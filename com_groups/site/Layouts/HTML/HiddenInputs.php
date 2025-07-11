<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Layouts\HTML;

use THM\Groups\Views\HTML\ListView;

/**
 * Outputs grouped hidden inputs together.
 */
class HiddenInputs
{
    /**
     * Renders any hidden fields specific to this list.
     *
     * @param   ListView  $view
     */
    public static function render(ListView $view): void
    {
        if (empty($view->filterForm)) {
            return;
        }

        if (!$fields = $view->filterForm->getGroup('hidden')) {
            return;
        }

        foreach ($fields as $field) {
            // Undo Joomla packaging of names by group
            $name = str_replace(['hidden[', ']'], '', $field->__get('name'));
            echo '<input type="hidden" name="' . $name . '" value="' . $field->__get('value') . '"/>';
        }
    }
}