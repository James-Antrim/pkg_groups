<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      Dennis Priefer, <dennis.priefer@mni.thm.de>
 * @author      Niklas Simonis, <niklas.simonis@mni.thm.de>
 * @author      Ilja Michajlow, <ilja.michajlow@mni.thm.de>
 * @author      Dieudonne Timma Meyatchie, <dieudonne.timma.meyatchie@mni.thm.de>
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Joomla\CMS\Helper\ContentHelper as UsersAccess;
use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;

/**
 * THM_GroupsViewTHM_Groups class for component com_thm_groups
 */
class Groups extends ListView
{

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		$usersAccess = UsersAccess::getActions('com_users');

		if ($usersAccess->get('core.create'))
		{
			ToolbarHelper::addNew('Group.add');
		}

		if ($usersAccess->get('core.delete'))
		{
			ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Groups.delete');
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
		if ($this->backend and !Can::manage())
		{
			Application::error(403);
		}

		parent::display($tpl);
	}
}
