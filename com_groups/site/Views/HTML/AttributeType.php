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

use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;
use THM\Groups\Inputs\Input;

/**
 * View class for displaying available attribute types.
 */
class AttributeType extends ListView
{
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		// Manage access is a prerequisite for getting this far
		ToolbarHelper::addNew('AttributeTypes.add');
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'AttributeTypes.delete');
		ToolbarHelper::divider();

		ToolbarHelper::title(Text::_('GROUPS_ATTRIBUTE_TYPES'), '');

		if (Can::administrate())
		{
			ToolbarHelper::preferences('com_groups');
			//ToolbarHelper::divider();
		}
	}

	/**
	 * Method to get display
	 *
	 * @param   Object  $tpl  template
	 *
	 * @return void
	 */
	public function display($tpl = null)
	{
		if (!Can::manage())
		{
			Application::error(403);
		}

		//TODO: suppress ordering if a filter has been used
		$this->headers = [
			'check' => ['type' => 'check'],
			'name'  => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_ATTRIBUTE_TYPE'),
				'type'       => 'text'
			],
			'input' => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_INPUT'),
				'type'       => 'text'
			],
		];

		parent::display($tpl);
	}

	/**
	 * Supplements item information for display purposes.
	 */
	protected function supplementItems()
	{
		foreach ($this->items as $item)
		{
			/** @var Input $input */
			$input       = $item->input;
			$item->input = $input->getName();
		}
	}
}
