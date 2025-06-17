<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

require_once 'roles.php';

/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperGroups
{
    /**
     * Associates a role with a given group, ignoring existing associations with the given role
     *
     * @param   int    $roleID   the id of the role to be associated
     * @param   array  $groupID  the group with which the role ist to be associated
     *
     * @return bool true on success, otherwise false
     * @throws Exception
     */
    public static function associateRole($roleID, $groupID)
    {
        // Standard groups are excluded.
        if ($groupID < 9) {
            return 0;
        }

        if ($existingID = THM_GroupsHelperRoles::getAssocID($roleID, $groupID, 'group')) {
            return $existingID;
        }

        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $query->insert('#__thm_groups_role_associations')->columns(['groupID', 'roleID'])->values("$groupID, $roleID");
        $dbo->setQuery($query);

        try {
            $dbo->execute();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return 0;
        }

        return THM_GroupsHelperRoles::getAssocID($roleID, $groupID, 'group');
    }

    /**
     * Gets the name of the usergroup requested
     *
     * @param   int  $groupID  the id of the usergroup
     *
     * @return string the group's title on success, otherwise empty
     * @throws Exception
     */
    public static function getName($groupID)
    {
        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $query->select('title')->from('#__usergroups')->where("id = '$groupID'");
        $dbo->setQuery($query);

        try {
            $title = $dbo->loadResult();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return '';
        }

        return empty($title) ? '' : $title;
    }

    /**
     * Gets the number of profiles associated with a given group
     *
     * @param   int  $groupID  GroupID
     *
     * @return  int  the number of profiles associated with the group
     * @throws Exception
     */
    public static function getProfileCount($groupID)
    {
        $dbo   = JFactory::getDBO();
        $query = $dbo->getQuery(true);

        $query->select("COUNT(DISTINCT pa.profileID) AS total");
        $query->from("`#__thm_groups_role_associations` AS ra");
        $query->innerJoin("`#__thm_groups_profile_associations` AS pa ON ra.id = pa.role_associationID");
        $query->innerJoin("`#__thm_groups_profiles` AS p ON p.id = pa.profileID");
        $query->where("p.published = 1");
        $query->where("ra.groupID = '$groupID'");
        $dbo->setQuery($query);

        try {
            return $dbo->loadResult();
        }
        catch (Exception $exc) {
            JFactory::getApplication()->enqueueMessage($exc->getMessage(), 'error');

            return 0;
        }
    }
}
