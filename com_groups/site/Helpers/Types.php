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

use THM\Groups\Adapters\Application;
use THM\Groups\Inputs\Input;
use THM\Groups\Tables\Types as Table;

class Types implements Selectable
{
	public const DATE = 5, EMAIL = 6, HTML = 2, IMAGE = 4, NAME = 8, NAME_SUPPLEMENT = 9, TELEPHONE_EU = 7, TEXT = 1, URL = 3;

	// IDs for quick range validation
	public const PROTECTED_IDS = [
		self::DATE,
		self::EMAIL,
		self::HTML,
		self::IMAGE,
		self::NAME,
		self::NAME_SUPPLEMENT,
		self::TELEPHONE_EU,
		self::TEXT,
		self::URL,
	];

	/**
	 * @inheritDoc
	 */
	public static function getAll(): array
	{
		$db    = Application::getDB();
		$query = $db->getQuery(true);
		$id    = 'DISTINCT ' . $db->quoteName('id');
		$types = $db->quoteName('#__groups_types');
		$query->select($id)->from($types);
		$db->setQuery($query);

		$return = [];

		if (!$typeIDs = $db->loadColumn())
		{
			return $return;
		}

		foreach ($typeIDs as $typeID)
		{
			$type = new Table($db);
			$type->load($typeID);

			$input = Inputs::INPUTS[$type->inputID];
			$input = "THM\Groups\Inputs\\$input";

			/** @var Input $input */
			$return[] = new $input($type);
		}

		return $return;
	}

	/**
	 * @inheritDoc
	 */
	public static function getOptions(): array
	{
		$options = [];

		/** @var  Input $field */
		foreach (self::getAll() as $input)
		{
			$options[$input->getName()] = (object) [
				'text'  => $input->getName(),
				'value' => $input->id
			];
		}

		ksort($options);

		return $options;
	}
}