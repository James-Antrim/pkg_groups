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

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Groups
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
}