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

class EMail extends Text
{
	public string $hint = 'maxine.mustermann@fb.thm.de';
	public int $id = 6;
	public string $message_de = 'Die Adresse ist ungültig.';
	public string $message_en = 'The address is invalid.';
	public string $name_de = 'E-Mail Adresse';
	public string $name_en = 'E-Mail Address';
	public string $pattern = '^([\w\d\-_\.]+)@([\w\d\-_\.]+)$';
	public string $type = 'email';
}