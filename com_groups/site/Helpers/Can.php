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

use Joomla\CMS\Factory;

class Can
{
	/**
	 * Checks whether the user has administrative rights for the component.
	 *
	 * @return bool
	 */
	public static function administrate(): bool
	{
		$user = Factory::getUser();

		return ($user->authorise('core.admin') or $user->authorise('core.admin', 'com_groups'));
	}

	/**
	 * Checks whether the user has management rights for the component.
	 *
	 * @return bool true if the user has admin access, otherwise false
	 */
	public static function manage(): bool
	{
		return (self::administrate() or Factory::getUser()->authorise('core.manage', 'com_groups'));
	}
}