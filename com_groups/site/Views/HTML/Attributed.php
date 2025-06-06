<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

/**
 * Handles code common for HTML output of resource attributes.
 */
trait Attributed
{
    /**
     * Generates an HTML list of the attributes values. Recursive as necessary.
     *
     * @param   array  $values  the attribute's values
     *
     * @return string
     */
    private function listValues(array $values): string
    {
        // There is only one value and the array key has no informational value.
        if (count($values) === 1 and is_numeric(array_key_first($values))) {
            return reset($values);
        }

        $return = '<ul>';

        foreach ($values as $key => $value) {
            $return .= '<li>';
            if (is_numeric($key)) {
                $return .= is_array($value) ? $this->listValues($value) : $value;
            }
            else {
                $return .= $key;
                $return .= is_array($value) ? $this->listValues($value) : " $value";
            }
            $return .= '</li>';
        }

        return $return . '</ul>';
    }

    /**
     * Creates a standardized output for resource attributes.
     *
     * @param   string                 $label  the label
     * @param   array|int|string|null  $value  the value
     *
     * @return void
     */
    public function renderAttribute(string $label, array|int|null|string $value): void
    {
        if (!$value) {
            return;
        }

        $value = is_array($value) ? $this->listValues($value) : $value;
        echo "<div class=\"attribute\"><div class=\"label\">$label</div><div class=\"value\">$value</div></div>";
    }
}