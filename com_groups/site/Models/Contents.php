<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use Joomla\Database\DatabaseQuery;
use THM\Groups\Adapters\Database as DB;
use THM\Groups\Controllers\Contents as Controller;
use THM\Groups\Helpers\Categories;
use THM\Groups\Tools\Migration;

/**
 * THM_GroupsModelContent_Manager is a class which deals with the information preparation for the administrator view.
 */
class Contents extends ListModel
{
    use Contented;

    protected string $defaultOrdering = 'user.surnames, user.forenames';

    /** @inheritDoc */
    public function __construct($config = [])
    {
        Migration::migrate();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = ['featured', 'language', 'level', 'state', 'userID'];
        }

        parent::__construct($config);

        Controller::clean();
    }

    /** @inheritDoc */
    protected function getListQuery(): DatabaseQuery
    {
        if (empty(Categories::root())) {
            return DB::query();
        }

        $query = $this->query();

        // Category <=> User
        if ($userID = $this->state->get('filter.userID') and is_numeric($userID)) {
            $userID = (int) $userID;
            $query->where(DB::qc('user.id', $userID));
        }

        return $query;
    }


    /** @inheritDoc */
    protected function populateState($ordering = null, $direction = null): void
    {
        parent::populateState($ordering, $direction);

        $ordering = $this->state->get('list.ordering');
        $userID   = $this->state->get('filter.userID');

        if ($ordering === $this->defaultOrdering and $userID) {
            $this->state->set('list.fullordering', 'ordering ASC');
            $this->state->set('list.ordering', 'ordering');
            $this->state->set('list.direction', 'ASC');
        }
        elseif ($ordering === 'ordering' and !$userID) {
            $this->state->set('list.fullordering', "$this->defaultOrdering ASC");
            $this->state->set('list.ordering', $this->defaultOrdering);
            $this->state->set('list.direction', 'ASC');
        }
    }
}
