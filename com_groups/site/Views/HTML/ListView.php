<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Exception;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\MVC\View\ListView as Base;
use Joomla\CMS\Uri\Uri;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\HTML;
use THM\Groups\Helpers\Can;
use THM\Groups\Views\Named;

/**
 * View class for handling lists of items.
 * - Overrides/-writes to avoid deprecated code in the platform or promote ease of use
 * - Supplemental functions to extract common code from list models
 */
abstract class ListView extends Base
{
	use Configured, Named;

	public bool $backend;
	public array $batch;
	public array $headers = [];
	public bool $mobile;
	public array $todo = [];
	protected $_layout = 'list';

	/**
	 * Constructor
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct(array $config)
	{
		// If this is not explicitly set going in Joomla will default to default without looking at the object property value.
		$config['layout'] = $this->_layout;

		parent::__construct($config);

		$this->configure();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		$titleKey = 'GROUPS_' . strtoupper($this->_name);

		ToolbarHelper::title(Text::_($titleKey), '');

		if (Can::administrate())
		{
			ToolbarHelper::preferences('com_groups');
			//ToolbarHelper::divider();
		}

		//ToolbarHelper::help('Users:_Groups');
	}

	/**
	 * Execute and display a template script. Inheriting classes handle the conditional access rights.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			Application::message(implode("\n", $errors), 'error');
			Application::redirect('', 500);
		}

		HTML::stylesheet(Uri::root() . 'components/com_groups/css/global.css');

		parent::display($tpl);
	}

	/**
	 * Checks whether the list items have been filtered.
	 * @return bool true if the results have been filtered, otherwise false
	 */
	protected function filtered(): bool
	{
		if ($filters = (array) $this->state->get('filter'))
		{
			// Search for filter value which has been set
			foreach ($filters as $filter)
			{
				if ($filter)
				{
					return true;
				}

				// Positive empty values
				if ($filter === 0 or $filter === '0')
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Initializes the headers after the view has been initialized.
	 */
	abstract protected function initializeHeaders();

	/**
	 * @inheritDoc
	 */
	protected function initializeView()
	{
		// TODO: check submenu viability

		parent::initializeView();

		// All the tools are now there.
		$this->initializeHeaders();
		$this->completeItems();
	}

	/**
	 * Supplements item information for display purposes as necessary.
	 */
	protected function completeItems()
	{
		// Filled by inheriting classes as necessary.
	}
}