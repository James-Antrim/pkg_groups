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
use THM\Groups\Adapters\Application;

class Groups extends ListField
{
	protected $type = 'Groups';

	protected function getOptions(): array
	{
		$defaultOptions = parent::getOptions();

		$db = $this->getDatabase();

		$gID   = $db->quoteName('g.id');
		$tag   = Application::getTag();
		$query = $db->getQuery(true);
		$ugID  = $db->quoteName('ug.id');

		$query->select([
			"DISTINCT $ugID",
			$db->quoteName('ug.level'),
			$db->quoteName('ug.lft', 'left'),
			$db->quoteName("g.name_$tag", 'name'),
			$db->quoteName('ug.rgt', 'right'),
			$db->quoteName('ug.title')
		])
			->from($db->quoteName('#__usergroups', 'ug'))
			->join('inner', $db->quoteName('#__groups_groups', 'g'), "$gID = $ugID");
		echo "<pre>" . print_r((string) $query, true) . "</pre>";

		return $defaultOptions;
	}
}