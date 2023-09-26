<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

abstract class Labeled extends ListController
{
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
    abstract protected function toggle(string $column, bool $value): void;
}