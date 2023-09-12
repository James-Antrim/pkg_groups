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
use THM\Groups\Adapters\Application;

trait Persistent
{
    /**
     * Gets the current maximum value in the ordering column.
     * @return mixed the current maximum value in the ordering column
     */
    public static function getMax(string $table, string $column): mixed
    {
        $db     = Application::getDB();
        $query  = $db->getQuery(true);
        $column = $db->quoteName($column);
        $query->select("MAX($column)")->from($db->quoteName("#__groups_$table"));
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * Gets the current maximum value in the ordering column.
     * @return int the current maximum value in the ordering column
     */
    public static function getMaxOrdering(string $table): int
    {
        return (int) self::getMax($table, 'ordering');
    }

    /**
     * Returns a table based on the called class.
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