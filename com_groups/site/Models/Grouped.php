<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use Joomla\CMS\Helper\UserGroupsHelper as UGHelper;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Groups;

trait Grouped
{
    protected array $groups = [];

    /**
     * Sets the groups relevant to the output of profiles.
     * @return void
     */
    protected function groups(): void
    {
        $params   = Input::parameters();
        $ugHelper = UGHelper::getInstance();
        if (!$parent = $ugHelper->get($params->get('groupID'))) {
            return;
        }

        $subs = $params->get('subgroups');
        $subs = in_array($subs, Input::BINARY) ? $subs : Input::YES;
        if ($subs === Input::NO) {
            $this->groups[$parent->id] = $parent;
            return;
        }

        foreach ($ugHelper->getAll() as $groupID => $group) {
            if (in_array($groupID, Groups::STANDARD_GROUPS)) {
                continue;
            }

            if ($group->lft >= $parent->lft and $group->rgt <= $parent->rgt) {
                $this->groups[$group->id] = $group;
            }
        }

        uasort($this->groups, function ($groupOne, $groupTwo) {
            // First group is antecedent
            if ($groupTwo->lft > $groupOne->rgt) {
                return 1;
            }

            // Second group is antecedent
            if ($groupOne->lft > $groupTwo->rgt) {
                return -1;
            }

            // First group is nested
            if ($groupOne->lft > $groupTwo->lft and $groupOne->rgt < $groupTwo->rgt) {
                return 1;
            }

            // Second group is nested
            if ($groupTwo->lft > $groupOne->lft and $groupTwo->rgt < $groupOne->rgt) {
                return 1;
            }

            // This should not be able to take place due to the nested table structure
            return 0;
        });
    }
}