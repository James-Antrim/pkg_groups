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

class Telephone extends Text
{
	public string $hint = '';
	public int $id = 7;
	public int $maxLength = 100;
	public string $name_de = 'Telefonnummer';
	public string $name_en = 'Telephone Number';
	public string $pattern = '';
	public string $type = 'tel';
}