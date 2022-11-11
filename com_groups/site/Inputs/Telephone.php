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

class Telephone extends Text
{
	public int $id = Inputs::TELEPHONE;
	public int $maxlength = 100;
	public string $message_de = 'Die Telefonnummer ist ungültig.';
	public string $message_en = 'The telephone number is invalid.';
	public string $name_de = 'Telefonnummer Eingabekästchen';
	public string $name_en = 'Telephone Number Entry Box';
	public string $pattern = '';
	public string $type = 'tel';
}