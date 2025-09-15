<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use Joomla\Database\DatabaseQuery;
use THM\Groups\Adapters\{Database as DB, Input};
use THM\Groups\Controllers\Contents as Controller;
use THM\Groups\Helpers\{Categories, Pages};

/**
 * THM_GroupsModelContent_Manager is a class which deals with the information preparation for the administrator view.
 */
class Contents extends ListModel
{
    protected string $defaultOrdering = 'user.alias, title';

    /** @inheritDoc */
    public function __construct($config = [])
    {
        parent::__construct($config);

        Controller::clean();
    }

    /** @inheritDoc */
    protected function getListQuery(): DatabaseQuery
    {
        $query = DB::query();

        $rootCategory = Categories::root();

        if (empty($rootCategory)) {
            return $query;
        }

        $select  = DB::qn([
            'content.id',
            'content.title',
            'content.state',
            'user.surnames',
            'user.forenames',
            'page.featured',
            'page.ordering'
        ]);
        $aliased = DB::qn(['category.id', 'category.parent_id'], ['categoryID', 'parentID']);
        // User management access is required to access the view
        $special = [DB::quote(1) . ' AS ' . DB::qn('access')];

        $query->select(array_merge($select, $aliased, $special))
            ->from(DB::qn('#__content', 'content'))
            ->innerJoin(DB::qn('#__categories', 'category'), DB::qc('category.id', 'content.catid'))
            ->innerJoin(DB::qn('#__users', 'user'), DB::qc('user.id', 'category.created_user_id'))
            ->innerJoin(DB::qn('#__groups_pages', 'page'), DB::qc('page.userID', 'content.created_by'))
            ->innerJoin(DB::qn('#__viewlevels', 'level'), DB::qc('level.id', 'content.access'))
            ->where(DB::qc('category.parent_id', $rootCategory))
            ->group(DB::qn('content.id'));

        if ($search = $this->state->get('filter.search')) {
            $query->where("(content.title LIKE '%" . implode("%' OR content.title LIKE '%", explode(' ', $search)) . "%')");
        }

        // Category and user are semantically identical
        if ($userID = (int) $this->state->get('filter.userID')) {
            $query->where(DB::qc('user.id', $userID));
        }

        $featured = $this->state->get('filter.featured');
        if (is_numeric($featured) and in_array((int) $featured, Input::BINARY)) {
            $featured = (int) $featured;
            $query->where(DB::qc('page.featured', $featured));
        }

        $status = $this->state->get('filter.state');
        if (is_numeric($status) and in_array((int) $status, array_keys(Pages::STATES))) {
            $status = (int) $status;
            $query->where(DB::qc('content.state', $status));
        }

        $this->orderBy($query);

        return $query;
    }
}
