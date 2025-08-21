<?php /** @noinspection GrazieInspection */

/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use THM\Groups\Adapters\User;

/**
 * Determines user permissions.
 */
class Can
{
    /**
     * Checks whether the user has access to administrate the component.
     *
     * @param   string  $context  the context in which access is being checked
     *
     * @return bool true if the user has 'admin' access, otherwise false
     */
    public static function administrate(string $context = 'com_users'): bool
    {
        return (User::authorise() or User::authorise('core.admin', $context));
    }

    /**
     * Checks whether the user has access to perform batch processing on component resources.
     *
     * @param   string  $context  the context in which access is being checked
     *
     * @return bool true if the user has 'create' access, otherwise false
     */
    public static function batchProcess(string $context = 'com_users'): bool
    {
        if (self::administrate($context)) {
            return true;
        }

        return (
            User::authorise('core.create', $context)
            and User::authorise('core.edit', $context)
            and User::authorise('core.edit.state', $context)
        );
    }

    /**
     * Checks whether the user has access to change resource states.
     *
     * @param   string  $context     the context of the access request
     * @param   int     $resourceID  the id of the resource if relevant
     *
     * @return bool true if the user has 'create' access, otherwise false
     */
    public static function changeState(string $context = 'com_users', int $resourceID = 0): bool
    {
        if (
            User::authorise('core.admin', $context)
            or User::authorise('core.manage', $context)
            or User::authorise('core.edit', $context)
            or User::authorise('core.edit.state', $context)) {
            return true;
        }

        // If no resource id was given there is nothing more to check
        if (empty($resourceID)) {
            return false;
        }

        switch ($context) {
            case 'com_content.article':
                if (User::authorise('core.edit.state', "com_content.article.$resourceID")) {
                    return true;
                }

                $userID = Pages::userID($resourceID);
                return ($userID === User::id() and Users::content($userID));
            case 'com_content.category':
                $asset = "$context.$resourceID";
                if (User::authorise('core.edit.state', $asset) or User::authorise('core.edit.own', $asset)) {
                    return true;
                }

                // Rights specific to the groups component, publication state ignored because of identity at this level.
                $userID = Categories::userID($resourceID);
                return ($userID === User::id() and Users::content($userID));
            default:
                return false;
        }
    }

    /**
     * Checks whether the user has access to configure the component.
     * @return bool
     */
    public static function configure(): bool
    {
        return (self::administrate() or User::authorise('core.options', 'com_users'));
    }

    /**
     * Checks whether the user has access to create component resources.
     *
     * @param   string  $context     the context of the access request
     * @param   int     $resourceID  the id of the resource if relevant
     *
     * @return bool
     */
    public static function create(string $context = 'com_users', int $resourceID = 0): bool
    {
        if (
            User::authorise('core.admin', $context)
            or User::authorise('core.manage', $context)
            or User::authorise('core.create', $context)) {
            return true;
        }

        if (empty($resourceID)) {
            return false;
        }

        switch ($context) {
            case 'com_content.article':
                if (!$resourceID = Pages::categoryID($resourceID)) {
                    return false;
                }

                if (User::authorise('core.create', "com_content.category.$resourceID")) {
                    return true;
                }

                $userID = Categories::userID($resourceID);
                return ($userID === User::id() and Users::content($userID));
            case 'com_content.category':
                if (User::authorise('core.create', "$context.$resourceID")) {
                    return true;
                }

                $userID = Categories::userID($resourceID);
                return ($userID === User::id() and Users::content($userID));
            default:
                return false;
        }
    }

    /**
     * Checks whether the user has access to debug the users component.
     *
     * @param   string  $context  the context in which access is being checked
     *
     * @return bool
     */
    public static function debug(string $context = 'com_users'): bool
    {
        return self::manage($context);
    }

    /**
     * Checks whether the user has access to delete component resources.
     *
     * @param   string  $context  the context in which access is being checked
     *
     * @return bool
     */
    public static function delete(string $context = 'com_users'): bool
    {
        return (self::administrate($context) or User::authorise('core.delete', $context));
    }

