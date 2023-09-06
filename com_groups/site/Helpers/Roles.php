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

use Joomla\CMS\Language\Text;
use THM\Groups\Adapters\Application;

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Roles implements Selectable
{
    use Named;

    /**
     * @inheritDoc
     */
    public static function getAll(): array
    {
        $db     = Application::getDB();
        $query  = $db->getQuery(true);
        $plural = $db->quoteName('plural_' . Application::getTag());
        $roles  = $db->quoteName('#__groups_roles');
        $query->select('*')->from($roles)->order($plural);
        $db->setQuery($query);

        if (!$roles = $db->loadObjectList('id')) {
            return [];
        }

        foreach ($roles as $roleID => $role) {
            $role->groups = RoleAssociations::byRoleID($roleID);
        }

        return $roles;
    }

    /**
     * Gets the current maximum value in the ordering column.
     * @return int the current maximum value in the ordering column
     */
    public static function getMaxOrdering(): int
    {
        $db       = Application::getDB();
        $query    = $db->getQuery(true);
        $ordering = $db->quoteName('ordering');
        $query->select("MAX($ordering)")->from($db->quoteName('#__groups_roles'));
        $db->setQuery($query);

        return (int) $db->loadResult();
    }

    /**
     * @inheritDoc
     *
     * @param bool $bound whether the role must already be associated
     */
    public static function getOptions(bool $associated = true): array
    {
        $plural    = 'plural_' . Application::getTag();
        $options   = [];
        $options[] = (object) [
            'text' => Text::_('GROUPS_NONE_MEMBER'),
            'value' => ''
        ];

        foreach (self::getAll() as $roleID => $role) {
            if ($associated and empty($role->groups)) {
                continue;
            }

            $options[] = (object) [
                'text' => $role->$plural,
                'value' => $roleID
            ];
        }

        return $options;
    }
}