<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use THM\Groups\Helpers\Groups;
use THM\Groups\Tables\RoleAssociations;
use THM\Groups\Tables\UserUsergroupMap as UUGM;

trait Associated
{
    /**
     * Associates / removes associations of the given user id to the given groups & roles.
     *
     * @param   int  $userID   the id of the user to (dis-) associate
     * @param   int  $action   the action to be performed on the user
     * @param   int  $groupID  the id of the group to be (dis-) associated
     * @param   int  $roleID   the id of the role to be (dis-) associated
     *
     * @return bool
     */
    protected function associate(int $userID, int $action, int $groupID, int $roleID): bool
    {
        $mapData = ['group_id' => $groupID, 'user_id' => $userID];
        $map     = new UUGM();
        $map->load($mapData);

        if ($action === Groups::REMOVE) {
            // Mapping doesn't exist
            if (!$map->id) {
                return false;
            }

            // Delete the mapping
            if (!$roleID) {
                return $map->delete();
            }

            $assoc     = new RoleAssociations();
            $assocData = ['mapID' => $map->id, 'roleID' => $roleID];

            // Association doesn't exist
            if (!$assoc->load($assocData)) {
                return false;
            }

            // Delete the association
            return $assoc->delete();
        }

        if (!$map->id and !$map->save($mapData)) {
            // Doesn't exist and couldn't be created
            return false;
        }

        // No role requested or the role requested would be in a joomla standard group which themselves are site roles.
        if (!$roleID or in_array($groupID, Groups::STANDARD_GROUPS)) {
            return true;
        }

        $assoc     = new RoleAssociations();
        $assocData = ['mapID' => $map->id, 'roleID' => $roleID];

        return ($assoc->load($assocData) or $assoc->save($assocData));
    }
}