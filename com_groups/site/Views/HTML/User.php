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
    /** @inheritdoc */
    protected string $layout = 'user';

    /** @inheritDoc */
    public function __construct(array $config)
    {
        $config['layout'] = $this->layout;

        parent::__construct($config);
    }

    /** @inheritDoc */
    public function display($tpl = null): void
    {
        $this->toDo = [
            'Add JS to validate image properties.'
        ];

        parent::display($tpl);
    }
}