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
use Joomla\Database\DatabaseDriver;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Can;
use THM\Groups\Tables\Roles as Table;
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available roles data.
 */
class Roles extends ListModel
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
                'groupID',
            ];
        }

        parent::__construct($config, $factory);
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        if (!Can::manage())
        {
            Application::error(403);
        }

        if (!$ids = Input::getSelectedIDs())
        {
            Application::message(Text::_('JERROR_NO_ITEMS_SELECTED'), 'error');

            return;
        }

        /** @var DatabaseDriver $db */
        $db        = $this->getDatabase();
        $deleted   = 0;
        $protected = 0;
        $skipped   = 0;

        foreach ($ids as $id)
        {
            $table = new Table();

            if (!$table->load($id))
            {
                Application::error(412);
            }

            if ($table->protected or !$table->delete($id))
            {
                $protected++;
                continue;
            }

            if (!$table->delete($id))
            {
                $skipped++;
                continue;
            }

            $deleted++;
        }

        $id       = $db->quoteName('id');
        $ordering = $db->quoteName('ordering');
        $query    = $db->getQuery(true);
        $roles    = $db->quoteName('#__groups_roles');

        $query->select([$id, $ordering])->from($roles);
        $db->setQuery($query);
        $results = $db->loadAssocList('id', 'ordering');
        $results = array_flip($results);
        ksort($results);

        $ordering = 1;

        foreach ($results as $id)
        {
            $table = new Table();
            $table->load($id);
            $table->ordering = $ordering;
            $table->store();
            $ordering++;
        }

        if ($skipped)
        {
            Application::message(Text::sprintf('GROUPS_X_SKIPPED_NOT_DELETED', $skipped), 'error');
        }

        if ($protected)
        {
            Application::message(Text::sprintf('GROUPS_X_PROTECTED_NOT_DELETED', $protected), 'notice');
        }

        if ($deleted)
        {
            Application::message(Text::sprintf('GROUPS_X_DELETED', $deleted));
        }
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
            $item->editLink = Route::_('index.php?option=com_groups&view=RoleEdit&id=' . $item->id);

            if ($item->groups === 0)
            {
                $item->groups = Text::_('GROUPS_NO_GROUPS');
            }
            elseif ($item->groups === 1)
            {
                $item->groups = $item->group;
            }
            else
            {
                //TODO: link to groups view with role filter set to this one
                $item->groups = $item->group;
            }
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
        // Create a new query object.
        $db     = $this->getDatabase();
        $groups = $db->quoteName('g.id', 'groups');
        $groups = 'COUNT(' . implode(') AS ', explode(' AS ', $groups));
        $query  = $db->getQuery(true);
        $tag    = Application::getTag();

        //'COUNT(' . $db->quoteName('id') . ')'

        // Select the required fields from the table.
        $query->select([
            $db->quoteName('r.id'),
            $db->quoteName('r.ordering'),
            $db->quoteName("r.name_$tag", 'name'),
            $db->quoteName("r.names_$tag", 'names'),
            $db->quoteName("g.name_$tag", 'group'),
            $groups
        ]);

        $assocTable   = $db->quoteName('#__groups_role_associations', 'ra');
        $assocGroupID = $db->quoteName('ra.groupID');
        $assocRoleID  = $db->quoteName('ra.roleID');
        $groupsID     = $db->quoteName('g.id');
        $groupsTable  = $db->quoteName('#__groups_groups', 'g');
        $roleID       = $db->quoteName('r.id');
        $rolesTable   = $db->quoteName('#__groups_roles', 'r');

        $query->from($rolesTable)->group($roleID);

        $groupID = $this->getState('filter.groupID');
        if (is_numeric($groupID) and intval($groupID) > 0)
        {
            $groupID = (int)$groupID;
            $query->join('inner', $assocTable, "$assocRoleID = $roleID")
                ->join('inner', $groupsTable, "$groupsID = $assocGroupID")
                ->where($groupsID . ' = :groupID')
                ->bind(':groupID', $groupID, ParameterType::INTEGER);
        }
        else
        {
            $query->join('left', $assocTable, "$assocRoleID = $roleID")
                ->join('left', $groupsTable, "$groupsID = $assocGroupID");

            if (is_numeric($groupID) and intval($groupID) < 0)
            {
                $query->where($groupsID . ' IS NULL');
            }
        }

        $this->orderBy($query);

        return $query;
    }

    /**
     * @inheritDoc
     */
    protected function populateState($ordering = 'ordering', $direction = 'asc')
    {
        parent::populateState($ordering, $direction);
    }
}