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

class User extends FormView
{
    /**
     * @inheritDoc
     */
    public function display($tpl = null): void
    {
        $this->todo = [
            'Everything ;)',
            'Add JS to validate image properties.'
        ];

        parent::display($tpl);
    }
}