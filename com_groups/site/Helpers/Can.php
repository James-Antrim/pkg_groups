<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
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
     * @return bool true if the user has 'admin' access, otherwise false
     */
    public static function administrate(): bool
    {
        return ContentHelper::getActions('com_users')->get('core.admin');
    }

    /**
     * Checks whether the user has access to perform batch processing on component resources.
     *
     * @return bool true if the user has 'create' access, otherwise false
     */
    public static function batchProcess(): bool
    {
        if (self::administrate())
        {
            return true;
        }

        $actions = ContentHelper::getActions('com_users');

        return ($actions->get('core.create') and $actions->get('core.edit') and $actions->get('core.edit.state'));
    }

    /**
     * Checks whether the user has access to change resource states.
     *
     * @return bool true if the user has 'create' access, otherwise false
     */
    private static function changeState(): bool
    {
        return (self::administrate() or ContentHelper::getActions('com_users')->get('core.edit.state'));
    }

    /**
     * Checks whether the user has access to change the state of a user.
     *
     * @return bool true if the user has 'manage' access, otherwise false
     */
    public static function changePersonState(int $id = 0): bool
    {
        if (self::changeState())
        {
            return true;
        }

        // People may update their own status attributes.
        return self::identity($id);
    }

    /**
     * Checks whether the user has access to configure the component.
     *
     * @return bool
     */
    public static function configure(): bool
    {
        return (self::administrate() or ContentHelper::getActions('com_users')->get('core.options'));
    }

    /**
     * Checks whether the user has access to create component resources.
     *
     * @return bool
     */
    public static function create(): bool
    {
        return (self::administrate() or ContentHelper::getActions('com_users')->get('core.create'));
    }

    /**
     * Checks whether the user has access to debug the users component.
     *
     * @return bool
     */
    public static function debug(): bool
    {
        return self::manage();
    }

    /**
     * Checks whether the user has access to delete component resources.
     *
     * @return bool
     */
    public static function delete(): bool
    {
        return (self::administrate() or ContentHelper::getActions('com_users')->get('core.delete'));
    }

    /**
     * Checks whether the user has access to create component resources.
     *
     * @return bool
     */
    public static function edit(): bool
    {
        return (self::administrate() or ContentHelper::getActions('com_users')->get('core.edit'));
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
     *
     * @return bool true if the user has 'manage' access, otherwise false
     *
     * @todo make sure this is being used as intended...
     */
    public static function manage(): bool
    {
        return (self::administrate() or ContentHelper::getActions('com_users')->get('core.manage'));
    }

    /**
     * Check whether the user has the access required to save the resource.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function save(int $id): bool
    {
        return $id ? self::edit() : self::create();
    }

    /**
     * Check whether the user has the access required to save the current resource and create a new one.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function saveNew(int $id): bool
    {
        return (self::save($id) and self::create());
    }

    /**
     * Check whether the user has the access required to save the current resource and create a new one.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function saveNewPerson(int $id): bool
    {
        return (self::savePerson($id) and self::create());
    }

    /**
     * Check whether the user has the access required to save a person as a resource.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function savePerson(int $id): bool
    {
        return (self::save($id) or self::identity($id));
    }

    /**
     * Checks whether the user has viewing access to the view.
     * @param string $view
     *
     * @return bool
     */
    public static function view(string $view): bool
    {
        return match ($view)
        {
            // purely administrative views
            'Attributes', 'Groups', 'Roles' => self::administrate(),
            // the published content/contents of a specific person or content administration
            'Content', 'Contents' => (Input::getInt('personID') or self::administrate()),
            // persons associated with a specific group or person administration
            'Persons' => (Input::getInt('groupID') or self::administrate()),
            // Development queue
            'Attribute', 'Group', 'Level', 'Levels', 'Role', 'Template', 'Templates' => false,
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