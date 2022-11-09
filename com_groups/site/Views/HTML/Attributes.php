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
use THM\Groups\Helpers;

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
		if (!Helpers\Can::manage())
		{
			Application::error(403);
		}

		$this->headers = [
			'check'   => ['type' => 'check'],
			'name'    => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_ATTRIBUTE'),
				'type'       => 'text'
			],
			'type'    => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_ATTRIBUTE_TYPE'),
				'type'       => 'text'
			],
			'context' => [
				'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_CONTEXT'),
				'type'       => 'text'
			],
			'level'   => [
				'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
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
			if ($item->icon)
			{
				$icon = str_replace('icon-', '', $item->icon);
				$icon = str_replace('fa-', '', $icon);

				$item->icon = HTML::icon($icon);
			}
			else
			{
				$item->icon = null;
			}

			switch ($item->context)
			{
				case Helpers\Attributes::GROUPS_CONTEXT:
					$item->context = Text::_('GROUPS_GROUPS');
					break;
				case Helpers\Attributes::PROFILES_CONTEXT:
					$item->context = Text::_('GROUPS_PROFILES');
					break;
				default:
					$item->context = Text::_('GROUPS_GROUPS_AND_PROFILES');
					break;
			}
		}
	}
}
