<?php
/**
 * @package     THM\Groups\Fields\Groups
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Helper\UserGroupsHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Tables\Groups as GT;

/**
 * Provides a list of context relevant groups.
 */
class Groups extends ListField
{
	protected $type = 'Groups';

	/**
	 * Method to get the group options.
	 *
	 * @return  array  the group option objects
	 */
	protected function getOptions(): array
	{
		$defaultOptions = parent::getOptions();
		$nameColumn     = 'name_' . Application::getTag();
		$options        = [];

		foreach (UserGroupsHelper::getInstance()->getAll() as $groupID => $group)
		{
			$disabled = $groupID <= 9 ? 'disabled' : '';
			$table    = new GT($this->getDatabase());

			if ($table->load($groupID) and $name = $table->$nameColumn ?? null)
			{
				$group->title = $name;
			}

			$options[] = (object) [
				'disable' => $disabled,
				'text'    => str_repeat('- ', $group->level) . $group->title,
				'value'   => $group->id
			];
		}

		return array_merge($defaultOptions, $options);
	}
}