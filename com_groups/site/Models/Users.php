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

use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;
use stdClass;
use THM\Groups\Adapters\Application;
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
        $db     = $this->getDatabase();
        $groups = [];
        $query  = $db->getQuery(true);
        $tag    = Application::tag();

        $jCondition1 = $db->quoteName('map.group_id') . ' = ' . $db->quoteName('g.id');
        $jCondition2 = $db->quoteName('ra.mapID') . ' = ' . $db->quoteName('map.id');
        $jCondition3 = $db->quoteName('r.id') . ' = ' . $db->quoteName('ra.roleID');

        $select = [
            $db->quoteName('g.id', 'groupID'),
            $db->quoteName("g.name_$tag", 'group'),
            $db->quoteName('r.id', 'roleID'),
            $db->quoteName("r.name_$tag", 'role'),
            $db->quoteName('map.user_id', 'userID')
        ];
        $query->select($select)
            ->from($db->quoteName('#__groups_groups', 'g'))
            ->join('inner', $db->quoteName('#__user_usergroup_map', 'map'), $jCondition1)
            ->join('left', $db->quoteName('#__groups_role_associations', 'ra'), $jCondition2)
            ->join('left', $db->quoteName('#__groups_roles', 'r'), $jCondition3)
            ->where($db->quoteName('map.user_id') . " = $itemID");

        $db->setQuery($query);

        foreach ($db->loadAssocList() as $result) {
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
            $item->editLink  = Route::_('index.php?option=com_groups&view=Profile&id=' . $item->id);
            $item->groups    = $this->getAssocs($item->id);
        }

        return $items;
    }

    /** @inheritDoc */
    protected function getListQuery(): QueryInterface
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select([
            $db->quoteName('u') . '.*',
            'COALESCE(' . $db->quoteName('surnames') . ', ' . $db->quoteName('name') . ') AS ' . $db->quoteName('surnames')
        ]);

        $userID = $db->quoteName('u.id');

        $query->from($db->quoteName('#__users', 'u'));

        $activation = $this->state->get('filter.activation');

        if ($this->isBinary($activation)) {
            $column = $db->quoteName('activation');

            if ((int) $activation) {
                $query->where("($column = '' OR $column = '0')");
            }
            else {
                $query->where("$column != '0'")
                    ->where($query->length($column) . ' > 0');
            }
        }

        if ($association = $this->state->get('filter.association')) {
            [$resource, $id] = explode('-', $association);

            switch ($resource) {
                case 'assocID':
                    if (!empty($id) and $id = (int) $id) {
                        $assocID     = $db->quoteName('pa.assocID');
                        $paProfileID = $db->quoteName('pa.userID');

                        $condition = "$paProfileID = $userID";
                        $query->join('inner', $db->quoteName('#__groups_person_associations', 'pa'), $condition)
                            ->where("$assocID = $id");
                    }
                    break;
                case 'groupID':
                    if (!empty($id) and $id = (int) $id) {
                        $groupID   = $db->quoteName('uugm.group_id');
                        $uIDColumn = $db->quoteName('uugm.user_id');

                        $condition = "$uIDColumn = $userID";
                        $query->join('inner', $db->quoteName('#__user_usergroup_map', 'uugm'), $condition)
                            ->where("$groupID = $id");
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

            $assocID     = $db->quoteName('pa.assocID');
            $paProfileID = $db->quoteName('pa.userID');
            $raID        = $db->quoteName('ra.id');

            $condition1 = "$paProfileID = $userID";
            $condition2 = "$raID = $assocID";
            $query->join($join, $db->quoteName('#__groups_profile_associations', 'pa'), $condition1)
                ->join($join, $db->quoteName('#__groups_role_associations', 'ra'), $condition2)
                ->where($db->quoteName('ra.id') . $predicate);
        }

        $this->binaryFilter($query, 'filter.block');
        $this->binaryFilter($query, 'filter.content');
        $this->binaryFilter($query, 'filter.editing');
        $this->binaryFilter($query, 'filter.published');

        //TODO re-add functional filter based exclusively on group assignment

        if ($search = $this->getState('filter.search')) {
            if (is_numeric($search)) {
                $query->where($db->quoteName('u.id') . ' = :id')
                    ->bind(':id', $search, ParameterType::INTEGER);
            }
            else {
                $search  = '%' . trim($search) . '%';
                $wherray = [
                    $db->quoteName('email') . ' LIKE :email',
                    $db->quoteName('name') . ' LIKE :name',
                    $db->quoteName('username') . ' LIKE :username',
                ];
                $query->where('(' . implode(' OR ', $wherray) . ')')
                    ->bind(':email', $search)
                    ->bind(':name', $search)
                    ->bind(':username', $search);
            }
        }

        $registered = $this->state->get('filter.registered');
        $visited    = $this->state->get('filter.visited');

        if ($registered or $visited) {
            $now        = date('\'Y-m-d H:i:s\'');
            $today      = date('\'Y-m-d 00:00:00\'');
            $weekAgo    = date('\'Y-m-d 00:00:00\'', strtotime('-1 week'));
            $monthsAgo1 = date('\'Y-m-d 00:00:00\'', strtotime('-1 month'));
            $monthsAgo3 = date('\'Y-m-d 00:00:00\'', strtotime('-3 month'));
            $monthsAgo6 = date('\'Y-m-d 00:00:00\'', strtotime('-6 month'));
            $yearAgo    = date('\'Y-m-d 00:00:00\'', strtotime('-1 year'));

            if ($registered) {
                $rColumn = $db->quoteName('registerDate');
                switch ($registered) {
                    case 'today':
                        $query->where("$rColumn BETWEEN $today AND $now");
                        break;
                    case 'past_week':
                        $query->where("$rColumn BETWEEN $weekAgo AND $now");
                        break;
                    case 'past_1month':
                        $query->where("$rColumn BETWEEN $monthsAgo1 AND $now");
                        break;
                    case 'past_3month':
                        $query->where("$rColumn BETWEEN $monthsAgo3 AND $now");
                        break;
                    case 'past_6month':
                        $query->where("$rColumn BETWEEN $monthsAgo6 AND $now");
                        break;
                    case 'past_year':
                        $query->where("$rColumn BETWEEN $yearAgo AND $now");
                        break;
                    case 'post_year':
                        $query->where("$rColumn < $yearAgo");
                        break;
                }
            }

            if ($visited) {
                $vColumn = $db->quoteName('lastvisitDate');
                switch ($visited) {
                    case 'today':
                        echo 'check?';
                        $query->where("$vColumn BETWEEN $today AND $now");
                        break;
                    case 'past_week':
                        $query->where("$vColumn BETWEEN $weekAgo AND $now");
                        break;
                    case 'past_1month':
                        $query->where("$vColumn BETWEEN $monthsAgo1 AND $now");
                        break;
                    case 'past_3month':
                        $query->where("$vColumn BETWEEN $monthsAgo3 AND $now");
                        break;
                    case 'past_6month':
                        $query->where("$vColumn BETWEEN $monthsAgo6 AND $now");
                        break;
                    case 'past_year':
                        $query->where("$vColumn BETWEEN $yearAgo AND $now");
                        break;
                    case 'post_year':
                        $query->where("$vColumn < $yearAgo");
                        break;
                    case 'never':
                        $query->where("$vColumn IS NULL");
                        break;
                }
            }
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
}