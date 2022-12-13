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
class Profiles extends ListModel
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
        if (!Helpers\Can::manage())
        {
            Application::error(403);
        }

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
            $item->editLink = Route::_('index.php?option=com_groups&view=Profile&id=' . $item->id);
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

        $query->select([
            $db->quoteName('p') . '.*',
            $db->quoteName('u') . '.*',
            $db->quoteName('ln.value', 'lastName'),
            $db->quoteName('fn.value', 'firstName')
        ]);

        $profileID   = $db->quoteName('p.id');
        $fnCondition = $db->quoteName('ln.profileID') . " = $profileID";
        $lnCondition = $db->quoteName('ln.profileID') . " = $profileID";
        $uCondition = $db->quoteName('u.id') . " = $profileID";

        $query->from($db->quoteName('#__groups_profiles', 'p'))
            ->join('inner', $db->quoteName('#__users', 'u'), $uCondition)
            ->join('left', $db->quoteName('#__groups_profile_attributes', 'ln'), $lnCondition)
            ->join('left', $db->quoteName('#__groups_profile_attributes', 'fn'), $fnCondition)
            ->where($db->quoteName('fn.attributeID') . ' = ' . Helpers\Attributes::FIRST_NAME)
            ->where($db->quoteName('ln.attributeID') . ' = ' . Helpers\Attributes::NAME);

        // groups: published, edit own, content enabled, role
        // joomla: status, activation, group, last visit, registration date
        /*$contextValue = $this->getState('filter.context');
        $positiveInt  = (is_numeric($contextValue) and $contextValue = (int)$contextValue);

        if ($positiveInt and in_array($contextValue, Helpers\Attributes::VALID_CONTEXTS))
        {
            if ($contextValue === Helpers\Attributes::PROFILES_CONTEXT)
            {
                $query->where($contextID . ' != ' . Helpers\Attributes::GROUPS_CONTEXT);
            }
            elseif ($contextValue === Helpers\Attributes::GROUPS_CONTEXT)
            {
                $query->where($contextID . ' != ' . Helpers\Attributes::PROFILES_CONTEXT);
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
        }*/

        $this->orderBy($query);

        return $query;
    }

    /**
     * @inheritDoc
     */
    protected function populateState($ordering = 'lastName, firstName', $direction = 'asc')
    {
        parent::populateState($ordering, $direction);
    }
}