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

//use THM\Groups\Helpers\Can;

/**
 * Handles code common for HTML output of outstanding tasks in the view context.
 */
trait Tasked
{
    /** @var array the open items. */
    public array $toDo = [];

    /**
     * Creates a standardized output for development tasks in the view context.
     *
     * @return void
     */
    public function renderTasks(): void
    {
        if (true) {//Can::administrate() and $this->toDo) {
            echo '<h6>Tasks:</h6>';
            echo '<ul>';
            foreach ($this->toDo as $toDo) {
                echo "<li>$toDo</li>";
            }
            echo '</ul>';
        }
    }
}