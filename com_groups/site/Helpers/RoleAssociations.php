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

use Joomla\Database\ParameterType;
use THM\Groups\Adapters\Application;

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
        $db        = Application::database();
        $condition = $db->quoteName('m.id') . ' = ' . $db->quoteName('ra.mapID');

        $query = $db->getQuery(true);
        $query->select($db->quoteName('ra') . '.*')
            ->from($db->quoteName('#__groups_role_associations', 'ra'))
            ->join('inner', $db->quoteName('#__user_usergroup_map', 'm'), $condition)
            ->where($db->quoteName('m.group_id') . " = $groupID");
        $db->setQuery($query);

        return $db->loadAssocList('id', 'roleID');
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
        $db        = Application::database();
        $query     = $db->getQuery(true);
        $ras       = $db->quoteName('#__groups_role_associations');
        $rIDColumn = $db->quoteName('roleID');
        $query->select('*')->from($ras)->where("$rIDColumn = :roleID")->bind(':roleID', $roleID, ParameterType::INTEGER);
        $db->setQuery($query);

        return $db->loadAssocList('id', 'groupID');
    }
}