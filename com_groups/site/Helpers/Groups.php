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

			$group->roles = RoleAssociations::byGroupID($groupID);
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
			if (empty($group->roles))
			{
				continue;
			}

			$disabled = in_array($groupID, self::DEFAULT) ? 'disabled' : '';

			$options[] = (object) [
				'disable' => $disabled,
				'text'    => self::getPrefix($group->level) . $group->title,
				'value'   => $group->id
			];
		}

		return $options;
	}

	/**
	 * Gets the prefix for hierarchical list displays.
	 *
	 * @param   int  $level  the nested level of the group
	 *
	 * @return string the prefix to display
	 *
	 */
	public static function getPrefix(int $level): string
	{
		$prefix = '';
		if ($level > 1)
		{
			$prefix = '<span class="text-muted">';
			$prefix .= str_repeat('&#8942;&nbsp;&nbsp;&nbsp;', $level - 2);
			$prefix .= '</span>&ndash;&nbsp;';
		}

		return $prefix;
	}
}