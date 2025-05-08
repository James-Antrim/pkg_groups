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

use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\{Application, Database as DB, Text};
use THM\Groups\Helpers\{Attributes as Helper, Types};
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available attribute types data.
 */
class Attributes extends ListModel
{
    use Ordered;

    protected string $defaultOrdering = 'ordering';

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'assigned',
                'typeID',
                'viewLevelID',
            ];
        }

        parent::__construct($config, $factory);
    }

    /** @inheritDoc */
    public function getItems(): array
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            // Management access is a prerequisite of accessing this view at all.
            $item->access   = true;
            $item->editLink = Route::_('index.php?option=com_groups&view=attribute&id=' . $item->id);

            $type         = Types::TYPES[$item->typeID];
            $item->input  = Text::_($type['input']);
            $item->output = Text::_($type['output']);
        }

        return $items;
    }

    /** @inheritDoc */
    protected function getListQuery(): QueryInterface
    {
        $query = DB::query();
        $tag   = Application::tag();

        $query->select([DB::qn('a') . '.*', DB::qn("a.label_$tag", 'name'), DB::qn('vl.title', 'level')])
            ->from(DB::qn('#__groups_attributes', 'a'))
            ->innerJoin(DB::qn('#__viewlevels', 'vl'), DB::qc('a.viewLevelID', 'vl.id'));

        $contextValue = $this->getState('filter.context');
        $positiveInt  = (is_numeric($contextValue) and $contextValue = (int) $contextValue);

        if ($positiveInt and in_array($contextValue, Helper::CONTEXTS)) {
            if ($contextValue === Helper::PERSONS_CONTEXT) {
                $query->where(DB::qc('a.context', Helper::GROUPS_CONTEXT, '!='));
            }
            elseif ($contextValue === Helper::GROUPS_CONTEXT) {
                $query->where(DB::qc('a.context', Helper::PERSONS_CONTEXT, '!='));
            }
        }

        $levelValue = $this->getState('filter.levelID');
        if (is_numeric($levelValue) and intval($levelValue) > 0) {
            $query->where(DB::qc('vl.id', (int) $levelValue));
        }

        $typeValue = $this->getState('filter.typeID');
        if (is_numeric($typeValue) and intval($typeValue) > 0) {
            $query->where(DB::qc('a.typeID', (int) $typeValue));
        }

        $this->orderBy($query);

        return $query;
    }
}