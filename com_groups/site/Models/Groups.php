<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use Joomla\CMS\Helper\UserGroupsHelper as UGH;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;
use stdClass;
use THM\Groups\Adapters\{Application, Database as DB};
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available groups data.
 */
class Groups extends ListModel
{
    protected string $defaultOrdering = 'ug.lft';

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = ['levelID', 'roleID'];
        }

        parent::__construct($config, $factory);
    }

    /** @inheritDoc */
    public function getItems(): array
    {
        $items    = parent::getItems() ?: [];
        $ugHelper = UGH::getInstance();

        foreach ($items as $item) {
            $item->editLink = Route::_('?option=com_groups&view=group&id=' . $item->id);
            $ugHelper->populateGroupData($item);
            $this->getUserCounts($item);
        }


        return $items;
    }

    /** @inheritDoc */
    protected function getListQuery(): QueryInterface
    {
        $select = ['DISTINCT ' . DB::qn('g.id', 'id'), DB::qn('g.name_' . Application::tag(), 'name'), DB::qn('ug.parent_id')];
        $query  = DB::query();
        $query->select($select)
            ->from(DB::qn('#__groups_groups', 'g'))
            ->innerJoin(DB::qn('#__usergroups', 'ug'), DB::qc('ug.id', 'g.id'))
            ->leftJoin(
                DB::qn('#__usergroups', 'p'),
                DB::qn('p.lft') . '<' . DB::qn('ug.lft') . ' AND ' . DB::qn('ug.rgt') . '<' . DB::qn('p.rgt')
            );

        if ($filterRoleID = (int) $this->getState('filter.roleID')) {
            $condition = DB::qc('ra.groupID', 'g.id');
            $ra        = DB::qn('#__groups_role_associations', 'ra');

            if ($filterRoleID >= 1) {
                $query->innerJoin($ra, $condition)->where(DB::qc('ra.roleID', $filterRoleID));
            }
            else {
                $query->leftJoin($ra, $condition)->where(DB::qn('ra.roleID') . ' IS NULL');
            }
        }

        if ($filterLevelID = (int) $this->getState('filter.levelID')) {
            $regex1 = $query->concatenate(["'[,\\\\[]'", DB::qn('g.id'), "'[,\\\\]]'"]);
            $regex2 = $query->concatenate(["'[,\\\\[]'", DB::qn('p.id'), "'[,\\\\]]'"]);
            $rules  = DB::qn('vl.rules');
            $query->innerJoin(DB::qn('#__viewlevels', 'vl'), "$rules REGEXP $regex1 OR $rules REGEXP $regex2")
                ->where(DB::qc('vl.id', $filterLevelID));
        }

        // Filter the comments over the search string if set.
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where(DB::qc('g.id', (int) substr($search, 3)));
            }
            else {
                $search     = '%' . trim($search) . '%';
                $conditions = DB::qcs([['g.name_de', $search, 'LIKE', true], ['g.name_en', $search, 'LIKE', true]], 'OR');
                $conditions = "($conditions)";
                $query->where($conditions);
            }
        }

        // Add the list ordering clause.
        $this->orderBy($query);

        return $query;
    }

    /**
     * Adds associated user counts to the group
     *
     * @param   stdClass  $group  the group to set values for
     *
     * @return void
     */
    private function getUserCounts(stdClass $group): void
    {
        $query = DB::query();
        $query->select('COUNT(DISTINCT ' . DB::qn('map.user_id') . ')')
            ->from(DB::qn('#__user_usergroup_map', 'map'))
            ->leftJoin(DB::qn('#__users', 'u'), DB::qc('u.id', 'map.user_id'))
            ->where(DB::qc('map.group_id', $group->id))
            ->where(DB::qc('u.block', ':blocked'))
            ->group(DB::qn('map.group_id'))
            ->bind(':blocked', $blocked, ParameterType::INTEGER);
        DB::set($query);

        // All group users
        $blocked        = 0;
        $group->enabled = DB::integer();

        // Blocked group users
        $blocked        = 1;
        $group->blocked = DB::integer();
    }

    /** @inheritDoc */
    protected function populateState($ordering = 'ug.lft', $direction = 'asc'): void
    {
        // Load the parameters.
        $params = Application::parameters('com_users')->merge(Application::parameters());
        $this->setState('params', $params);

        // List state information.
        parent::populateState($ordering, $direction);
    }
}