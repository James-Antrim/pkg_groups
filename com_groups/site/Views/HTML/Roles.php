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

/**
 * THM_GroupsViewTHM_Groups class for component com_thm_groups
 */
class Roles extends ListView
{
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('GROUPS_ROLES'), 'users-cog groups');

		// Manage access is a prerequisite for getting this far
		ToolbarHelper::addNew('Role.add');
		ToolbarHelper::editList('Role.edit');
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Roles.delete');
		ToolbarHelper::divider();

		if (Can::administrate())
		{
			ToolbarHelper::preferences('com_groups');
			ToolbarHelper::divider();
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

		$this->headers = [
			'check'    => ['type' => 'check'],
			'ordering' => ['type' => 'ordering'],
			'name'     => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_ROLE'),
				'type'       => 'text'
			],
			'names'    => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_PLURAL'),
				'type'       => 'text'
			]
		];

		parent::display($tpl);
	}
}
