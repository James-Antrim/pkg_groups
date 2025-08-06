<?php
/**
 * @package     Groups
 * @extension   pkg_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Joomla\CMS\User\User as Instance;
use Joomla\CMS\User\UserFactory;
use Joomla\CMS\User\UserFactoryInterface;

/**
 * Class wraps functions of user related classes for easier access and inclusion.
 */
class User
{
    /**
     * Method to check User object authorisation against an access control
     * object and optionally an access extension object
     *
     * @param   string       $action  the name of the action to check for permission.
     * @param   string|null  $asset   the name of the asset on which to perform the action.
     *
     * @return  bool
     */
    public static function authorise(string $action = 'core.admin', string $asset = null): bool
    {
        return self::instance()->authorise($action, $asset);
    }

    /**
     * Gets the id of the user, optionally by username.
     *
     * @param   string  $userName
     *
     * @return int
     */
    public static function id(string $userName = ''): int
    {
        return self::instance($userName)->id ?: 0;
    }

    /**
     * Gets a user object (specified or current).
     *
     * @param   int|string  $userID  the user identifier (id or name)
     *
     * @return Instance
     */
    public static function instance(int|string $userID = 0): Instance
    {
        /** @var UserFactory $userFactory */
        $userFactory = Application::container()->get(UserFactoryInterface::class);

        // Get a specific user.
        if ($userID) {
            return is_int($userID) ? $userFactory->loadUserById($userID) : $userFactory->loadUserByUsername($userID);
        }

        $current = Application::instance()->getIdentity();

        // Enforce type consistency, by overwriting the potential null from getIdentity.
        return $current ?: $userFactory->loadUserById(0);
    }

    /**
     * Retrieves the view access level ids allowed the user.
     *
     * @param   int  $userID
     *
     * @return array
     */
    public static function levels(int $userID = 0): array
    {
        $user = self::instance($userID);
        return $user->getAuthorisedViewLevels();
    }

    /**
     * Gets the name of the user.
     *
     * @param   int|string  $userID
     *
     * @return string
     */
    public static function name(int|string $userID = 0): string
    {
        return self::instance($userID)->name ?: '';
    }

    /**
     * Retrieves the name of the current user.
     * @return string the name of the user
     */
    public static function token(): string
    {
        $user = self::instance();

        if (!$user->email or !$user->registerDate) {
            return '';
        }

        // Joomla documented the wrong type for registerDate which is a string
        return urlencode(password_hash($user->email . $user->registerDate, PASSWORD_BCRYPT));
    }

    /**
     * Gets the account name of the user, optionally by id.
     *
     * @param   int  $userID  the id of
     *
     * @return string
     */
    public static function userName(int $userID = 0): string
    {
        return self::instance($userID)->username ?: '';
    }
}