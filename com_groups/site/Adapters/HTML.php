<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Joomla\CMS\HTML\Helpers\{Form as FH, Grid, Number, SearchTools, Select};
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Utility\Utility;
use Joomla\Utilities\ArrayHelper;
use stdClass;

/**
 * Class integrates HTMLHelper and several further helper classes, wrapping unhandled exceptions and creating simplified
 * and documented access to functions otherwise called by magic methods.
 */
class HTML extends HTMLHelper
{
    /**
     * Returns an action on a grid
     *
     * @param   int     $index       the row index
     * @param   array   $state       the state configuration
     * @param   string  $controller  the name of the controller class
     *
     * @return  string
     */
    public static function button(int $index, array $state, string $controller): string
    {
        $task = $state['task'] ?? null;

        // A button must have a purpose
        if (!$task) {
            return '';
        }

        $active     = $state['class'] === 'publish';
        $ariaID     = "{$state['column']}-$index";
        $attributes = [
            'class'   => $active ? 'tbody-icon active' : 'tbody-icon',
            'href'    => 'javascript:void(0);',
            'onclick' => "return Joomla.listItemTask('cb$index','$controller.$task','adminForm')"
        ];
        $icon       = $active ? 'fa fa-check' : $state['class'];
        $icon       = self::icon($icon);
        $tip        = '';

        if (!empty($state['tip'])) {
            $attributes['aria-labelledby'] = $ariaID;

            $tip = Text::_($state['tip']);
        }

        $return = '<a ' . ArrayHelper::toString($attributes) . '>' . $icon . '</a>';

        if ($tip) {
            $return .= "<div role=\"tooltip\" id=\"$ariaID\">$tip</div>";
        }

        return $return;
    }

    /**
     * Method to check all checkboxes in a resource table.
     * @return  string
     * @see Grid::checkall()
     */
    public static function checkAll(): string
    {
        return Grid::checkall();
    }

    /**
     * Method to create a checkbox for a resource table row.
     *
     * @param   int  $rowNumber  the row number in the HTML output
     * @param   int  $rowID      the id of the resource row in the database
     *
     * @return  string
     */
    public static function checkBox(int $rowNumber, int $rowID): string
    {
        return Grid::id($rowNumber, $rowID);
    }

    /**
     * Creates an icon with tooltip as appropriate.
     *
     * @param   string  $class  the icon class(es)
     *
     * @return string
     */
    public static function icon(string $class): string
    {
        return "<i class=\"$class\" aria-hidden=\"true\"></i>";
    }

    /**
     * Method to create a sorting column header for a resource table. Header text key is automatically prefaced and
     * localized.
     *
     * @param   string  $constant       the text to display in the table header
     * @param   string  $column         the query column that this link sorts by
     * @param   string  $direction      the current sort direction for this query column
     * @param   string  $currentColumn  the current query column that the results are being sorted by
     *
     * @return  string
     * @see SearchTools::sort()
     */
    public static function sort(string $constant, string $column, string $direction, string $currentColumn): string
    {
        return SearchTools::sort(Text::_($constant), $column, $direction, $currentColumn);
    }

    /**
     * @inheritDoc
     * Link text key is automatically prefaced and localized.
     */
    public static function link($url, $text, $attribs = null): string
    {
        return parent::link($url, $text, $attribs);
    }

    /**
     * Method to return the maximum upload size defined in the site configured in the ini.
     * @return  string
     * @see Number::bytes(), Utility::getMaxUploadSize()
     */
    public static function maxUploadSize(): string
    {
        return Number::bytes(Utility::getMaxUploadSize());
    }

    /**
     * Create an object that represents an option in an option list.
     *
     * @param   int|string  $value    the option value
     * @param   string      $text     the option text
     * @param   bool        $disable  whether the option is disabled
     *
     * @return  stdClass
     */
    public static function option(int|string $value, string $text, bool $disable = false): stdClass
    {
        return Select::option((string) $value, $text, 'value', 'text', $disable);
    }

    /**
     * Method to create a column header for activating the sort column when multiple list columns can be sorted.
     *
     * @param   string  $currentColumn  the current query column that the results are being sorted by
     *
     * @return  string
     * @see SearchTools::sort()
     */
    public static function orderingSort(string $currentColumn): string
    {
        return SearchTools::sort(
            '',
            'ordering',
            'asc',
            $currentColumn,
            null,
            'asc',
            '',
            HTML::icon('fa fa-arrows-alt-v')

        );
    }

