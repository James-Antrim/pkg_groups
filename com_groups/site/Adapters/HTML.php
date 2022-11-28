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
	 * @param   array  $array  the properties and their values
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

	/**
	 * Creates an icon with tooltip as appropriate.
	 *
	 * @param   string  $class  the icon class(es)
	 *
	 * @return string the HTML for the icon to be displayed
	 */
	public static function icon(string $class): string
	{
		if (strpos($class, ','))
		{
			[$subset, $class] = explode(',', $class);
			$class  = "fa$subset fa-$class";
		}
		elseif (strpos($class, 'fa') === false)
		{
			$class = "fa fa-$class";
		}

		return "<i class=\"$class\" aria-hidden=\"true\"></i>";
	}

	/**
	 * The content wrapped with a link referencing a tip and the tip.
	 *
	 * @param   string  $content  the content referenced by the tip
	 * @param   string  $tip      the tip to be displayed
	 * @param   string  $url      the url linked by the tip as applicable
	 * @param   bool    $newTab   whether the url should open in a new tab
	 *
	 * @return string the HTML for the content and tip
	 */
	public static function tip(
		string $content,
		string $context,
		string $tip,
		array $properties = [],
		string $url = '',
		bool $newTab = false
	): string
	{
		if (empty($tip) and empty($url))
		{
			return $content;
		}

		$properties['aria-describedby'] = $context;

		if ($url and $newTab)
		{
			$properties['target'] = '_blank';
		}

		$url = $url ?: '#';
		$content = self::link($url, $content, $properties);
		$tip = "<div role=\"tooltip\" id=\"$context\">" . $tip . '</div>';

		return $content . $tip;
	}
}