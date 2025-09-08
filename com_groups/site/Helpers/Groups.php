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
use THM\Groups\Tables\Groups as Table;

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Groups extends Selectable
{
    use Named;

    public const PUBLIC = 1, REGISTERED = 2, AUTHOR = 3, EDITOR = 4, PUBLISHER = 5, MANAGER = 6, ADMIN = 7, SUPER_ADMIN = 8;

    public const STANDARD_GROUPS = [
        self::ADMIN,
        self::AUTHOR,
        self::EDITOR,
        self::MANAGER,
        self::PUBLIC,
        self::PUBLISHER,
        self::REGISTERED,
        self::SUPER_ADMIN
    ];

    public const REMOVE = 0;

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
            ->innerJoin(DB::qn('#__users', 'u'), DB::qc('u.id', 'uugm.user_id'))
            ->leftJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.mapID', 'uugm.id'))
            ->leftJoin(DB::qn('#__groups_roles', 'r'), DB::qc('r.id', 'ra.roleID'))
            ->where(DB::qcs([['uugm.group_id', $groupID], ['u.published', Users::PUBLISHED]]))
            ->order(implode(',', DB::qn(['r.ordering', 'u.surnames', 'u.forenames'])));

        DB::set($query);

        $results = [];

        foreach (DB::arrays() as $result) {
            $roleID = $result['roleID'] ?: Roles::MEMBERS;
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

        $query  = DB::query();
        $regex  = $query->concatenate(["'[,\\\\[]'", ':groupID', "'[,\\\\]]'"]);
        $return = [];
        $query->select(DB::qn(['id', 'title']))
            ->from(DB::qn('#__viewlevels'))
            ->bind(':groupID', $groupID, ParameterType::INTEGER)
            ->where(DB::qn('rules') . " REGEXP $regex");

        do {
            $groupID = array_pop($groupIDs);
            DB::set($query);

            if ($results = DB::arrays('id', 'title')) {
                $return += $results;
            }
        }
        while ($groupIDs);

        asort($return);

        return $return;
    }

    /**
     * Gets the localized name of the group associated with the given id.
     *
     * @param   int  $resourceID
     *
     * @return string
     */
    public static function name(int $resourceID): string
    {
        $group = new Table();

        if (!$group->load($resourceID)) {
            return '';
        }

        return Application::tag() === 'en' ? $group->name_en : $group->name_de;
    }

    /** @inheritDoc */
    public static function options(bool $allowDefault = false): array
    {
        $options = [];

        foreach (self::resources() as $groupID => $group) {
            $disabled = (!$allowDefault and in_array($groupID, self::STANDARD_GROUPS)) ? 'disabled' : '';

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
        if ($level) {
            $prefix .= str_repeat('&#8942;&nbsp;&nbsp;&nbsp;', $level - 1) . '&ndash;&nbsp;';
        }

        return $prefix;
    }

    /**
     * Retrieves the ids of profiles directly associated with a group.
     *
     * @param   int  $groupID
     *
     * @return array
     */
    public static function profileIDs(int $groupID = 0): array
    {
        $query = DB::query();
        $query->select('DISTINCT ' . DB::qn('user_id'))
            ->from(DB::qn('#__user_usergroup_map', 'uugm'))
            ->innerJoin(DB::qn('#__users', 'u'), DB::qn('u.id', 'uugm.user_id'))
            ->where(DB::qc('u.published', Users::PUBLISHED))
            ->order(implode(',', DB::qn(['u.surnames', 'u.forenames'])));

        if ($groupID) {
            $query->where(DB::qc('group_id', $groupID));
        }

        DB::set($query);

        return DB::integers();
    }

    /** @inheritDoc */
    public static function resources(): array
    {
        $groups = self::userGroups();

        foreach ($groups as $groupID => $group) {
            if ($name = self::name($groupID)) {
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
        $tag   = Application::tag();
        $query = DB::query();
        $query->select([DB::qn("r.id"), DB::qn("r.name_$tag", 'name')])
            ->from(DB::qn('#__groups_roles', 'r'))
            ->innerJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.roleID', 'r.id'))
            ->innerJoin(DB::qn('#__user_usergroup_map', 'm'), DB::qc('m.id', 'ra.mapID'))
            ->where(DB::qc('m.group_id', $groupID))
            ->order(DB::qn("r.name_$tag"));
        DB::set($query);

        return DB::arrays('id', 'name');
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