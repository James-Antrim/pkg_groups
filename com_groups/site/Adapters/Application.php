<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Exception;
use Joomla\CMS\Application\{CMSApplication, CMSApplicationInterface, WebApplication};
use Joomla\CMS\{Component\ComponentHelper, Document\Document, Factory, Language\Language};
use Joomla\CMS\{Menu\MenuItem, Session\Session, Uri\Uri};
use Joomla\CMS\Extension\{ComponentInterface, ExtensionHelper};
use Joomla\CMS\User\{User, UserFactory, UserFactoryInterface};
use Joomla\Database\DatabaseDriver;
use Joomla\DI\Container;
use Joomla\Registry\Registry;
use THM\Groups\Component;

/**
 * Aggregates various core joomla functions spread around multiple core classes and offers shortcuts to them with no
 * thrown exceptions.
 */
class Application
{
    /**
     * Predefined Joomla message types without unnecessary prefixing.
     * @see    CMSApplicationInterface
     */
    public const ERROR = 'error', MESSAGE = 'message', NOTICE = 'notice', WARNING = 'warning';

    /**
     * Unused locally, but Joomla supported.
     * @ALERT, @CRITICAL, @EMERGENCY: danger
     * @DEBUG, @INFO: info
     *
     * public const ALERT = 'alert', CRITICAL = 'critical', DEBUG = 'debug', EMERGENCY = 'emergency', INFO = 'info';
     * @noinspection GrazieInspection
     */

    /**
     * Checks whether the current context is the administrator context.
     * @return bool
     */
    public static function backend(): bool
    {
        return self::instance()->isClient('administrator');
    }

    /**
     * Returns the groups component object.
     * @return Component
     */
    public static function component(): Component
    {
        $component = 'groups';
        $type      = ComponentInterface::class;

        // Check if the extension is already loaded
        if (!empty(ExtensionHelper::$extensions[$type][$component])) {
            return ExtensionHelper::$extensions[$type][$component];
        }

        $application = self::instance();
        $container   = self::container()->createChild();

        $provider = require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/provider.php';
        $provider->register($container);

        $extension                                      = $container->get($type);
        ExtensionHelper::$extensions[$type][$component] = $extension;

        return $extension;
    }

    /**
     * Shortcuts configuration access.
     * @return Registry
     */
    public static function configuration(): Registry
    {
        /** @var WebApplication $app */
        $app = self::instance();

        return $app->getConfig();
    }

    /**
     * Shortcuts container access.
     * @return Container
     */
    public static function container(): Container
    {
        return Factory::getContainer();
    }

    /**
     * Shortcuts container access.
     * @return DatabaseDriver
     */
    public static function database(): DatabaseDriver
    {
        return self::container()->get('DatabaseDriver');
    }

    /**
     * Shortcuts document access.
     * @return Document
     */
    public static function document(): Document
    {
        /** @var WebApplication $app */
        $app = self::instance();

        return $app->getDocument();
    }

    /**
     * Determines whether the view was called from a dynamic context: no active menu item or the active item does not
     * belong to the component.
     * @return bool
     */
    public static function dynamic(): bool
    {
        $menuItem = self::menuItem();

        if (!empty($menuItem) and $menuItem->type === 'component' and $menuItem->component === 'groups') {
            return false;
        }

        return true;
    }

    /**
     * Performs a redirect on error.
     *
     * @param   int     $code  the error code
     * @param   string  $key   the localization key for a message
     *
     * @return void
     */
    public static function error(int $code, string $key = ''): void
    {
        $current = Uri::getInstance()->toString();

        // Unauthenticated
        if ($code === 401) {
            $message  = $code;
            $return   = urlencode(base64_encode($current));
            $severity = self::NOTICE;
            $url      = Uri::base() . "?option=com_users&view=login&return=$return";
        }
        // Unauthorized
        elseif ($code === 403) {
            $message  = $code;
            $url      = Uri::base();
            $severity = self::WARNING;
        }
        else {
            // Use specific message if requested.
            $message  = $key ?: $code;
            $severity = match ($code) {
                // Form error / not found
                400, 404 => self::NOTICE,
                // Inconsistent data
                412 => self::WARNING,
                default => self::ERROR,
            };

            if ($severity === self::ERROR) {
                // TODO turn this into logging before productive release
                echo "<pre>" . print_r($message, true) . "</pre>";
                $exc = new Exception();
                echo "<pre>" . print_r($exc->getTraceAsString(), true) . "</pre>";
                die;
            }

            $url = Input::getInput()->server->getString('HTTP_REFERER', Uri::base());
        }

        self::message($message, $severity);
        self::redirect($url, $code);
    }

    /**
     * Gets the component's MVC factory.
     *
     * @return MVCFactory
     */
    public static function factory(): MVCFactory
    {
        return self::component()->mvcFactory();
    }

    /**
     * Gets the name of an object's class without its namespace.
     *
     * @param   object|string  $object  the object whose namespace free name is requested or the fq name of the class to be
     *                                  loaded
     *
     * @return string the name of the class without its namespace
     */
    public static function getClass(object|string $object): string
    {
        $fqName   = is_string($object) ? $object : get_class($object);
        $nsParts  = explode('\\', $fqName);
        $lastItem = array_pop($nsParts);

        return empty($lastItem) ? 'Groups' : $lastItem;
    }

