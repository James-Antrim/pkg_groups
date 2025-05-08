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

use Joomla\CMS\{Language\Text, Router\Route};
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\{Application, Database as DB};
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available roles data.
 */
class Roles extends ListModel
{
    use Ordered;

    protected string $defaultOrdering = 'ordering';

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'assigned',
                'groupID',
            ];
        }

        parent::__construct($config, $factory);
    }

    /** @inheritDoc */
    public function getItems(): array
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            // Management access is a prerequisite of accessing this view at all.
            $item->access   = true;
            $item->editLink = Route::_('index.php?option=com_groups&view=role&id=' . $item->id);

            if ($item->groups === 0) {
                $item->groups = Text::_('GROUPS_NO_GROUPS');
            }
            elseif ($item->groups === 1) {
                $item->groups = $item->group;
            }
            else {
                //TODO: link to groups view with role filter set to this one
                $item->groups = $item->group;
            }
        }

        return $items;
    }

    /** @inheritDoc */
    protected function getListQuery(): QueryInterface
    {
        $groups = 'COUNT(' . implode(') AS ', explode(' AS ', DB::qn('g.id', 'groups')));
        $tag    = Application::tag();

        $select = [
            DB::qn('r.id'),
            DB::qn('r.ordering'),
            DB::qn("r.name_$tag", 'name'),
            DB::qn("r.plural_$tag", 'plural'),
            DB::qn("g.name_$tag", 'group'),
            $groups
        ];
        $query  = DB::query();
        $query->select($select)->from(DB::qn('#__groups_roles', 'r'))->group(DB::qn('r.id'));

        $aConditions = DB::qc('ra.roleID', 'r.id');
        $aTable      = DB::qn('#__groups_role_associations', 'ra');
        $groupID     = $this->getState('filter.groupID');
        $gConditions = DB::qc('g.id', 'uugm.group_id');
        $gTable      = DB::qn('#__groups_groups', 'g');
        $mConditions = DB::qn('uugm.id', 'ra.mapID');
        $mTable      = DB::qn('#__user_usergroup_map', 'uugm');

        if (is_numeric($groupID) and intval($groupID) > 0) {
            $query->innerJoin($aTable, $aConditions)
                ->innerJoin($mTable, $mConditions)
                ->innerJoin($gTable, $gConditions)
                ->where(DB::qc('g.id', (int) $groupID));
        }
        else {
            $query->leftJoin($aTable, $aConditions)
                ->leftJoin($mTable, $mConditions)
                ->leftJoin($gTable, $gConditions);

            if (is_numeric($groupID) and intval($groupID) < 0) {
                $query->where(DB::qn('g.id') . ' IS NULL');
            }
        }

        $this->orderBy($query);

        return $query;
    }
}