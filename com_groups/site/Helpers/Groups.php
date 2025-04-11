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

use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\Database\ParameterType;
use THM\Groups\Adapters\Application;

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Groups implements Selectable
{
    use Named;

    public const PUBLIC = 1, REGISTERED = 2, AUTHOR = 3, EDITOR = 4, PUBLISHER = 5, MANAGER = 6, ADMIN = 7, SUPER_ADMIN = 8;

    public const DEFAULT = [
        self::ADMIN,
        self::AUTHOR,
        self::EDITOR,
        self::MANAGER,
        self::PUBLIC,
        self::PUBLISHER,
        self::REGISTERED,
        self::SUPER_ADMIN
    ];

    /**
     * @inheritDoc
     */
    public static function getAll(): array
    {
        $groups = self::getUserGroups();

        foreach ($groups as $groupID => $group) {
            if ($name = self::getName($groupID)) {
                $group->title = $name;
            }

            $group->roles = RoleAssociations::byGroupID($groupID);
        }

        return $groups;
    }

    /**
     * Gets the view levels associated with the group.
     *
     * @param   int  $groupID  the id of the group to get levels for
     *
     * @return array [id => title]
     */
    public static function getLevels(int $groupID): array
    {
        $group    = UserGroupsHelper::getInstance()->get($groupID);
        $groupIDs = $group->path;

        $db     = Application::database();
        $id     = $db->quoteName('id');
        $rules  = $db->quoteName('rules');
        $title  = $db->quoteName('title');
        $levels = $db->quoteName('#__viewlevels');

        $query  = $db->getQuery(true);
        $regex  = $query->concatenate(["'[,\\\\[]'", ':groupID', "'[,\\\\]]'"]);
        $return = [];
        $query->select([$id, $title])->from($levels)->where("$rules REGEXP $regex");

        do {
            $groupID = array_pop($groupIDs);
            $query->bind(':groupID', $groupID, ParameterType::INTEGER);
            $db->setQuery($query);

            if ($results = $db->loadAssocList('id', 'title')) {
                $return += $results;
            }
        }
        while ($groupIDs);

        asort($return);

        return $return;
    }

    /**
     * Gets the IDs of existing user groups.
     * @return int[]
     */
    public static function getIDs(): array
    {
        return array_keys(self::getAll());
    }

    /**
     * @inheritDoc
     */
    public static function getOptions(bool $allowDefault = false): array
    {
        $options = [];

        foreach (self::getAll() as $groupID => $group) {
            $disabled = (!$allowDefault and in_array($groupID, self::DEFAULT)) ? 'disabled' : '';

            $options[] = (object) [
                'disable' => $disabled,
                'text'    => self::getPrefix($group->level) . $group->title,
                'value'   => $group->id
            ];
        }

        return $options;
    }

    /**
     * Gets the prefix for hierarchical list displays.
     *
     * @param   int  $level  the nested level of the group
     *
     * @return string the prefix to display
     *
     */
    public static function getPrefix(int $level): string
    {
        $prefix = '';
        if ($level > 1) {
            $prefix .= str_repeat('&#8942;&nbsp;&nbsp;&nbsp;', $level - 2) . '&ndash;&nbsp;';
        }

        return $prefix;
    }

    /**
     * Gets the roles associated with the group.
     *
     * @param   int  $groupID  the id of the group to get roles for
     *
     * @return array [id => name]
     */
    public static function getRoles(int $groupID): array
    {
        $tag = Application::getTag();
        $db  = Application::database();

        $roleID     = $db->quoteName("r.id");
        $condition1 = $db->quoteName("ra.roleID") . " = $roleID";
        $condition2 = $db->quoteName("m.id") . ' = ' . $db->quoteName("ra.mapID");

        $query = $db->getQuery(true)
            ->select([$roleID, $db->quoteName("r.name_$tag", 'name')])
            ->from($db->quoteName('#__groups_roles', 'r'))
            ->join('inner', $db->quoteName('#__groups_role_associations', 'ra'), $condition1)
            ->join('inner', $db->quoteName('#__user_usergroup_map', 'm'), $condition2)
            ->where($db->quoteName("m.group_id") . ' = :groupID')
            ->bind(':groupID', $groupID, ParameterType::INTEGER)
            ->order($db->quoteName("r.name_$tag"));
        $db->setQuery($query);

        $results = $db->loadAssocList('id', 'name');

        return $results ?? [];
    }

    /**
     * Gets the existing stock of usergroups. stdClass[id => ug];
     * @return array
     */
    private static function getUserGroups(): array
    {
        return UserGroupsHelper::getInstance()->getAll();
    }
}