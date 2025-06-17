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

use Joomla\CMS\Helper\UserGroupsHelper as UGH;
use stdClass;
use THM\Groups\Helpers\Groups as Helper;

class Group extends EditModel
{
    protected string $tableClass = 'Groups';

    /** @inheritdoc */
    public function getItem(): stdClass
    {
        if ($item = parent::getItem()) {
            $groupID          = $item->id;
            $group            = UGH::getInstance()->get($groupID);
            $item->title      = $group->title;
            $item->parent_id  = $group->parent_id;
            $levels           = Helper::levels($groupID);
            $item->viewLevels = array_keys($levels);
        }

        return $item;
    }
}