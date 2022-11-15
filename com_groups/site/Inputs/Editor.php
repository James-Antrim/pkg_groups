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

class Editor extends Input
{
	// Enabled / disabled (all hidden).
	public bool $buttons = false;
	// 'Array' of plugin buttons to be hidden. Buttons must be enabled for this to work.
	public string $hide = '';
	public int $id = Inputs::EDITOR;
	public string $name_de = 'WYSIWYG-Editor';
	public string $name_en = 'WYSIWYG-Editor';
	public string $type = 'editor';
}