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

use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Tables\Templates as Table;

class Templates extends ListController
{
    /**
     * @inheritdoc
     */
    protected string $item = 'Template';

    /**
     * Blocks the selected users.
     * @return void
     */
    public function defaultCard(): void
    {
        $this->toggleUnique('cards');
    }

    /**
     * Blocks the selected users.
     * @return void
     */
    public function defaultVCard(): void
    {
        $this->toggleUnique('vcards');
    }

    /**
     * Suppresses role output for the given template.
     * @return void
     */
    public function hideRoles(): void
    {
        $this->toggle(false);
    }

    /**
     * Enables role output for the given template.
     * @return void
     */
    public function showRoles(): void
    {
        $this->toggle(true);
    }

    /**
     * Toggles the role column's value.
     *
     * @param bool $value
     *
     * @return void
     */
    private function toggle(bool $value): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool('roles', $selectedIDs, $value);

        $this->farewell($selected, $updated);
    }

    private function toggleUnique(string $column): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $table       = new Table();

        if ($selectedID = reset($selectedIDs) and $table->load($selectedID)) {
            $table->$column = 1;

            if ($this->zeroColumn('templates', $column) and $table->store()) {
                Application::message('GROUPS_DEFAULT_SET');
            } else {
                Application::message('GROUPS_500', Application::ERROR);
            }
        }

        $this->setRedirect("$this->baseURL&view=Templates");
    }
}