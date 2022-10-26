<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use Joomla\CMS\Helper\UserGroupsHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Tables\Groups as GT;

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Groups implements Selectable
{
	public const PUBLIC = 1, REGISTERED = 2, AUTHOR = 3, EDITOR = 4, PUBLISHER = 5, MANAGER = 6, ADMIN = 7, SUPER_ADMIN = 8;

	public const DEFAULT = [
		self::ADMIN,
		self::AUTHOR,
		self::EDITOR,
		self::MANAGER,
		self::PUBLIC,
		self::PUBLISHER,
		self::REGISTERED,
		self::SUPER_ADMIN
	];

	/**
	 * @inheritDoc
	 */
	public static function getAll(): array
	{
		$groups     = UserGroupsHelper::getInstance()->getAll();
		$nameColumn = 'name_' . Application::getTag();

		foreach ($groups as $groupID => $group)
		{
			$table = new GT(Application::getDB());

			if ($table->load($groupID) and $name = $table->$nameColumn ?? null)
			{
				$group->title = $name;
			}
		}

		return $groups;
	}

	/**
	 * @inheritDoc
	 */
	public static function getOptions(): array
	{
		$options = [];

		foreach (self::getAll() as $groupID => $group)
		{
			$disabled = in_array($groupID, self::DEFAULT) ? 'disabled' : '';

			$options[] = (object) [
				'disable' => $disabled,
				'text'    => str_repeat('- ', $group->level) . $group->title,
				'value'   => $group->id
			];
		}

		return $options;
	}
}