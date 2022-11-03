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


class Editor extends Input
{
	// Can be an array of plugin buttons to be excluded or set to false.
	public bool $buttons = true;
	// TODO: implement 'all' and selection in the attribute type definitions
	// 'Array' of plugin buttons to be hidden. Buttons must be set to true for this to work.
	public string $hide = 'all';
	public int $id = 2;
	public string $name_de = 'WYSIWYG-Editor';
	public string $name_en = 'WYSIWYG-Editor';
	public string $type = 'editor';
}