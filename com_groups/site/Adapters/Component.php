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
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Registry\Registry;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

class Component
{
	/**
	 * Checks whether the current context is the administrator context.
	 *
	 * @return bool
	 */
	public static function backend(): bool
	{
		return self::getApplication()->isClient('administrator');
	}

	/**
	 * Performs a redirect on error.
	 *
	 * @param   int  $code  the error code
	 *
	 * @return void
	 */
	public static function error(int $code)
	{
		$URI     = Uri::getInstance();
		$current = $URI->toString();

		//TODO: Add logging

		if ($code === 401)
		{
			$return   = urlencode(base64_encode($current));
			$URL      = Uri::base() . "?option=com_users&view=login&return=$return";
			$severity = 'notice';
		}
		else
		{
			switch ($code)
			{
				case 400:
				case 404:
					$severity = 'notice';
					break;
				case 403:
				case 412:
					$severity = 'warning';
					break;
				case 500:
				case 501:
				case 503:
				default:
					$severity = 'error';
					break;

			}

			$referrer = Input::getInput()->server->getString('HTTP_REFERER', Uri::base());
			$URL      = $referrer === $current ? Uri::base() : $referrer;
		}

		self::message(Text::_("GROUPS_$code"), $severity);
		self::redirect($URL, $code);
	}

	/**
	 * Surrounds the call to the application with a try catch so that not every function needs to have a throws tag. If
	 * the application has an error it would have never made it to the component in the first place, so the error would
	 * not have been thrown in this call regardless.
	 *
	 * @return CMSApplicationInterface|null
	 */
	public static function getApplication(): ?CMSApplicationInterface
	{
		try
		{
			return Factory::getApplication();
		}
		catch (Exception $exc)
		{
			return null;
		}
	}

	/**
	 * Gets the name of an object's class without its namespace.
	 *
	 * @param   object|string  $object  the object whose namespace free name is requested or the fq name of the class to be loaded
	 *
	 * @return string the name of the class without its namespace
	 */
	public static function getClass($object): string
	{
		$fqName   = is_string($object) ? $object : get_class($object);
		$nsParts  = explode('\\', $fqName);
		$lastItem = array_pop($nsParts);

		return empty($lastItem) ? 'Dashboard' : $lastItem;
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
	 * Gets the language portion of the localization tag.
	 *
	 * @return string
	 */
	public static function getTag(): string
	{
		$language = self::getApplication()->getLanguage();

		return explode('-', $language->getTag())[0];
	}

	/**
	 * Masks the Joomla application enqueueMessage function
	 *
	 * @param   string  $message  the message to enqueue
	 * @param   string  $type     how the message is to be presented
	 *
	 * @return void
	 */
	public static function message(string $message, string $type = 'message')
	{
		self::getApplication()->enqueueMessage(Text::_($message), $type);
	}

	/**
	 * Checks whether the client device is a mobile phone.
	 *
	 * @return bool
	 */
	public static function mobile(): bool
	{
		/** @var CMSApplication $app */
		$app     = self::getApplication();
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
	public static function redirect(string $url = '', int $status = 303)
	{
		$url = $url ?: Uri::getInstance()::base();

		/** @var CMSApplication $app */
		$app = self::getApplication();
		$app->redirect($url, $status);
	}
}