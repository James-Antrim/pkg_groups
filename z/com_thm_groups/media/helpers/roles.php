<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\Database as DB;
use THM\Groups\Helpers\Roles;

/**
 * Class providing helper functions role entities
 */
class THM_GroupsHelperRoles
{
    /**
     * Retrieves the id of a specific group/role association.
     *
     * @param   int     $roleID      the id of the role
     * @param   int     $resourceID  the id of the resource with which the role is associated
     * @param   string  $resource    the context of
     *
     * @return int
     */
    public static function getAssocID(int $roleID, int $resourceID, string $resource): int
    {
        $query = DB::query();

        $query->select('id');
        if ($resource === 'group') {
            $query->from('#__thm_groups_role_associations')
                ->where("groupID = '$resourceID'")
                ->where("roleID = '$roleID'");
        }
        elseif ($resource === 'profile') {
            $query->from('#__thm_groups_profile_associations')
                ->where("profileID = '$resourceID'")
                ->where("role_associationID = '$roleID'");
        }
        else {
            return 0;
        }

        DB::set($query);
        return DB::integer();
    }

    /**
     * Retrieves the name of the role by means of its association with a group.
     *
     * @param   int   $assocID  the id of the group -> role association
     * @param   bool  $block    whether redundant roles ('Mitglied') should be blocked
     *
     * @return  string the name of the role referenced in the association
     */
    public static function getNameByAssoc(int $assocID, bool $block): string
    {
        $query = DB::query();
        $query
            ->select("roles.name, roles.id")
            ->from('#__thm_groups_role_associations AS ra')
            ->innerJoin('#__thm_groups_roles AS roles ON roles.id = ra.roleID')
            ->where("ra.id = '$assocID'");

        DB::set($query);

        $role = DB::array();

        // Role ID 1 is member which is implicitly true and therefore should not be explicitly stated
        $hideMemberRole = ($block and $role['id'] === Roles::MEMBER);

        return (empty($role['name']) or $hideMemberRole) ? '' : $role['name'];
    }
}
