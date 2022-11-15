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

use Joomla\CMS\Helper\ContentHelper;

class Can
{
	/**
	 * Checks whether the user has 'admin' rights for the component.
	 *
	 * @return bool true if the user has 'admin' access, otherwise false
	 */
	public static function administrate(): bool
	{
		return ContentHelper::getActions('com_users')->get('core.admin');
	}

	/**
	 * Checks whether the user has 'create' rights for the component.
	 *
	 * @return bool true if the user has 'create' access, otherwise false
	 */
	public static function create(): bool
	{
		return (self::manage() or ContentHelper::getActions('com_users')->get('core.create'));
	}

	/**
	 * Checks whether the user has 'manage' rights for the component.
	 *
	 * @return bool true if the user has 'manage' access, otherwise false
	 */
	public static function manage(): bool
	{
		return (self::administrate() or ContentHelper::getActions('com_users')->get('core.manage'));
	}
}