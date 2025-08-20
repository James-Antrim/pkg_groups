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

use Joomla\CMS\Helper\ContentHelper;
use THM\Groups\Adapters\{Application, User};

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
        return ContentHelper::getActions($context)->get('core.admin');
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

        $actions = ContentHelper::getActions($context);

        return ($actions->get('core.create') and $actions->get('core.edit') and $actions->get('core.edit.state'));
    }

    /**
     * Checks whether the user has access to change resource states.
     *
     * @param   string  $context  the context in which access is being checked
     *
     * @return bool true if the user has 'create' access, otherwise false
     */
    public static function changeState(string $context = 'com_users'): bool
    {
        return (self::administrate($context) or ContentHelper::getActions($context)->get('core.edit.state'));
    }

    /**
     * Checks whether the user has access to configure the component.
     * @return bool
     */
    public static function configure(): bool
    {
        return (self::administrate() or ContentHelper::getActions('com_users')->get('core.options'));
    }

    /**
     * Checks whether the user has access to create component resources.
     *
     * @param   string  $context     the context in which access is being checked
     * @param   int     $resourceID  the id of the category context as applicable
     *
     * @return bool
     */
    public static function create(string $context = 'com_users', int $resourceID = 0): bool
    {
        // Basic context rights
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
        return (self::administrate($context) or ContentHelper::getActions($context)->get('core.delete'));
    }

    /**
     * Checks whether the user has access to create component resources.
     *
     * @param   string  $context     the context in which access is being checked
     * @param   int     $resourceID  the id of a specific resource as relevant
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
        return (self::administrate() or ContentHelper::getActions($context)->get('core.manage'));
    }

    /**
     * Checks whether the user has access to publish users or a specific user.
     * @return bool true if the user has 'manage' access, otherwise false
     */
    public static function publish(int $id = 0): bool
    {
        if (self::changeState()) {
            return true;
        }

        if (!$id) {
            return false;
        }

        // People may update their own status attributes.
        return self::identity($id);
    }

    /**
     * Check whether the user has the access required to save the resource.
     *
     * @param   int     $id       the id of the resource to save
     * @param   string  $context  the context in which access is being checked
     *
     * @return bool
     */
    public static function save(string $context, int $id = 0): bool
    {
        if (self::administrate($context)) {
            return true;
        }

        switch ($context) {
            // Someone creating or editing content or the profile associated category
            case 'com_content.category':
                // user is groups-associated with category and global or individual enable
                // user can edit or save in with content permissions on this category
                return false;
            case 'com_content':
                // groups related content
                return (self::edit($context, $id) or self::create($context));
            case 'com_groups':
                if (self::manage()) {
                    return true;
                }
                return (self::edit($context, $id) or self::create($context));
            case 'com_users':
                return (self::edit($context, $id) or self::create($context) or ($id and self::identity($id)));
            default:
                return false;
        }
    }

    /**
     * Check whether the user has the access required to save a person as a resource.
     *
     * @param   int  $id
     *
     * @return bool
     */
    public static function saveUser(int $id): bool
    {
        return (self::save('com_users', $id) or ($id and self::identity($id)));
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

        $public = !Application::backend();

        // todo: square this with admin menu generation
        return match ($view) {
            // Administrative views and admin access was already checked
            'Attribute', 'Attributes',
            'Role', 'Roles',
            'Template', 'Templates' => false,

            // com users rights and no public display
            'Group', 'Groups' => self::manage(),

            // com content rights or public display
            'Content', 'Contents' => ($public or self::manage('com_content')),

            // com users rights or public display
            'Persons' => ($public or self::manage()),

            // Development queue
            //'Level', 'Levels' => false,

            // Viewing is allowed, functions, layouts and levels may still be restricted elsewhere.
            default => true,
        };
    }

    /**
     * com_user hard coded permissions
     * -------------------------------
     * Admin:
     * mail:???
     * notes (category based)
     * - add - core.create (this category, but none works for whatever reason?)
     * - actions - not empty state (?) + (core.admin or core.edit.state)
     * -- archive, checkin, publish, unpublish - core.edit.state
     * -- trash - !trash state and core.edit.state
     * - delete - trash state and core.delete
     * note (category based)
     * - apply & save - core.create (any category) or core.edit (this category)
     * - version - core.edit (w/ com_contenthistory)
     */
}