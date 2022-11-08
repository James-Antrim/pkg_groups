<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\Field\ListField;

/**
 * Provides a list of context relevant groups.
 */
class ViewLevels extends ListField
{
	protected $type = 'ViewLevels';

	/**
	 * Method to get the group options.
	 *
	 * @return  array  the group option objects
	 */
	protected function getOptions(): array
	{
		$defaultOptions = parent::getOptions();

		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		$levels = $db->quoteName('#__viewlevels', 'vl');
		$text   = $db->quoteName('vl.title', 'text');
		$title  = $db->quoteName('vl.title');
		$value  = 'DISTINCT ' . $db->quoteName('vl.id', 'value');

		$query->select([$value, $text])->from($levels)->order($title);

		if ($this->form->getName() === 'com_groups.attributes.filter')
		{
			$attributes = $db->quoteName('#__groups_attributes', 'a');
			$condition  = $db->quoteName('a.viewLevelID') . ' = ' . $db->quoteName('vl.id');
			$query->join('inner', $attributes, $condition);
		}

		// TODO: supplement query for the groups context

		$db->setQuery($query);

		$options = $db->loadObjectList() ?: [];

		return array_merge($defaultOptions, $options);
	}
}