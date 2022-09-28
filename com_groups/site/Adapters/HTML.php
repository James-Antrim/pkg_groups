<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Joomla\CMS\HTML\HTMLHelper;

class HTML extends HTMLHelper
{
	/**
	 * Converts an array $property => $value to a string for use in HTML tags.
	 *
	 * @param   array  $array the properties and their values
	 *
	 * @return string
	 */
	public static function toProperties(array $array): string
	{
		foreach ($array as $property => $value)
		{
			$array[$property] = "$property=\"$value\"";
		}

		return $array ? implode(' ', $array) : '';
	}
}