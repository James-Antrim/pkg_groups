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

use Joomla\Database\DatabaseQuery;
use THM\Groups\Adapters\{Database as DB, Input};
use THM\Groups\Helpers\{Categories, Pages};

/**
 * Class standardizes the getName function across classes.
 */
trait Contented
{
    /**
     * Gets the base query common to content related lists.
     * @return DatabaseQuery
     */
    public function query(): DatabaseQuery
    {
        $query        = DB::query();
        $rootCategory = Categories::root();

        $select  = DB::qn([
            'content.checked_out',
            'content.id',
            'content.hits',
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

        $this->orderBy($query);

        return $query;
    }
}