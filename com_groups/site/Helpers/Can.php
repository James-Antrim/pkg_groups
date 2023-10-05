<?php
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
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;

class Can
{
    /**
     * Checks whether the user has access to administrate the component.
     *
     * @param string $context the context in which access is being checked
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
     * @param string $context the context in which access is being checked
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
     * @param string $context the context in which access is being checked
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
     * @param string $context the context in which access is being checked
     *
     * @return bool
     */
    public static function create(string $context = 'com_users'): bool
    {
        return (self::administrate($context) or ContentHelper::getActions($context)->get('core.create'));
    }

    /**
     * Checks whether the user has access to debug the users component.
     *
     * @param string $context the context in which access is being checked
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
     * @param string $context the context in which access is being checked
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
     * @param string $context the context in which access is being checked
     *
     * @return bool
     */
    public static function edit(string $context = 'com_users'): bool
    {
        return (self::administrate($context) or ContentHelper::getActions($context)->get('core.edit'));
    }

    /**
     * Checks whether the user against which access rights are being checked is the current user.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function identity(int $id = 0): bool
    {
        return ($id and Application::getUser()->id === $id);
    }

    /**
     * Checks whether the user has administrative (back-end) access to the component.
     * @return bool true if the user has 'manage' access, otherwise false
     */
    public static function manage(): bool
    {
        return (self::administrate() or ContentHelper::getActions('com_users')->get('core.manage'));
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
     * @param int    $id      the id of the resource to save
     * @param string $context the context in which access is being checked
     *
     * @return bool
     */
    public static function save(int $id, string $context = 'com_users'): bool
    {
        return $id ? self::edit($context) : self::create($context);
    }

    /**
     * Check whether the user has the access required to save a person as a resource.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function saveUser(int $id): bool
    {
        return (self::save($id) or ($id and self::identity($id)));
    }

    /**
     * Checks whether the user has viewing access to the view.
     *
     * @param string $view
     *
     * @return bool
     */
    public static function view(string $view): bool
    {
        return match ($view) {
            // purely administrative views
            'Attribute', 'Attributes',
            'Role', 'Roles',
            'Template', 'Templates',
                // TODO group/s authorization was admin in users, but need to be revisited for presentation
            'Group', 'Groups' => self::administrate(),
            // the published content/contents of a specific person or content administration
            'Content', 'Contents' => (Input::getInt('personID') or self::administrate()),
            // persons associated with a specific group or person administration
            'Persons' => (Input::getInt('groupID') or self::administrate()),
            // Development queue
            'Level', 'Levels' => false,
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