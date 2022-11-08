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

use THM\Groups\Helpers\Inputs\Input;

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Inputs implements Selectable
{
	// IDs as readable constants
	public const DATE = 5, EDITOR = 2, EMAIL = 6, FILE = 4, TELEPHONE = 7, TEXT = 1, URL = 3;

	// IDs for quick range validation
	public const IDS = [
		self::DATE,
		self::EDITOR,
		self::EMAIL,
		self::FILE,
		self::TELEPHONE,
		self::TEXT,
		self::URL
	];

	// ID => Class Map for loading by readable constants
	public const INPUTS = [
		self::DATE      => 'Date',
		self::EDITOR    => 'Editor',
		self::EMAIL     => 'EMail',
		self::FILE      => 'File',
		self::TELEPHONE => 'Telephone',
		self::TEXT      => 'Text',
		self::URL       => 'URL'
	];

	/**
	 * @inheritDoc
	 */
	public static function getAll(): array
	{
		$inputs = [];

		foreach (self::INPUTS as $input)
		{
			$input = "THM\Groups\Helpers\Inputs\\$input";

			/** @var Input $input */
			$input = new $input();

			if ($input->supported)
			{
				$inputs[$input->id] = $input;
			}
		}

		return $inputs;
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
