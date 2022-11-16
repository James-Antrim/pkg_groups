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

use Joomla\CMS\Helper\ContentHelper as CoreAccess;
use Joomla\CMS\Helper\UserGroupsHelper as UGH;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;
use THM\Groups\Helpers\Groups as Helper;
use THM\Groups\Adapters\HTML;

/**
 * View class for displaying available groups.
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
		$usersAccess = CoreAccess::getActions('com_users');

		if ($usersAccess->get('core.create'))
		{
			ToolbarHelper::addNew('Group.add');
		}

		if ($usersAccess->get('core.delete'))
		{
			ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Groups.delete');
			ToolbarHelper::divider();
		}

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
		if ($this->backend and !Can::manage())
		{
			Application::error(403);
		}

		$this->headers = [
			'check' => ['type' => 'check'],
			//'ordering' => ['type' => 'ordering'],
			'name'  => [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_GROUP'),
				'type'       => 'text'
			]
			/*
			 * TODO
			Old Groups had...
			- a batch interface for adding/removing roles
			- a column for all related roles
			-- with buttons to remove them once added
			- a column displaying the number of users assigned to the group
			Joomla User Groups has...
			- a list field with explicit ordering options
			- a table tool for selecting which columns are displayed
			- a column that directly links access rights
			- a column for the number of active users
			- a column for the number of blocked users
			*/
		];

		parent::display($tpl);
	}

	/**
	 * @inheritDoc
	 */
	protected function supplementItems()
	{
		$ugh = UGH::getInstance();

		foreach ($this->items as $item)
		{
			if ($this->filtered())
			{
				// The last item is the $item->name
				array_pop($item->path);

				foreach ($item->path as $key => $parentID)
				{
					$item->path[$key] = $ugh->get($parentID)->title;
				}
				$item->supplement = implode(' / ', $item->path);
			}
			else
			{
				$item->name = str_repeat('- ', $item->level) . $item->name;
			}

			if (in_array($item->id, Helper::DEFAULT))
			{
				$context = "groups-group-$item->id";
				$tip     = Text::_('GROUPS_PROTECTED_GROUP');

				$item->icon = HTML::tip(HTML::icon('lock'), $context, $tip);
			}
		}
	}
}
