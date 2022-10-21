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

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Registry\Registry;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;

/**
 * View class for handling lists of items.
 * - Overrides/-writes to avoid deprecated code in the platform or promote ease of use
 * - Supplemental functions to extract common code from list models
 */
abstract class ListView extends BaseView
{
	public array $activeFilters;
	public array $batch;
	public Form $filterForm;
	public array $headers = [];
	protected array $items;
	protected string $layout = 'list';
	protected Pagination $pagination;
	public Registry $state;

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
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
	 * @inheritDoc
	 */
	public function display($tpl = null)
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			Application::message(implode("\n", $errors), 'error');
			Application::redirect('', 500);
		}

		$this->addToolbar();
		parent::display($tpl);
	}
}