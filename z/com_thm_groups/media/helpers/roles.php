<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

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
     * @return int the id of the association on success, otherwise 0
     * @throws Exception
     */
    public static function getAssocID($roleID, $resourceID, $resource)
    {
        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);

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

        $dbo->setQuery($query);

        try {
            $result = $dbo->loadResult();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return 0;
        }

        return empty($result) ? 0 : $result;
    }

    /**
     * Retrieves the name of the role by means of its association with a group.
     *
     * @param   int   $assocID  the id of the group -> role association
     * @param   bool  $block    whether redundant roles ('Mitglied') should be blocked
     *
     * @return  string the name of the role referenced in the association
     * @throws Exception
     */
    public static function getNameByAssoc($assocID, $block)
    {
        $dbo = JFactory::getDbo();

        $query = $dbo->getQuery(true);
        $query
            ->select("roles.name, roles.id")
            ->from('#__thm_groups_role_associations AS ra')
            ->innerJoin('#__thm_groups_roles AS roles ON roles.id = ra.roleID')
            ->where("ra.id = '$assocID'");

        $dbo->setQuery($query);

        try {
            $role = $dbo->loadAssoc();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return '';
        }

        // Role ID 1 is member which is implicitly true and therefore should not be explicitly stated
        $hideMemberRole = ($block and $role['id'] === MEMBER);

        return (empty($role['name']) or $hideMemberRole) ? '' : $role['name'];
    }
}
