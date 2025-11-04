<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\Field\ListField;
use THM\Groups\Adapters\{Database as DB, User as UAdapter};
use THM\Groups\Helpers\{Can, Categories as Helper, Users as UHelper};

/** @inheritDoc */
class Categories extends ListField
{
    /** @inheritDoc */
    protected function getOptions(): array
    {
        $default = parent::getOptions();
        if (!$rootID = Helper::root()) {
            return $default;
        }

        $query = DB::query();
        $query->select(['DISTINCT ' . DB::qn('id', 'value'), DB::qn('title', 'text')])
            ->from(DB::qn('#__categories'))
            ->where(DB::qc('parent_id', $rootID))
            ->order(DB::qn('text'));

        if (!Can::manage('content')) {
            if (!$pCategoryID = UHelper::categoryID(UAdapter::id())) {
                // Can't manage and is not assigned a category
                return $default;
            }

            $query->where(DB::qc('id', $pCategoryID));
            DB::set($query);

            // There will be only one, but list field expects an array
            return DB::objects();
        }

        DB::set($query);
        return array_merge($default, DB::objects());
    }
}