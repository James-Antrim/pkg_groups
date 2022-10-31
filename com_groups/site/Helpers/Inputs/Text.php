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

class Text extends Input
{
	public string $hint = '';
	public int $id = 1;
	public int $maxLength = 255;
	public string $name_de = 'Text';
	public string $name_en = 'Text';
	public string $pattern = '^[^<>{}]+$';
	public string $type = 'text';
	public bool $validate = true;
}