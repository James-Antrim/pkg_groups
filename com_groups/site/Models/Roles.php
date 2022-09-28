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
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\Component;

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
	 * Build an SQL query to load the list data.
	 *
	 * @return  QueryInterface
	 */
	protected function getListQuery(): QueryInterface
	{
		// Create a new query object.
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'r.*'
			)
		);

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
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = 'r.ordering', $direction = 'asc')
	{
		// Load the parameters.
		$params = Component::getParams('com_users')->merge(Component::getParams());
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}
}