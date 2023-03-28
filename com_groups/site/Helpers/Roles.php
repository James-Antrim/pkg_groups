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

use THM\Groups\Adapters\Application;

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Roles implements Selectable
{
    use Named;

    public const MEMBER = 1;

    /**
     * @inheritDoc
     */
    public static function getAll(): array
    {
        $db    = Application::getDB();
        $query = $db->getQuery(true);
        $roles = $db->quoteName('#__groups_roles');
        $query->select('*')->from($roles);
        $db->setQuery($query);

        if (!$roles = $db->loadObjectList('id'))
        {
            return [];
        }

        foreach ($roles as $roleID => $role)
        {
            $role->groups = RoleAssociations::byRoleID($roleID);
        }

        return $roles;
    }

    /**
     * @inheritDoc
     */
    public static function getOptions(): array
    {
        $namesColumn = 'names_' . Application::getTag();
        $options     = [];

        foreach (self::getAll() as $roleID => $role)
        {
            if (empty($role->groups))
            {
                continue;
            }

            $options[] = (object)[
                'text' => $role->$namesColumn,
                'value' => $roleID
            ];
        }

        return $options;
    }
}