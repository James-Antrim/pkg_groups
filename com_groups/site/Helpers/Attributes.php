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

class Attributes
{
	public const BOTH_CONTEXTS = 0, GROUPS_CONTEXT = 2, PROFILES_CONTEXT = 1;

	public const EMAIL = 4, FAX = 8, FIRST_NAME = 1, HOMEPAGE = 9, NAME = 2, NAME_SUPPLEMENT_PRE = 5,
		NAME_SUPPLEMENT_POST = 7, PROFILE_PICTURE = 3, ROOM = 10, TELEPHONE = 6;

	// IDs for quick range validation
	public const PROTECTED_IDS = [
		self::EMAIL,
		self::FAX,
		self::FIRST_NAME,
		self::HOMEPAGE,
		self::NAME,
		self::NAME_SUPPLEMENT_PRE,
		self::NAME_SUPPLEMENT_POST,
		self::PROFILE_PICTURE,
		self::ROOM,
		self::TELEPHONE,
	];
}