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
use THM\Groups\Adapters\Input;

/**
 * Model class for aggregating available roles data.
 */
class TemplateAttributes extends ListModel
{
    protected string $defaultOrdering = 'ordering';

    /**
     * @inheritDoc
     */
    public function getItems(): array
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            $item->access = true;
        }

        return $items;
    }

    /**
     * Build an SQL query to load the list data.
     * @return  QueryInterface
     */
    protected function getListQuery(): QueryInterface
    {
        $db         = $this->getDatabase();
        $query      = $db->getQuery(true);
        $templateID = Input::getID();

        $query->select('*')
            ->from($db->quoteName('#__groups_template_attributes'))
            ->where($db->quoteName('templateID') . " = $templateID");
        $this->orderBy($query);

        return $query;
    }
}