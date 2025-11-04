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
class Content extends FormView
{
    /** @inheritDoc */
    protected string $layout = 'content';

    /** @inheritDoc */
    protected function addToolbar(array $buttons = [], string $constant = ''): void
    {
        $this->toDo[] = 'If no root category has been set display a warning.';
        $this->toDo[] = 'Differentiate between content and pages regarding featured and ordering while saving.';
        $this->toDo[] = 'Finish the form manifest.';
        $this->toDo[] = 'Associations.';
        $this->toDo[] = 'Preview?.';
        $this->toDo[] = 'Associations.';
        $this->toDo[] = 'Localizations.';
        $this->toDo[] = 'Change the nature of the categories field according to the number of options.';

        $buttons = empty($this->item->id) ? ['save', 'apply', 'save2new'] : ['save', 'apply', 'save2copy', 'save2new'];
        parent::addToolbar($buttons);
    }
}