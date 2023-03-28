<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use Joomla\CMS\Table\Table;

trait Persistent
{
    /**
     * Returns a table based on the called class.
     *
     * @return Table
     */
    public static function getTable(): Table
    {
        $helperClass = get_called_class();
        $segments    = explode('\\', $helperClass);
        $tableClass  = array_pop($segments);
        $fqn         = "\\THM\\Groups\\Tables\\$tableClass";

        return new $fqn();
    }
}