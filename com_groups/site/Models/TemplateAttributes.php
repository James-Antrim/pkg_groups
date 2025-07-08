<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\{Database as DB, Input};

/**
 * Model class for aggregating available roles data.
 */
class TemplateAttributes extends ListModel
{
    protected string $defaultOrdering = 'ordering';

    /** @inheritDoc */
    public function getItems(): array
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            $item->access = true;
        }

        return $items;
    }

    /** @inheritDoc */
    protected function getListQuery(): QueryInterface
    {
        $query      = DB::query();
        $templateID = Input::id();

        $query->select('*')
            ->from(DB::qn('#__groups_template_attributes'))
            ->where(DB::qc('templateID', $templateID));
        $this->orderBy($query);

        return $query;
    }
}