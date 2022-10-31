<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers\Inputs;

class URL extends Text
{
	public string $hint = 'https://www.website.com';
	public int $id = 3;
	public string $message_de = 'Die Adresse ist ungültig.';
	public string $message_en = 'The address is invalid.';
	public string $name_de = 'Internet Adresse';
	public string $name_en = 'Internet Address';
	public string $pattern = '';
	public string $type = 'url';
}