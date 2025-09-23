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
use THM\Groups\Adapters\Database as DB;

trait Persistent
{
    /**
     * Gets the current maximum value of the table and column specified, optionally filtered the values of other columns.
     *
     * @param   string  $table    the table to query
     * @param   string  $column   the column whose max value is sought
     * @param   array   $filters  restrictions to filter the result set by as key => value pairs
     *
     * @return mixed
     */
    public static function max(string $table, string $column, array $filters = []): mixed
    {
        $column = DB::qn($column);
        $query  = DB::query()->select("MAX($column)")->from(DB::qn("#__groups_$table"));

        foreach ($filters as $filter => $value) {
            $query->where(DB::qc($filter, $value));
        }

        DB::set($query);

        return DB::result();
    }

    /**
     * Gets the next ordering value to use for insertions.
     *
     * @param   array  $filters  restrictions to filter the result set by as key => value pairs
     *
     * @return int
     */
    public static function next(array $filters = []): int
    {
        $helper   = get_called_class();
        $segments = explode('\\', $helper);
        $table    = strtolower(array_pop($segments));

        $max = self::max($table, 'ordering', $filters);
        return ($max === null) ? 0 : (int) $max + 1;
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