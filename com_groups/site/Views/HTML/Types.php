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
class Types extends ListView
{
	/**
	 * @inheritDoc
	 */
	protected function addToolbar()
	{
		// Manage access is a prerequisite for getting this far
		ToolbarHelper::addNew('Types.add');
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Types.delete');
		ToolbarHelper::divider();

		ToolbarHelper::title(Text::_('GROUPS_TYPES'), '');

		if (Can::administrate())
		{
			ToolbarHelper::preferences('com_groups');
			//ToolbarHelper::divider();
		}
	}

	/**
	 * @inheritDoc
	 */
	public function display($tpl = null)
	{
		if (!Can::manage())
		{
			Application::error(403);
		}

		parent::display($tpl);
	}

	/**
	 * @inheritDoc
	 */
	protected function completeItems()
	{
		foreach ($this->items as $item)
		{
			/** @var Input $input */
			$input       = $item->input;
			$item->input = $input->getName();
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function initializeHeaders()
	{
		$this->headers = [
			'check' => ['type' => 'check'],
			'name'  => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_TYPE'),
				'type'       => 'text'
			],
			'input' => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_INPUT'),
				'type'       => 'text'
			],
		];
	}
}
