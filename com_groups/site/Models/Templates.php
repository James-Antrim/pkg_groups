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

use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\{Application, Database as DB};
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available roles data.
 */
class Templates extends ListModel
{
    protected string $defaultOrdering = 'name';

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [];
        }

        parent::__construct($config, $factory);
    }

    /** @inheritDoc */
    public function getItems(): array
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            // Management access is a prerequisite of accessing this view at all.
            $item->access     = true;
            $item->editLink   = Route::_('index.php?option=com_groups&view=template&id=' . $item->id);
            $item->attributes = Route::_('index.php?option=com_groups&view=template-attributes&id=' . $item->id);
        }

        return $items;
    }

    /** @inheritDoc */
    protected function getListQuery(): QueryInterface
    {
        $query = DB::query();
        $tag   = Application::tag();
        $query->select(['*', DB::qn("name_$tag", 'name')])->from(DB::qn('#__groups_templates'));
        $this->orderBy($query);

        return $query;
    }
}