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

use Joomla\CMS\Helper\ContentHelper as UsersAccess;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Adapters\Component;
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
		$usersAccess = UsersAccess::getActions('com_users');

		ToolbarHelper::title(Text::_('COM_GROUPS_ROLES'), 'users-cog groups');

		if (Can::manage())
		{
			ToolbarHelper::addNew('Role.add');
			ToolbarHelper::editList('Role.edit');
			ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Role.delete');
			ToolbarHelper::divider();
		}

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
			Component::error(403);
		}

		$this->headers = [
			'check' => ['type' => 'check'],
			'order' => ['type' => 'order'],
			'name'  => ['properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'], 'type' => 'text'],
			'names' => ['properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'], 'type' => 'text']
		];

		parent::display($tpl);
	}
}
