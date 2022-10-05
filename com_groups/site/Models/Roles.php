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
use THM\Groups\Adapters\Component;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Can;
use THM\Groups\Tables\Roles as Table;

class Roles extends ListModel
{
	/**
	 * @inheritDoc
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null)
	{
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
	 * Deletes role entries.
	 *
	 * @return void
	 */
	public function delete()
	{

		if (!Can::manage())
		{
			Component::error(403);
		}

		if (!$ids = Input::getSelectedIDs())
		{
			Component::message(Text::_('JERROR_NO_ITEMS_SELECTED'), 'error');

			return;
		}

		/** @var DatabaseDriver $db */
		$db        = $this->getDatabase();
		$deleted   = 0;
		$protected = false;
		$skipped   = 0;

		foreach ($ids as $id)
		{
			$table = new Table($db);

			if (!$table->load($id))
			{
				Component::error(412);
			}

			if ($table->protected or !$table->delete($id))
			{
				$protected = true;
				continue;
			}

			if (!$table->delete($id))
			{
				$skipped++;
				continue;
			}

			$deleted++;
		}

		$id    = $db->quoteName('id');
		$order = $db->quoteName('order');
		$query = $db->getQuery(true);
		$roles = $db->quoteName('#__groups_roles');

		$query->select([$id, $order])->from($roles);
		$db->setQuery($query);
		$results = $db->loadAssocList('id', 'order');
		$results = array_flip($results);
		ksort($results);

		$order = 1;

		foreach ($results as $id)
		{
			$table = new Table($db);
			$table->load($id);
			$table->order = $order;
			$table->store();
			$order++;
		}

		if ($skipped)
		{
			Component::message("$skipped entries could not be deleted. LOCALIZE", 'error');
		}

		if ($protected)
		{
			Component::message("A protected entry was not deleted. LOCALIZE", 'notice');
		}

		if ($deleted)
		{
			Component::message("$deleted entries were deleted successfully. LOCALIZE");
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
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);
		$tag   = Component::getTag();

		// Select the required fields from the table.
		$query->select([
			$db->quoteName('r.id'),
			$db->quoteName('r.order'),
			$db->quoteName("r.name_$tag", 'name'),
			$db->quoteName("r.names_$tag", 'names'),
			$db->quoteName('r.protected')
		]);

		$assocTable   = $db->quoteName('#__groups_role_associations', 'ra');
		$assocGroupID = $db->quoteName('ra.groupID');
		$assocRoleID  = $db->quoteName('ra.roleID');
		$groupsID     = $db->quoteName('ug.id');
		$groupsTable  = $db->quoteName('#__usergroups', 'ug');
		$roleID       = $db->quoteName('r.id');
		$rolesTable   = $db->quoteName('#__groups_roles', 'r');

		$query->from($rolesTable)
			->join('left', $assocTable, "$assocRoleID = $roleID")
			->join('left', $groupsTable, "$groupsID = $assocGroupID");

		// Filter the comments over the search string if set.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$ids = (int) substr($search, 3);
				$query->where("$roleID = :id");
				$query->bind(':id', $ids, ParameterType::INTEGER);
			}
			else
			{
				$search  = '%' . trim($search) . '%';
				$nameDE  = $db->quoteName('r.name_de') . ' LIKE :title';
				$nameEN  = $db->quoteName('r.name_en') . ' LIKE :title';
				$namesDE = $db->quoteName('r.names_de') . ' LIKE :title';
				$namesEN = $db->quoteName('r.names_en') . ' LIKE :title';
				$group   = $db->quoteName('ug.title') . ' LIKE :title';

				$query->where("($nameDE OR $nameEN OR $namesDE OR $namesEN OR $group)");
				$query->bind(':title', $search);
			}
		}

		// Add the list ordering clause.
		$this->order($query);

		return $query;
	}

	/**
	 * @inheritDoc
	 */
	protected function populateState($ordering = 'order', $direction = 'asc')
	{
		parent::populateState($ordering, $direction);
	}
}