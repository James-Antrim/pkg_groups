<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use THM\Groups\Helpers\RoleAssociations;

class User extends EditModel
{
    protected string $tableClass = 'Users';

    /** @inheritDoc */
    public function getItem(): object
    {
        $item   = parent::getItem();
        $userID = $item->id;

        if ($item->groups and $groups = (array) $item->groups) {
            foreach (array_keys($groups) as $groupID) {
                $groups[$groupID] = RoleAssociations::byGroupID($groupID, $userID);
            }
            $item->groups = $groups;
        }
        $this->item = $item;
        return $item;
    }
}