<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use THM\Groups\Adapters\Database as DB;
use THM\Groups\Helpers\{Categories as Helper, Groups, Profiles, Users as UHelper};
use THM\Groups\Tables\{Categories as Table};

/** @inheritDoc */
class Category extends Controller
{
    /**
     * Creates a category based on user table data.
     *
     * @param   int  $userID
     *
     * @return int
     */
    public function create(int $userID): int
    {
        if (!$rootID = Helper::root()) {
            return 0;
        }

        $root = new Table();

        if (!$root->load($rootID) or !$alias = UHelper::alias($userID)) {
            return 0;
        }

        $category                  = new Table();
        $category->access          = Groups::PUBLIC;
        $category->alias           = $alias;
        $category->created_user_id = $userID;
        $category->extension       = 'com_content';
        $category->language        = '*';
        $category->path            = "$root->path/$alias";
        $category->published       = Helper::PUBLISHED;
        $category->title           = Profiles::name($userID);
        $category->setLocation($rootID, 'last-child');

        if (!$category->store() or !$categoryID = $category->id) {
            return 0;
        }

        // @todo test if this is still necessary
        $query = DB::query();
        $query->update(DB::qn('#__categories'))->set(DB::qc('created_user_id', $userID))->where(DB::qc('id', $categoryID));
        DB::set($query);
        return $categoryID;
    }
}