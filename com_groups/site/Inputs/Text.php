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

class Text extends Input
{
	public int $id = Inputs::TEXT;
	public int $maxlength = 255;
	public string $message_de = 'Einfache Texte dürfen weder Tags noch Zeilenumbrüche beinhalten.';
	public string $message_en = 'Simple texts may not contain tags or new lines.';
	public string $name_de = 'Text Eingabekästchen';
	public string $name_en = 'Text Entry Box';
	public string $pattern = '^[^<>{}]+$';
	public string $type = 'text';
	public bool $validate = true;
}