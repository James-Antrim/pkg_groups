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
use THM\Groups\Adapters\HTML;
use THM\Groups\Helpers\Can;
use THM\Groups\Helpers\Inputs\Input;

/**
 * View class for displaying available attribute types.
 */
class Attributes extends ListView
{
	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		// Manage access is a prerequisite for getting this far
		ToolbarHelper::addNew('Attributes.add');
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Attributes.delete');
		ToolbarHelper::divider();

		parent::addToolbar();
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

		//TODO: supress ordering if a filter has been used
		$this->headers = [
			'check' => ['type' => 'check'],
			'name'  => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_ATTRIBUTE'),
				'type'       => 'text'
			],
			'type'  => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_ATTRIBUTE_TYPE'),
				'type'       => 'text'
			],
			'level' => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_VIEW_LEVEL'),
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
			if ($item->icon and strpos($item->icon, 'icon-') === 0)
			{
				$icon       = str_replace('icon-', '', $item->icon);
				$item->icon = HTML::icon($icon);
			}
			else
			{
				$item->icon = null;
			}
		}
	}
}
