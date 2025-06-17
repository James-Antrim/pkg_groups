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
use THM\Groups\Adapters\{Application, Database as DB};

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
     * Retrieves a map of roleIDs => userIDs for the given group.
     *
     * @param   int  $groupID
     *
     * @return array
     */
    public static function associations(int $groupID): array
    {
        $query = DB::query();
        $query->select(DB::qn(['r.id', 'uugm.user_id'], ['roleID', 'userID']))
            ->from(DB::qn('#__user_usergroup_map', 'uugm'))
            ->innerJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.mapID', 'uugm.id'))
            ->innerJoin(DB::qn('#__groups_roles', 'r'), DB::qc('r.id', 'ra.roleID'))
            ->innerJoin(DB::qn('#__users', 'u'), DB::qc('u.id', 'uugm.user_id'))
            ->where(DB::qc('uugm.group_id', $groupID))
            ->order(implode(',', DB::qn(['r.ordering', 'u.surnames', 'u.forenames'])));
        DB::set($query);

        $results = [];

        foreach (DB::arrays() as $result) {
            $roleID = $result['roleID'];
            $userID = $result['userID'];
            if (empty($results[$roleID])) {
                $results[$roleID] = [];
            }
            if (empty($results[$roleID][$userID])) {
                $results[$roleID][$userID] = $userID;
            }
        }

        return $results;
    }

    /**
     * Gets the IDs of existing user groups.
     * @return int[]
     */
    public static function ids(): array
    {
        return array_keys(self::resources());
    }

    /**
     * Gets the view levels associated with the group.
     *
     * @param   int  $groupID  the id of the group to get levels for
     *
     * @return array [id => title]
     */
    public static function levels(int $groupID): array
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

    /** @inheritDoc */
    public static function options(bool $allowDefault = false): array
    {
        $options = [];

        foreach (self::resources() as $groupID => $group) {
            $disabled = (!$allowDefault and in_array($groupID, self::DEFAULT)) ? 'disabled' : '';

            $options[] = (object) [
                'disable' => $disabled,
                'text'    => self::prefix($group->level) . $group->title,
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
    public static function prefix(int $level): string
    {
        $prefix = '';
        if ($level > 1) {
            $prefix .= str_repeat('&#8942;&nbsp;&nbsp;&nbsp;', $level - 2) . '&ndash;&nbsp;';
        }

        return $prefix;
    }

    /**
     * Retrieves the ids of profiles directly associated with a group.
     *
     * @param   int        $groupID
     * @param   bool|null  $published  the published status of the user profiles
     *
     * @return array
     */
    public static function profileIDs(int $groupID = 0, bool|null $published = Users::PUBLISHED): array
    {
        $query = DB::query();
        $query->select('DISTINCT ' . DB::qn('user_id'))
            ->from(DB::qn('#__user_usergroup_map', 'uugm'))
            ->innerJoin(DB::qn('#__users', 'u'), DB::qn('u.id', 'uugm.user_id'));

        if ($groupID) {
            $query->where(DB::qc('group_id', $groupID));
        }

        if ($published !== null) {
            $query->where(DB::qc('u.published', (int) $published));
        }

        DB::set($query);

        return DB::integers();
    }

    /** @inheritDoc */
    public static function resources(): array
    {
        $groups = self::userGroups();

        foreach ($groups as $groupID => $group) {
            if ($name = self::getName($groupID)) {
                $group->title = $name;
            }

            $group->roles = RoleAssociations::byGroupID($groupID);
        }

        return $groups;
    }

    /**
     * Gets the roles associated with the group.
     *
     * @param   int  $groupID  the id of the group to get roles for
     *
     * @return array [id => name]
     */
    public static function roles(int $groupID): array
    {
        $tag = Application::tag();
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
    private static function userGroups(): array
    {
        return UserGroupsHelper::getInstance()->getAll();
    }
}