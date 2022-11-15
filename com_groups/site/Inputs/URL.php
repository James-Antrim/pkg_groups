<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Inputs;

use THM\Groups\Helpers\Inputs;

class URL extends Text
{
	const LINK = 1, ICON = 2, TEXT = 3;

	public int $display = self::LINK;
	public string $hint = 'https://www.website.com';
	public int $id = Inputs::URL;
	public string $message_de = 'Die Adresse ist ungültig.';
	public string $message_en = 'The address is invalid.';
	public string $name_de = 'URL Eingabekästchen';
	public string $name_en = 'URL Entry Box';
	public string $pattern = '';
	public string $text_de = '';
	public string $text_en = '';
	public string $type = 'url';

	// TODO: support title localization
	// TODO: support localized display options
}