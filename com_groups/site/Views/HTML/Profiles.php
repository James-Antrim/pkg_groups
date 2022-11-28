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
use Joomla\CMS\Router\Route;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers;

/**
 * View class for displaying available profiles.
 */
class Profiles extends ListView
{
	/**
	 * @inheritDoc
	 */
	protected function addToolbar()
	{
		/**
		 * @todo add items
		 */
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
			'Everything :)',
			'Add hooks to catch filters links from the groups (or other) views.',
			'EG:',
			'Blocked users from group   ...?option=com_groups&view=Profiles&filter[groupID]=3&filter[state]=0',
			'!Displayed users from group   ...?option=com_groups&view=Profiles&filter[groupID]=3&filter[published]=0',
		];

		parent::display($tpl);
	}

	/**
	 * @inheritDoc
	 */
	protected function completeItems()
	{
		foreach ($this->items as $item)
		{
			$item->editLink = Route::_('index.php?option=com_groups&view=Profile&layout=edit&id=' . $item->id);
			$item->viewLink = Route::_('index.php?option=com_groups&view=Profile&id=' . $item->id);
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

		$this->headers['id'] = [
			'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
			'title'      => Text::_('GROUPS_ID'),
			'type'       => 'value'
		];
	}
}