    /**
     * Generates a string containing property information for an HTML element to be output
     *
     * @param   mixed &$element  the element being processed
     *
     * @return string the HTML attribute output for the item
     */
    public static function properties(array &$element): string
    {
        $return = '';

        if (!empty($element['properties']) and is_array($element['properties'])) {
            $return = ArrayHelper::toString($element['properties']);
        }
        unset($element['properties']);

        return $return;
    }

    /**
     * Generates an HTML selection list.
     *
     * @param   string            $name        the field name.
     * @param   stdClass[]        $options     the field options
     * @param   array|int|string  $selected    the selected resource designators; called function accepts array|string
     * @param   array             $properties  additional HTML properties for the select tag
     * @param   string            $textKey     name of the name column when working directly with table rows
     * @param   string            $valueKey    name of the value column when working directly with table rows
     * @param   bool|string       $id          the optional id for the select box
     *
     * @return  string
     */
    public static function selectBox(
        string $name,
        array $options,
        array|int|string $selected = [],
        array $properties = [],
        string $textKey = 'text',
        string $valueKey = 'value',
        bool|string $id = false
    ): string
    {
        /**
         * Called function will most likely eventually be typed to array|string, most of our single selection values
         * will be integers for resource ids.
         */
        $selected = gettype($selected) === 'integer' ? (string) $selected : $selected;

        return Select::genericlist($options, $name, $properties, $valueKey, $textKey, $selected, $id, true);
    }

    /**
     * Displays a hidden token field to reduce the risk of CSRF exploits.
     * @return  string  A hidden input field with a token
     * @see     FH::token(), Session::checkToken()
     */
    public static function token(): string
    {
        return FH::token();
    }

    /**
     * Returns an action on a grid
     *
     * @param   int     $index       the row index
     * @param   array   $state       the state configuration
     * @param   string  $controller  the name of the controller class
     * @param   string  $neither     text for columns which cannot be toggled
     *
     * @return  string
     */
    public static function toggle(int $index, array $state, string $controller = '', string $neither = ''): string
    {
        $ariaID     = "{$state['column']}-$index";
        $attributes = [
            'aria-labelledby' => $ariaID,
            'class'           => "tbody-icon"
        ];

        $class  = $state['class'];
        $return = '';

        if ($neither) {
            $iconClass = 'fa fa-minus';
            $task      = '';
            $tip       = $neither;
        }
        else {
            $iconClass = $class === 'publish' ? 'fa fa-check' : 'fa fa-times';
            $task      = $state['task'];
            $tip       = Text::_($state['tip']);
        }

        $icon = self::icon($iconClass);

        if ($task and $controller) {
            $attributes['class']   .= $class === 'publish' ? ' active' : '';
            $attributes['href']    = 'javascript:void(0);';
            $attributes['onclick'] = "return Joomla.listItemTask('cb$index','$controller.$task','adminForm')";

            $return .= '<a ' . ArrayHelper::toString($attributes) . '>' . $icon . '</a>';
        }
        else {
            $return .= '<span ' . ArrayHelper::toString($attributes) . '>' . $icon . '</span>';
        }

        $return .= "<div role=\"tooltip\" id=\"$ariaID\">$tip</div>";

        return $return;
    }

    /**
     * The content wrapped with a link referencing a tip and the tip.
     *
     * @param   string  $content  the content referenced by the tip
     * @param   string  $context
     * @param   string  $tip      the tip to be displayed
     * @param   array   $properties
     * @param   string  $url      the url linked by the tip as applicable
     * @param   bool    $newTab   whether the url should open in a new tab
     *
     * @return string
     */
    public static function tip(
        string $content,
        string $context,
        string $tip,
        array $properties = [],
        string $url = '',
        bool $newTab = false
    ): string
    {
        if (empty($tip) and empty($url)) {
            return $content;
        }

        $properties['aria-describedby'] = $context;

        if ($url and $newTab) {
            $properties['target'] = '_blank';
        }

        $url     = $url ?: '#';
        $content = self::link($url, $content, $properties);
        $tip     = "<div role=\"tooltip\" id=\"$context\">" . Text::_($tip) . '</div>';

        return $content . $tip;
    }
}