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
use THM\Groups\Adapters\{Database as DB, Input, User};
use THM\Groups\Helpers\Categories;

/**
 * Class retrieves information about content for the profile's content category
 */
class Pages extends ListModel
{
    use Contented;

    protected string $defaultOrdering = 'ordering';

    /** @inheritDoc */
    protected function getListQuery(): DatabaseQuery
    {
        if (empty(Categories::root())) {
            return DB::query();
        }

        $query = $this->query();

        $query->where(DB::qc('user.id', Input::integer('profileID', User::id())));

        return $query;
    }
}
