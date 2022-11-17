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

use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;

/**
 * View class for displaying available attribute types.
 */
class Type extends FormView
{
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		// Manage access is a prerequisite for getting this far
		ToolbarHelper::save('Type.save');
		ToolbarHelper::save2copy('Type.save2copy');
		ToolbarHelper::divider();

		parent::addToolbar();
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
}
