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

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel as Base;
use Joomla\Database\QueryInterface;
use Joomla\Registry\Registry;
use THM\Groups\Adapters\Application;

/**
 * Model class for handling lists of items.
 * - Overrides/-writes to avoid deprecated code in the platform or promote ease of use
 * - Supplemental functions to extract common code from list models
 */
abstract class ListModel extends Base
{
	use Named;

	/**
	 * A state object. Overrides the use of the deprecated CMSObject.
	 *
	 * @var    Registry
	 */
	protected $state = null;

	/**
	 * @inheritdoc
	 * The state is set here to prevent the use of a deprecated CMSObject as the state in the stateAwareTrait.
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null)
	{
		$this->state = new Registry();

		try
		{
			parent::__construct($config, $factory);
		}
		catch (Exception $exception)
		{
			Application::message($exception->getMessage(), 'error');
			Application::redirect('', $exception->getCode());
		}
	}

	/**
	 * @inheritDoc
	 * Replacing the deprecated CMSObject with Registry makes the parent no longer function correctly this compensates for that.
	 */
	public function getActiveFilters(): array
	{
		$activeFilters = [];

		if (!empty($this->filter_fields))
		{
			foreach ($this->filter_fields as $filter)
			{
				$filterName = 'filter.' . $filter;
				$value      = $this->state->get($filterName);

				if ($value or is_numeric($value))
				{
					$activeFilters[$filter] = $value;
				}
			}
		}

		return $activeFilters;
	}

	/**
	 * Gets the filter form. Overwrites the parent to have form names analog to the view names in which they are used.
	 * Also has enhanced error reporting in the event of failure.
	 *
	 * @param   array  $data      data
	 * @param   bool   $loadData  load current data
	 *
	 * @return  Form|null  the form object or null if the form can't be found
	 */
	public function getFilterForm($data = [], $loadData = true): ?Form
	{
		$this->filterFormName = strtolower($this->name);

		$context = $this->context . '.filter';
		$options = ['control' => '', 'load_data' => $loadData];

		try
		{
			return $this->loadForm($context, $this->filterFormName, $options);
		}
		catch (Exception $exception)
		{
			Application::message($exception->getMessage(), 'error');
			Application::redirect('', $exception->getCode());
		}

		return null;
	}

	/**
	 * Adds a standard order clause for the given $query;
	 */
	protected function orderBy(QueryInterface $query)
	{
		if ($column = $this->getState('list.ordering'))
		{
			$column    = $query->quoteName($query->escape($column));
			$direction = $query->escape($this->getState('list.direction', 'ASC'));
			$query->order("$column $direction");
		}
	}
}