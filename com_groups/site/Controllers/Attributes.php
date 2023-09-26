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

use THM\Groups\Adapters\{Application, Input, Text};
use THM\Groups\Helpers\Attributes as Helper;
use THM\Groups\Tables\Attributes as Table;

class Attributes extends ListController
{
    /**
     * @inheritdoc
     */
    protected string $item = 'Attribute';

    /**
     * Deletes the selected attributes.
     * @return void
     */
    public function delete(): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);

        if ($protectedIDs = array_intersect($selectedIDs, Helper::PROTECTED)) {
            $protected   = count($protectedIDs);
            $selectedIDs = array_diff($selectedIDs, Helper::PROTECTED);
            Application::message(Text::sprintf('GROUPS_X_PROTECTED_NOT_DELETED', $protected), Application::INFO);
        }

        $deleted = 0;
        $skipped = 0;

        foreach ($selectedIDs as $selectedID) {
            $table = new Table();

            if (!$table->delete($selectedID)) {
                $skipped++;
                continue;
            }

            $deleted++;
        }

        if ($skipped) {
            Application::message(Text::sprintf('GROUPS_X_SKIPPED_NOT_DELETED', $skipped), Application::ERROR);
        }

        $this->farewell($selected, $deleted, true);
    }

    /**
     * Removes the icon from the attribute label in profile views.
     * @return void
     */
    public function hideIcon(): void
    {
        $this->toggle('showIcon', false);
    }

    /**
     * Removes the text from the attribute label in profile views.
     * @return void
     */
    public function hideLabel(): void
    {
        $this->toggle('showLabel', false);
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     * @return  void
     */
    public function saveOrderAjax(): void
    {
        $this->checkToken();
        $this->authorizeAJAX();

        $ordering     = 1;
        $attributeIDs = Input::getArray('cid');

        foreach ($attributeIDs as $attributeID) {
            $protected = in_array($attributeID, Helper::PROTECTED);
            $table     = new Table();
            $table->load($attributeID);
            $table->ordering = $protected ? 0 : $ordering;
            $table->store();

            $ordering = $protected ? $ordering : $ordering + 1;
        }

        echo Text::_('Request performed successfully.');

        $this->app->close();
    }

    /**
     * Displays the icon in the attribute label in profile views.
     * @return void
     */
    public function showIcon(): void
    {
        $this->toggle('showIcon', true);
    }

    /**
     * Displays the text in the attribute label in profile views.
     * @return void
     */
    public function showLabel(): void
    {
        $this->toggle('showLabel', true);
    }

    /**
     * Toggles a boolean column's value.
     *
     * @param string $column
     * @param bool   $value
     *
     * @return void
     */
    private function toggle(string $column, bool $value): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $selectedIDs = array_diff($selectedIDs, Helper::PROTECTED);
        $updated     = $this->updateBool($column, $selectedIDs, $value);

        $this->farewell($selected, $updated);
    }
}