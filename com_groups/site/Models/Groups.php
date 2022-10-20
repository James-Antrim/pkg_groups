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
use THM\Groups\Adapters\Application;

class Groups extends ListModel
{
	/**
	 * @inheritDoc
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null)
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = [
				'id',
				'a.id',
				'parent_id',
				'a.parent_id',
				'title',
				'a.title',
				'lft',
				'a.lft',
				'rgt',
				'a.rgt',
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
				'a.*'
			)
		);

		$query->from($db->quoteName('#__usergroups') . ' AS a')
			->join('inner', $db->quoteName('#__groups_groups', 'g'), $db->quoteName('g.id') . ' = ' . $db->quoteName('a.id'));

		// Filter the comments over the search string if set.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$ids = (int) substr($search, 3);
				$query->where($db->quoteName('a.id') . ' = :id');
				$query->bind(':id', $ids, ParameterType::INTEGER);
			}
			else
			{
				$search = '%' . trim($search) . '%';
				$query->where($db->quoteName('a.title') . ' LIKE :title');
				$query->bind(':title', $search);
			}
		}

		// Add the list ordering clause.
		$this->orderBy($query);

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
	protected function populateState($ordering = 'a.lft', $direction = 'asc')
	{
		// Load the parameters.
		$params = Application::getParams('com_users')->merge(Application::getParams());
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}
}