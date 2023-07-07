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

class Attribute extends FormView
{
    /**
     * @inheritDoc
     */
    protected function addToolbar(array $buttons = []): void
    {
        $buttons = empty($this->item->id) ? ['apply'] : ['save', 'apply', 'save2copy', 'save2new'];
        parent::addToolbar($buttons);
    }
}