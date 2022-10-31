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

class Date extends Input
{
	public int $id = 5;
	public string $name_de = 'Datum';
	public string $name_en = 'Date';
	public string $type = 'date';
}