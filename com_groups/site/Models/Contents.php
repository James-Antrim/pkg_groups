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

use Joomla\{Database\DatabaseQuery, Registry\Registry};
use THM\Groups\Adapters\{Database as DB, Input};
use THM\Groups\Controllers\Contents as Controller;
use THM\Groups\Helpers\{Categories, Pages};
use THM\Groups\Tools\Migration;

/**
 * THM_GroupsModelContent_Manager is a class which deals with the information preparation for the administrator view.
 */
class Contents extends ListModel
{
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
    public function getItems(): array
    {
        $items = parent::getItems();
        foreach ($items as $item) {
            if (isset($item->metadata)) {
                $registry       = new Registry($item->metadata);
                $item->metadata = $registry->toArray();
            }
        }
        return $items;
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
            'content.checked_out',
            'content.id',
            'content.language',
            'content.title',
            'content.state',
            'user.surnames',
            'user.forenames',
            'page.featured',
            'page.ordering'
        ]);
        $aliased = DB::qn(
            ['category.id', 'category.parent_id', 'language.image', 'language.title', 'level.title'],
            ['categoryID', 'parentID', 'language_image', 'language_title', 'level']
        );
        $url     = 'index.php?option=com_groups&view=content&id=';
        $special = [
            // Content management access is required to access the view
            DB::quote(1) . ' AS ' . DB::qn('access'),
            $query->concatenate([DB::quote($url), DB::qn('content.id')], '') . ' AS ' . DB::qn('url')
        ];

        $query->select(array_merge($select, $aliased, $special))
            ->from(DB::qn('#__content', 'content'))
            ->innerJoin(DB::qn('#__categories', 'category'), DB::qc('category.id', 'content.catid'))
            ->innerJoin(DB::qn('#__users', 'user'), DB::qc('user.id', 'category.created_user_id'))
            ->innerJoin(
                DB::qn('#__groups_pages', 'page'),
                DB::qcs([['page.userID', 'content.created_by'], ['page.contentID', 'content.id']])
            )
            ->innerJoin(DB::qn('#__viewlevels', 'level'), DB::qc('level.id', 'content.access'))
            ->leftJoin(DB::qn('#__languages', 'language'), DB::qc('language.lang_code', 'content.language'))
            ->where(DB::qc('category.parent_id', $rootCategory))
            ->group(DB::qn('content.id'));

        if ($search = $this->state->get('filter.search')) {
            $query->where("(content.title LIKE '%" . implode("%' OR content.title LIKE '%", explode(' ', $search)) . "%')");
        }

        $featured = $this->state->get('filter.featured');
        if (is_numeric($featured) and in_array((int) $featured, Input::BINARY)) {
            $featured = (int) $featured;
            $query->where(DB::qc('page.featured', $featured));
        }

        $language = $this->state->get('filter.language');
        if ($language and preg_match('/^(\*|[a-z]{2}-[A-Z]{2})$/', $language)) {
            $query->where(DB::qc('content.language', $language, '=', true));
        }

        if ($levelID = $this->state->get('filter.levelID') and is_numeric($levelID)) {
            $levelID = (int) $levelID;
            $query->where(DB::qc('content.access', $levelID));
        }

        $status = $this->state->get('filter.state');
        if (is_numeric($status) and in_array((int) $status, array_keys(Pages::STATES))) {
            $status = (int) $status;
            $query->where(DB::qc('content.state', $status));
        }

        // Category <=> User
        if ($userID = $this->state->get('filter.userID') and is_numeric($userID)) {
            $userID = (int) $userID;
            $query->where(DB::qc('user.id', $userID));
        }

        $this->orderBy($query);

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
