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

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers;
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available attribute types data.
 */
class Attributes extends ListModel
{
    /**
     * @inheritDoc
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = [
                'assigned',
                'typeID',
                'viewLevelID',
            ];
        }

        parent::__construct($config, $factory);
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        Application::message(Text::_('GROUPS_503'));
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        $items = parent::getItems();

        foreach ($items as $item)
        {
            // Management access is a prerequisite of accessing this view at all.
            $item->access   = true;
            $item->editLink = Route::_('index.php?option=com_groups&view=Attribute&id=' . $item->id);
        }

        return $items;
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  QueryInterface
     */
    protected function getListQuery(): QueryInterface
    {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);
        $tag   = Application::getTag();

        $query->select([
            $db->quoteName('a') . '.*',
            $db->quoteName("a.label_$tag", 'name'),
            $db->quoteName("t.name_$tag", 'type'),
            $db->quoteName('vl.title', 'level')
        ]);

        $attributes = $db->quoteName('#__groups_attributes', 'a');
        $contextID  = $db->quoteName('a.context');
        $levelID    = $db->quoteName('vl.id');
        $lCondition = $db->quoteName('a.viewLevelID') . " = $levelID";
        $levels     = $db->quoteName('#__viewlevels', 'vl');
        $typeID     = $db->quoteName('t.id');
        $tCondition = $db->quoteName('a.typeID') . " = $typeID";
        $types      = $db->quoteName('#__groups_types', 't');

        $query->from($attributes)->join('inner', $levels, $lCondition)->join('inner', $types, $tCondition);

        $contextValue = $this->getState('filter.context');
        $positiveInt  = (is_numeric($contextValue) and $contextValue = (int)$contextValue);

        if ($positiveInt and in_array($contextValue, Helpers\Attributes::VALID_CONTEXTS))
        {
            if ($contextValue === Helpers\Attributes::PERSONS_CONTEXT)
            {
                $query->where($contextID . ' != ' . Helpers\Attributes::GROUPS_CONTEXT);
            }
            elseif ($contextValue === Helpers\Attributes::GROUPS_CONTEXT)
            {
                $query->where($contextID . ' != ' . Helpers\Attributes::PERSONS_CONTEXT);
            }
        }

        $levelValue = $this->getState('filter.levelID');
        if (is_numeric($levelValue) and intval($levelValue) > 0)
        {
            $levelValue = (int)$levelValue;
            $query->where($levelID . ' = :levelID')
                ->bind(':levelID', $levelValue, ParameterType::INTEGER);
        }

        $typeValue = $this->getState('filter.typeID');
        if (is_numeric($typeValue) and intval($typeValue) > 0)
        {
            $typeValue = (int)$typeValue;
            $query->where($typeID . ' = :typeID')
                ->bind(':typeID', $typeValue, ParameterType::INTEGER);
        }

        $this->orderBy($query);

        return $query;
    }

    /**
     * @inheritDoc
     */
    protected function populateState($ordering = 'name', $direction = 'asc')
    {
        parent::populateState($ordering, $direction);
    }
}