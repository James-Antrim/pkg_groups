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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers;
use THM\Groups\Helpers\Groups as Helper;
use THM\Groups\Adapters\HTML;

/**
 * View class for displaying available groups.
 */
class Groups extends ListView
{
	/**
	 * @inheritDoc
	 */
	protected function addToolbar()
	{
		$actions = CoreAccess::getActions('com_users');
		$toolbar = Toolbar::getInstance();

		if ($actions->get('core.create'))
		{
			$toolbar->addNew('Group.add');
		}

		$dropdown = $toolbar->dropdownButton('status-group');
		$dropdown->text('GROUPS_ACTIONS');
		$dropdown->toggleSplit(false);
		$dropdown->icon('icon-ellipsis-h');
		$dropdown->buttonClass('btn btn-action');
		$dropdown->listCheck(true);

		$childBar = $dropdown->getChildToolbar();

		if ($actions->get('core.edit'))
		{
			$button = $childBar->popupButton('roles');
			$button->icon('fa fa-hat-wizard');
			$button->text('GROUPS_BATCH_ROLES');
			$button->selector('batchRoles');
			$button->listCheck(true);
		}

		if ($actions->get('core.edit'))
		{
			$button = $childBar->popupButton('levels');
			$button->icon('icon-eye');
			$button->text('GROUPS_BATCH_LEVELS');
			$button->selector('batchLevels');
			$button->listCheck(true);
		}

		if ($actions->get('core.delete'))
		{
			$button = $childBar->delete('users.delete');
			$button->text('GROUPS_REMOVE');
			$button->message('GROUPS_REMOVE_CONFIRM');
			$button->listCheck(true);
		}

		parent::addToolbar();
	}

	/**
	 * @inheritDoc
	 */
	public function display($tpl = null)
	{
		if ($this->backend and !Helpers\Can::manage())
		{
			Application::error(403);
		}

		$this->todo = [
			'Add batch processing for adding / removing roles.',
			'Add batch processing for view levels.',
			'Add column for user count active / blocked.',
			'Add column for access debugging.'
		];

		parent::display($tpl);
	}

	/**
	 * @inheritDoc
	 */
	protected function completeItems()
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
				$item->prefix = Helper::getPrefix($item->level);
			}

			if (in_array($item->id, Helper::DEFAULT))
			{
				$context = "groups-group-$item->id";
				$tip     = Text::_('GROUPS_PROTECTED_GROUP');

				$item->icon = HTML::tip(HTML::icon('lock'), $context, $tip);
			}

			if (!$this->state->get('filter.roleID'))
			{
				$roles = Helper::getRoles($item->id);
				$count = count($roles);

				//$item->viewLevel = implode(', ', $levels);
				switch (true)
				{
					case $count === 0:
						$item->role = Text::_('GROUPS_NONE');
						break;
					case $count === 1:
						$item->role = $roles[Helpers\Roles::MEMBER];
						break;
					// Doesn't take up too much space I hope...
					case $count === 2:
					case $count === 3:
						unset($roles[Helpers\Roles::MEMBER]);
						$item->viewLevel = implode(', ', $roles);
						break;
					default:
						$item->role = Text::_('GROUPS_MULTIPLE');
						break;

				}
			}

			if (!$this->state->get('filter.levelID'))
			{
				$levels = Helper::getLevels($item->id);
				$count  = count($levels);

				switch (true)
				{
					case $count === 0:
						$item->viewLevel = Text::_('GROUPS_NONE');
						break;
					case $count > 2:
						$item->viewLevel = Text::_('GROUPS_MULTIPLE');
						break;
					default:
						$item->viewLevel = implode(', ', $levels);
						break;

				}
			}

			$item->editLink = Route::_('index.php?option=com_groups&view=Group&id=' . $item->id);
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
				'title'      => Text::_('GROUPS_GROUP'),
				'type'       => 'text'
			]
		];

		if (!$this->state->get('filter.roleID'))
		{
			$this->headers['role'] = [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_ROLE'),
				'type'       => 'value'
			];
		}

		if (!$this->state->get('filter.levelID'))
		{
			$this->headers['viewLevel'] = [
				'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
				'title'      => Text::_('GROUPS_LEVEL'),
				'type'       => 'value'
			];
		}

		$this->headers['id'] = [
			'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
			'title'      => Text::_('GROUPS_ID'),
			'type'       => 'value'
		];
	}
}
