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


class File extends Input
{
	public string $accept = '';
	public int $id = 4;
	public bool $mode = true;
	public bool $multiple = false;
	public string $name_de = 'Datei Auswahlkästchen';
	public string $name_en = 'File Select Box';
	public string $type = 'file';
}