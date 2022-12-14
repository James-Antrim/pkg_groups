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

class Date extends Input
{
	public int $id = Inputs::DATE;
	public string $message_de = 'Das Datum ist ungültig.';
	public string $message_en = 'The date is invalid.';
	public string $name_de = 'Datum Auswahlkästchen';
	public string $name_en = 'Date Select Box';
	public string $type = 'date';
}