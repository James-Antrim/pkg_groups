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
use THM\Groups\Helpers\Can;
use THM\Groups\Helpers\Inputs;
use THM\Groups\Inputs\Input;
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available attribute types data.
 */
class Types extends ListModel
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
                'inputID',
            ];
        }

        parent::__construct($config, $factory);
    }

    /**
     * @inheritDoc
     */
    public function delete(): void
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
            $item->editLink = Route::_('index.php?option=com_groups&view=Type&id=' . $item->id);

            $input = Inputs::INPUTS[$item->inputID];
            $input = "THM\Groups\Inputs\\$input";

            /** @var Input $input */
            $item->input = new $input();
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
            $db->quoteName('t.id'),
            $db->quoteName("t.name_$tag", 'name'),
            $db->quoteName('t.inputID', 'inputID')
        ]);

        $query->from($db->quoteName('#__groups_types', 't'));

        $iIDColumn = $db->quoteName('t.inputID');
        $inputID   = $this->getState('filter.inputID');
        if (is_numeric($inputID) and intval($inputID) > 0)
        {
            $inputID = (int)$inputID;
            $query->where($iIDColumn . ' = :inputID')
                ->bind(':inputID', $inputID, ParameterType::INTEGER);
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