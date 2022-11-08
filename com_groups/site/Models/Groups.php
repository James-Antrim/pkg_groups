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

use Joomla\CMS\Helper\UserGroupsHelper as UGH;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available groups data.
 */
class Groups extends ListModel
{
	/**
	 * @inheritDoc
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null)
	{
		Migration::migrate();

		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = ['roleID'];
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

		Application::message(Text::_('GROUPS_503'));
	}

	/**
	 * @inheritDoc
	 */
	public function getItems()
	{
		$items    = parent::getItems() ?: [];
		$ugHelper = UGH::getInstance();

		foreach ($items as $item)
		{
			$ugHelper->populateGroupData($item);
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
		$tag   = Application::getTag();

		$id       = $db->quoteName('g.id', 'id');
		$groupID  = $db->quoteName('g.id');
		$name     = $db->quoteName("g.name_$tag", 'name');
		$parentID = $db->quoteName('ug.parent_id');
		// Select the required fields from the table.
		$query->select([$id, $name, $parentID]);

		$condition  = $db->quoteName('ug.id') . " = $groupID";
		$groups     = $db->quoteName('#__groups_groups', 'g');
		$userGroups = $db->quoteName('#__usergroups', 'ug');
		$query->from($groups)->join('inner', $userGroups, $condition);

		if ($roleID = (int) $this->getState('filter.roleID'))
		{
			$condition = $db->quoteName('ra.groupID') . " = $groupID";
			$ra        = $db->quoteName('#__groups_role_associations', 'ra');
			$raRoleID  = $db->quoteName('ra.roleID');

			if ($roleID >= 1)
			{
				$query->join('inner', $ra, $condition)
					->where("$raRoleID = :roleID")
					->bind(':roleID', $roleID, ParameterType::INTEGER);
			}
			else
			{
				$query->join('left', $ra, $condition)
					->where("$raRoleID IS NULL");
			}
		}

		// Filter the comments over the search string if set.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$ids = (int) substr($search, 3);
				$query->where($db->quoteName('g.id') . ' = :id');
				$query->bind(':id', $ids, ParameterType::INTEGER);
			}
			else
			{
				$nameDE = $db->quoteName('g.name_de');
				$nameEN = $db->quoteName('g.name_en');
				$search = '%' . trim($search) . '%';
				$query->where("($nameDE LIKE :title1 OR $nameEN LIKE :title2)");
				$query->bind(':title1', $search);
				$query->bind(':title2', $search);
			}
		}

		// Add the list ordering clause.
		$this->orderBy($query);

		return $query;
	}

	/**
	 * @inheritDoc
	 */
	protected function populateState($ordering = 'ug.lft', $direction = 'asc')
	{
		// Load the parameters.
		$params = Application::getParams('com_users')->merge(Application::getParams());
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}
}