    /**
     * Checks whether the user has access to create component resources.
     *
     * @param   string  $context     the context of the access request
     * @param   int     $resourceID  the id of the resource if relevant
     *
     * @return bool
     */
    public static function edit(string $context = 'com_users', int $resourceID = 0): bool
    {
        // Basic context rights
        if (
            User::authorise('core.admin', $context)
            or User::authorise('core.manage', $context)
            or User::authorise('core.edit', $context)) {
            return true;
        }

        // If no resource id was given there is nothing more to check
        if (empty($resourceID)) {
            return false;
        }

        switch ($context) {
            case 'com_content.article':
                if (User::authorise('core.edit', "com_content.article.$resourceID")) {
                    return true;
                }

                $userID = Pages::userID($resourceID);
                return ($userID === User::id() and Users::content($userID));
            case 'com_content.category':
                $asset = "$context.$resourceID";
                if (User::authorise('core.edit', $asset) or User::authorise('core.edit.own', $asset)) {
                    return true;
                }

                // Rights specific to the groups component, publication state ignored because of identity at this level.
                $userID = Categories::userID($resourceID);
                return ($userID === User::id() and Users::content($userID));
            default:
                return false;
        }
    }

    /**
     * Checks whether the user against which access rights are being checked is the current user.
     *
     * @param   int  $id
     *
     * @return bool
     */
    private static function identity(int $id = 0): bool
    {
        return ($id and User::id() === $id);
    }

    /**
     * Checks whether the user has administrative (back-end) access to the component.
     * @return bool true if the user has 'manage' access, otherwise false
     */
    public static function manage(string $context = 'com_users'): bool
    {
        return (self::administrate() or User::authorise('core.manage', $context));
    }

    /**
     * Checks whether the user is authorized to reorder the resources in the given context.
     *
     * @param   string  $context     the context of the access request
     * @param   int     $resourceID  the id of the resource if relevant
     *
     * @return bool
     */
    public static function reorder(string $context = 'com_content', int $resourceID = 0): bool
    {
        if (self::administrate($context) or self::manage($context) or self::changeState($context, $resourceID)) {
            return true;
        }
        return false;
    }

    /**
     * Checks whether the user is authorized to reorder the resources in the given context.
     *
     * @param   array  $resourceIDs  the ids of the resources
     *
     * @return bool
     */
    public static function reorderPages(array $resourceIDs): bool
    {
        if (self::reorder()) {
            return true;
        }

        foreach ($resourceIDs as $resourceID) {
            if (!Can::changeState("com_content.article", $resourceID)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check whether the user has the access required to save the resource.
     *
     * @param   string  $context     the context of the access request
     * @param   int     $resourceID  the id of the resource if relevant
     *
     * @return bool
     */
    public static function save(string $context, int $resourceID = 0): bool
    {
        if (self::administrate($context) or self::manage($context)) {
            return true;
        }

        switch ($context) {
            case 'com_content':
            case 'com_groups' :
                return $resourceID ? self::edit($context, $resourceID) : self::create($context);
            case 'com_users':
                if ($resourceID) {
                    return (self::edit($context, $resourceID) or self::identity($resourceID));
                }
                return self::create($context);
            default:
                return false;
        }
    }

    /**
     * Checks whether the user has viewing access to the view.
     *
     * @param   string  $view
     *
     * @return bool
     */
    public static function view(string $view): bool
    {
        if (self::administrate()) {
            return true;
        }

        // todo: square this with admin menu generation
        return match ($view) {
            // Administrative views and admin access was already checked
            'Attribute', 'Attributes',
            'Role', 'Roles',
            'Template', 'Templates' => false,
            'Group', 'Groups', 'User', 'Users' => self::manage(),
            'Content', 'Contents' => self::manage('com_content'),

            // Development queue
            //'Level', 'Levels' => false,

            // Viewing is allowed, functions, layouts and levels may still be restricted elsewhere.
            default => true,
        };
    }
}