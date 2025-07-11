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

use THM\Groups\Adapters\{Database as DB};

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class RoleAssociations
{
    use Persistent;

    /**
     * Gets the ids of the associated roles
     *
     * @param   int  $groupID  the id of the group
     *
     * @return int[] the associated groups in the form assocID => roleID
     */
    public static function byGroupID(int $groupID): array
    {
        $query = DB::query();
        $query->select(DB::qn('ra') . '.*')
            ->from(DB::qn('#__groups_role_associations', 'ra'))
            ->innerJoin(DB::qn('#__user_usergroup_map', 'm'), DB::qc('m.id', 'ra.mapID'))
            ->where(DB::qc('m.group_id', $groupID));
        DB::set($query);

        return DB::arrays('id', 'roleID');
    }

    /**
     * Gets the ids of the associated groups
     *
     * @param   int  $roleID  the id of the role
     *
     * @return int[] the associated groups in the form assocID => groupID
     */
    public static function byRoleID(int $roleID): array
    {
        $query = DB::query();
        $query->select('*')->from(DB::qn('#__groups_role_associations'))->where(DB::qc('roleID', $roleID));
        DB::set($query);

        return DB::arrays('id', 'groupID');
    }
}