<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

/** @inheritDoc */
class Attribute extends FormView
{
    /** @inheritDoc */
    protected function addToolbar(array $buttons = [], string $constant = ''): void
    {
        $this->toDo[] = 'Find a way to display Font Awesome Brand Icons correctly in the select box';
        $buttons      = empty($this->item->id) ? ['save', 'apply', 'save2new'] : ['save', 'apply', 'save2copy', 'save2new'];
        parent::addToolbar($buttons);
    }
}