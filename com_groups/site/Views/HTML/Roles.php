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
 * View class for displaying available roles.
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
		// Manage access is a prerequisite for getting this far
		ToolbarHelper::addNew('Role.add');
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Roles.delete');
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

	/**
	 * @inheritDoc
	 */
	protected function initializeHeaders()
	{
		//TODO: suppress ordering if a filter has been used
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
			],
			'groups'   => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_GROUPS'),
				'type'       => 'value'
			]
		];
	}
}
