<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use THM\Groups\Adapters\Input;

class TemplateAttributes extends ListController
{
    /**
     * @inheritdoc
     */
    protected string $item = 'TemplateAttribute';

    /**
     * Closes the form view without saving changes.
     * @return void
     */
    public function cancel(): void
    {
        $this->setRedirect("$this->baseURL&view=Templates");
    }

    /**
     * Toggles the role column's value.
     *
     * @param bool $value
     *
     * @return void
     */
    public function toggle(bool $value): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool('template_attributes', $selectedIDs, $value);

        $this->farewell($selected, $updated);
    }
}