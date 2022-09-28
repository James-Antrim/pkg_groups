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
use THM\Groups\Adapters\Component;

class ListModel extends Base
{
	use Named;

	/**
	 * Constructor
	 *
	 * @param   array                     $config   An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   MVCFactoryInterface|null  $factory  The factory.
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null)
	{
		try
		{
			parent::__construct($config, $factory);
		}
		catch (Exception $exception)
		{
			Component::message($exception->getMessage(), 'error');
			Component::redirect('', $exception->getCode());
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getFilterForm($data = [], $loadData = true): ?Form
	{
		$this->filterFormName = strtolower($this->name);

		Form::addFieldPath(JPATH_COMPONENT_SITE . '/Fields');
		Form::addFormPath(JPATH_COMPONENT_SITE . '/forms');

		try
		{
			$context = $this->context . '.filter';
			$options = ['control' => '', 'load_data' => $loadData];

			return $this->loadForm($context, $this->filterFormName, $options);
		}
		catch (Exception $exception)
		{
			Component::message($exception->getMessage(), 'error');
			Component::redirect('', $exception->getCode());
		}

		return null;
	}

	/**
	 * Adds a standard order clause for the given $query;
	 *
	 * @since version
	 */
	protected function order(QueryInterface $query)
	{
		if ($column = $this->getState('list.ordering'))
		{
			$column    = $query->quoteName($query->escape($column));
			$direction = $query->escape($this->getState('list.direction', 'ASC'));
			$query->order("$column $direction");
		}
	}
}