    /**
     * Method to get the application language object.
     * @return  Language  The language object
     */
    public static function getLanguage(): Language
    {
        return self::instance()->getLanguage();
    }

    /**
     * Gets the parameter object for the component
     *
     * @param   string  $component  the component name.
     *
     * @return  Registry
     */
    public static function getParams(string $component = 'com_groups'): Registry
    {
        return ComponentHelper::getParams($component);
    }

    /**
     * Gets the session from the application container.
     * @return Session
     */
    public static function getSession(): Session
    {
        return self::instance()->getSession();
    }

    /**
     * Gets a user object (specified or current).
     *
     * @param   int|string  $userID  the user identifier (id or name)
     *
     * @return User
     */
    public static function getUser(int|string $userID = 0): User
    {
        /** @var UserFactory $userFactory */
        $userFactory = self::container()->get(UserFactoryInterface::class);

        // Get a specific user.
        if ($userID) {
            return is_int($userID) ? $userFactory->loadUserById($userID) : $userFactory->loadUserByUsername($userID);
        }

        $current = self::instance()->getIdentity();

        // Enforce type consistency, by overwriting the potential null from getIdentity.
        return $current ?: $userFactory->loadUserById(0);
    }

    /**
     * Gets the user's state's property value.
     *
     * @param   string  $property  the property name
     * @param   mixed   $default   the optional default value
     *
     * @return  mixed  the property value or null
     * @see CMSApplication::getUserState()
     */
    public static function getUserState(string $property, mixed $default = null): mixed
    {
        /** @var CMSApplication $app */
        $app = self::instance();

        return $app->getUserState($property, $default);
    }

    /**
     * Gets the property value from the state, overwriting the value from the request if available.
     *
     * @param   string  $property  the property name
     * @param   string  $request   the name of the property as passed in a request.
     * @param   mixed   $default   the optional default value
     * @param   string  $type      the optional name of the type filter to use on the variable
     *
     * @return  mixed  The request user state.
     * @see CMSApplication::getUserStateFromRequest(), InputFilter::clean()
     */
    public static function getUserRequestState(
        string $property,
        string $request,
        mixed $default = null,
        string $type = 'none'
    ): mixed
    {
        /** @var CMSApplication $app */
        $app = self::instance();

        return $app->getUserStateFromRequest($property, $request, $default, $type);
    }

    /**
     * Performs handling for joomla's internal errors not handled by joomla.
     *
     * @param   Exception  $exception  the joomla internal error being thrown instead of handled
     *
     * @return void
     */
    public static function handleException(Exception $exception): void
    {
        $code    = $exception->getCode() ?: 500;
        $message = $exception->getMessage();
        echo "<pre>" . print_r($exception->getTraceAsString(), true) . "</pre>";
        self::error($code, $message);
    }

    /**
     * Surrounds the call to the application with a try catch so that not every function needs to have a throws tag. If
     * the application has an error it would have never made it to the component in the first place, so the error would
     * not have been thrown in this call regardless.
     * @return CMSApplicationInterface|null
     */
    public static function instance(): ?CMSApplicationInterface
    {
        $application = null;

        try {
            $application = Factory::getApplication();
        }
        catch (Exception $exception) {
            self::handleException($exception);
        }

        return $application;
    }

    /**
     * Gets the current menu item.
     * @return MenuItem|null the current menu item or null
     */
    public static function menuItem(int $itemID = 0): ?MenuItem
    {
        /** @var CMSApplication $app */
        $app = self::instance();

        if ($menu = $app->getMenu()) {
            if ($itemID) {
                return $menu->getItem($itemID);
            }

            return $menu->getActive();
        }

        return null;
    }

    /**
     * Masks the Joomla application enqueueMessage function
     *
     * @param   string  $message  the message to enqueue
     * @param   string  $type     how the message is to be presented
     *
     * @return void
     */
    public static function message(string $message, string $type = self::MESSAGE): void
    {
        self::instance()->enqueueMessage(Text::_($message), $type);
    }

    /**
     * Checks whether the client device is a mobile phone.
     * @return bool
     */
    public static function mobile(): bool
    {
        /** @var CMSApplication $app */
        $app     = self::instance();
        $client  = $app->client;
        $tablets = [$client::IPAD, $client::ANDROIDTABLET];

        return ($client->mobile and !in_array($client->platform, $tablets));
    }

    /**
     * Redirect to another URL.
     *
     * @param   string  $url     The URL to redirect to. Can only be http/https URL
     * @param   int     $status  The HTTP 1.1 status code to be provided. 303 is assumed by default.
     *
     * @return  void
     */
    public static function redirect(string $url = '', int $status = 303): void
    {
        $url = $url ?: Uri::getInstance()::base();

        /** @var CMSApplication $app */
        $app = self::instance();
        $app->redirect($url, $status);
    }

    /**
     * Gets the language portion of the localization tag.
     * @return string
     */
    public static function tag(): string
    {
        $language = self::instance()->getLanguage();

        return explode('-', $language->getTag())[0];
    }
}