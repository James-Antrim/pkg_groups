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

use Joomla\CMS\{Form\Form, Router\Route};
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\{DatabaseQuery, QueryInterface};
use stdClass;
use THM\Groups\Adapters\{Application, Database as DB};
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available attribute types data.
 */
class Users extends ListModel
{
    protected string $defaultOrdering = 'surnames, forenames';

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'activation',
                'association',
                'block',
                'content',
                'editing',
                'functional',
                'published',
                'registered',
                'roleID',
                'visited'
            ];
        }

        parent::__construct($config, $factory);
    }

    /** @inheritDoc */
    public function filterFilterForm(Form $form): void
    {
        if ($this->state->get('filter.association')) {
            $form->removeField('roleID', 'filter');
            unset($this->filter_fields['roleID']);
        }
        elseif ($this->state->get('filter.roleID')) {
            $form->removeField('association', 'filter');
            unset($this->filter_fields['association']);
        }
    }

    /**
     * Gets the groups & roles associated with a given person's id
     *
     * @param   int  $itemID
     *
     * @return array
     */
    private function getAssocs(int $itemID): array
    {
        $groups = [];
        $query  = DB::query();
        $tag    = Application::tag();

        $query->select(DB::qn(
            ["g.name_$tag", 'g.id', "r.name_$tag", 'r.id', 'map.user_id'],
            ['group', 'groupID', 'role', 'roleID', 'userID']
        ))
            ->from(DB::qn('#__groups_groups', 'g'))
            ->innerJoin(DB::qn('#__user_usergroup_map', 'map'), DB::qc('map.group_id', 'g.id'))
            ->leftJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.mapID', 'map.id'))
            ->leftJoin(DB::qn('#__groups_roles', 'r'), DB::qc('r.id', 'ra.roleID'))
            ->where(DB::qc('map.user_id', $itemID));

        DB::set($query);

        foreach (DB::arrays() as $result) {
            $group   = $result['group'];
            $groupID = $result['groupID'];
            $role    = $result['role'];
            $roleID  = $result['roleID'];

            if (empty($groups[$groupID])) {
                $groups[$groupID] = ['name' => $group, 'roles' => []];
            }

            if ($roleID and empty($groups[$groupID]['roles'][$roleID])) {
                $groups[$groupID]['roles'][$roleID] = $role;
            }
        }

        return $groups;
    }

    /** @inheritDoc */
    public function getItems(): array
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            // Management access is a prerequisite of accessing this view at all.
            $item->access    = true;
            $item->activated = empty($item->activation);
            $item->editLink  = Route::_('index.php?option=com_groups&view=profile&id=' . $item->id);
            $item->groups    = $this->getAssocs($item->id);
        }

        return $items;
    }

    /** @inheritDoc */
    protected function getListQuery(): QueryInterface
    {
        $query = DB::query();

        $query->select([
            DB::qn('u') . '.*',
            'COALESCE(' . DB::qn('surnames') . ', ' . DB::qn('name') . ') AS ' . DB::qn('surnames')
        ])->from(DB::qn('#__users', 'u'));

        $activation = $this->state->get('filter.activation');

        if ($this->isBinary($activation)) {
            $column = DB::qn('activation');

            if ((int) $activation) {
                $restriction = '(' . DB::qcs([['activation', '', '=', true], ['activation', '0', '=', true]], 'OR') . ')';
                $query->where($restriction);
            }
            else {
                $query->where(DB::qc('activation', '0', '!=', true))->where($query->length($column) . ' > 0');
            }
        }

        $usersID = DB::qn('u.id');
        if ($association = $this->state->get('filter.association')) {
            [$resource, $id] = explode('-', $association);

            switch ($resource) {
                case 'assocID':
                    if (!empty($id) and $id = (int) $id) {
                        $query->innerJoin(DB::qn('#__groups_person_associations', 'pa'), DB::qc('pa.userID', $usersID))
                            ->where(DB::qc('pa.assocID', $id));
                    }
                    break;
                case 'groupID':
                    if (!empty($id) and $id = (int) $id) {
                        $query->innerJoin(DB::qn('#__user_usergroup_map', 'uugm'), DB::qc('uugm.user_id', $usersID))
                            ->where(DB::qc('uugm.group_id', $id));
                    }
                    break;
            }
        }
        elseif ($id = $this->state->get('filter.roleID') and is_numeric($id) and $id = (int) $id) {
            if ($id > 0) {
                $join      = 'inner';
                $predicate = " = $id";
            }
            else {
                $join      = 'left';
                $predicate = " IS NULL";
            }

            $query->join($join, DB::qn('#__groups_profile_associations', 'pa'), DB::qc('pa.userID', $usersID))
                ->join($join, DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.id', 'pa.assocID'))
                ->where(DB::qn('ra.id') . $predicate);
        }

        $this->binaryFilter($query, 'filter.block');
        $this->binaryFilter($query, 'filter.content');
        $this->binaryFilter($query, 'filter.editing');
        $this->binaryFilter($query, 'filter.published');

        //TODO re-add functional filter based exclusively on group assignment

        if ($search = $this->getState('filter.search')) {
            if (is_numeric($search)) {
                $query->where(DB::qc('u.id', (int) $search));
            }
            else {
                $search     = '%' . trim($search) . '%';
                $conditions = DB::qcs([
                    ['email', $search, 'LIKE', true],
                    ['name', $search, 'LIKE', true],
                    ['username', $search, 'LIKE', true]
                ], 'OR');
                $conditions = "($conditions)";
                $query->where($conditions);
            }
        }

        if ($registered = $this->state->get('filter.registered')) {
            $this->terminate($query, 'registerDate', $registered);
        }

        if ($visited = $this->state->get('filter.visited')) {
            $this->terminate($query, 'lastvisitDate', $visited);
        }

        $this->orderBy($query);

        return $query;
    }

    /**
     * Determines whether the page was called from as a link from the groups view.
     * @return bool
     */
    private function groupLinked(): bool
    {
        $groupID = (int) $this->state->get('filter.groupID');
        $state   = $this->state->get('filter.state');

        return $groupID and is_numeric($state);
    }

    /**
     * Method to get the data that should be injected in the form.
     * @return  stdClass   Should
     */
    protected function loadFormData(): stdClass
    {
        $data = parent::loadFormData();

        if ($this->groupLinked()) {
            // Replace external parameter names with internal filter names
            $data->filter = [
                'association' => $this->state->get('filter.association'),
                'block'       => (int) $this->state->get('filter.block')
            ];
        }

        return $data;
    }

    /** @inheritDoc */
    protected function populateState($ordering = null, $direction = null): void
    {
        parent::populateState($ordering, $direction);

        if ($this->groupLinked()) {
            $groupID = (int) $this->state->get('filter.groupID');
            $this->state->set('filter.association', "groupID-$groupID");

            // An active state is determined by a negative block attribute
            $state = (bool) $this->state->get('filter.state');
            $this->state->set('filter.block', !$state);
        }

        if ($this->state->get('filter.association')) {
            $this->state->set('filter.roleID', '');
        }
        elseif ($this->state->get('filter.roleID')) {
            $this->state->set('filter.association', '');
        }
    }

    /**
     * Adds a date based filter on a column based on a descriptive value string.
     *
     * @param   DatabaseQuery  $query   the query to add the filter to
     * @param   string         $column  the column to filter against
     * @param   string         $value   the descriptive value of the filter
     *
     * @return void
     */
    private function terminate(DatabaseQuery $query, string $column, string $value): void
    {
        $column = DB::qn($column);
        $now    = date('\'Y-m-d H:i:s\'');
        $then   = date('\'Y-m-d 00:00:00\'', strtotime('-1 year'));

        switch ($value) {
            case 'today':
                $then = date('\'Y-m-d 00:00:00\'');
                $query->where("$column BETWEEN $then AND $now");
                break;
            case 'past_week':
                $then = date('\'Y-m-d 00:00:00\'', strtotime('-1 week'));
                $query->where("$column BETWEEN $then AND $now");
                break;
            case 'past_1month':
                $then = date('\'Y-m-d 00:00:00\'', strtotime('-1 month'));
                $query->where("$column BETWEEN $then AND $now");
                break;
            case 'past_3month':
                $then = date('\'Y-m-d 00:00:00\'', strtotime('-3 month'));
                $query->where("$column BETWEEN $then AND $now");
                break;
            case 'past_6month':
                $then = date('\'Y-m-d 00:00:00\'', strtotime('-6 month'));
                $query->where("$column BETWEEN $then AND $now");
                break;
            case 'past_year':
                $query->where("$column BETWEEN $then AND $now");
                break;
            case 'post_year':
                $query->where("$column < $then");
                break;
            case 'never':
                $query->where("$column IS NULL");
                break;
        }
    }
}