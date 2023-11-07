<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2020 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Exception;
use Joomla\CMS\Log\Log;
use Joomla\Database\DatabaseQuery;
use Joomla\Utilities\ArrayHelper;
use stdClass;

/**
 * Adapts functions of the document class to avoid exceptions and deprecated warnings.
 */
class Database
{
    /**
     * Execute the SQL statement.
     * @return  bool  True on success, bool false on failure.
     */
    public static function execute(): bool
    {
        $dbo = Application::getDB();
        try {
            return $dbo->execute();
        }
        catch (Exception $exception) {
            self::logException($exception);
            Application::message($exception->getMessage(), Application::ERROR);

            return false;
        }
    }

    /**
     * Retrieves the null date (time) specific to the driver.
     * @return  string  the driver specific null date (time).
     */
    public static function getNullDate(): string
    {
        $dbo = Application::getDB();

        return $dbo->getNullDate();
    }

    /**
     * Get the current query object or a new JDatabaseQuery object.
     *
     * @param   bool  $new  True to return a new DatabaseQuery object, otherwise false
     *
     * @return  DatabaseQuery
     */
    public static function getQuery(bool $new = true): DatabaseQuery
    {
        $dbo = Application::getDB();

        return $dbo->getQuery($new);
    }

    /**
     * Inserts a row into a table based on an object's properties.
     *
     * @param   string   $table   The name of the database table to insert into.
     * @param   object  &$object  A reference to an object whose public properties match the table fields.
     * @param   string   $key     The name of the primary key. If provided the object property is updated.
     *
     * @return  bool    True on success.
     */
    public static function insertObject(string $table, object &$object, string $key = 'id'): bool
    {
        $dbo = Application::getDB();

        try {
            return $dbo->insertObject($table, $object, $key);
        }
        catch (Exception $exception) {
            self::logException($exception);
            Application::message($exception->getMessage(), Application::ERROR);

            return false;
        }
    }

    /**
     * Method to get the first row of the result set from the database query as an associative array
     * of ['field_name' => 'row_value'].
     * @return  array  The return value or an empty array if the query failed.
     */
    public static function loadAssoc(): array
    {
        $dbo = Application::getDB();
        try {
            $result = $dbo->loadAssoc();

            return $result ?: [];
        }
        catch (Exception $exception) {
            self::logException($exception);
            Application::message($exception->getMessage(), Application::ERROR);

            return [];
        }
    }

    /**
     * Method to get an array of the result set rows from the database query where each row is an associative array
     * of ['field_name' => 'row_value'].  The array of rows can optionally be keyed by a field name, but defaults to
     * a sequential numeric array.
     * NOTE: Choosing to key the result array by a non-unique field name can result in unwanted
     * behavior and should be avoided.
     *
     * @param   string  $key     The name of a field on which to key the result array.
     * @param   string  $column  An optional column name. Instead of the whole row, only this column value will be in
     *                           the result array.
     *
     * @return  array[]   The return value or an empty array if the query failed.
     */
    public static function loadAssocList(string $key = '', string $column = ''): array
    {
        $dbo = Application::getDB();
        try {
            $result = $dbo->loadAssocList($key, $column);

            return $result ?: [];
        }
        catch (Exception $exception) {
            self::logException($exception);
            Application::message($exception->getMessage(), Application::ERROR);

            return [];
        }
    }

    /**
     * Method to get the first field of the first row of the result set from the database query and return it as an int
     * value.
     *
     * @param   bool  $default  the default value
     *
     * @return  bool  The return value if successful, otherwise the default value
     */
    public static function loadBool(bool $default = false): bool
    {
        $result = self::loadResult();

        return $result !== null ? (bool) $result : $default;
    }

    /**
     * Method to get an array of values from the <var>$offset</var> field in each row of the result set from
     * the database query.
     *
     * @param   int  $offset  The row offset to use to build the result array.
     *
     * @return  array    The return value or null if the query failed.
     */
    public static function loadColumn(int $offset = 0): array
    {
        $dbo = Application::getDB();
        try {
            $result = $dbo->loadColumn($offset);

            return $result ?: [];
        }
        catch (Exception $exception) {
            self::logException($exception);
            Application::message($exception->getMessage(), Application::ERROR);

            return [];
        }
    }

    /**
     * Method to get the first field of the first row of the result set from the database query and return it as an int
     * value.
     *
     * @param   int  $default  the default value
     *
     * @return  int  The return value if successful, otherwise the default value
     */
    public static function loadInt(int $default = 0): int
    {
        $result = self::loadResult();

        return $result !== null ? (int) $result : $default;
    }

    /**
     * Method to get an array of values from the <var>$offset</var> field in each row of the result set from
     * the database query.
     *
     * @param   int  $offset  The row offset to use to build the result array.
     *
     * @return  int[]    The return value or null if the query failed.
     */
    public static function loadIntColumn(int $offset = 0): array
    {
        if ($result = self::loadColumn($offset)) {
            return ArrayHelper::toInteger($result);
        }

        return $result;
    }

    /**
     * Method to get the first row of the result set from the database query as an object.
     *
     * @param   string  $class  The class name to use for the returned row object.
     *
     * @return  object  The return value or an empty array if the query failed.
     */
    public static function loadObject(string $class = 'stdClass'): stdClass
    {
        $dbo = Application::getDB();
        try {
            $result = $dbo->loadObject($class);

            return $result ?: new stdClass();
        }
        catch (Exception $exception) {
            self::logException($exception);
            Application::message($exception->getMessage(), Application::ERROR);

            return new stdClass();
        }
    }

    /**
     * Method to get an array of the result set rows from the database query where each row is an object.  The array
     * of objects can optionally be keyed by a field name, but defaults to a sequential numeric array.
     * NOTE: Choosing to key the result array by a non-unique field name can result in unwanted
     * behavior and should be avoided.
     *
     * @param   string  $key    The name of a field on which to key the result array.
     * @param   string  $class  The class name to use for the returned row objects.
     *
     * @return  array   The return value or an empty array if the query failed.
     */
    public static function loadObjectList(string $key = '', string $class = 'stdClass'): array
    {
        $dbo = Application::getDB();
        try {
            $result = $dbo->loadObjectList($key, $class);

            return $result ?: [];
        }
        catch (Exception $exception) {
            self::logException($exception);
            Application::message($exception->getMessage(), Application::ERROR);

            return [];
        }
    }

    /**
     * Method to get the first field of the first row of the result set from the database query.
     *
     * @param   mixed  $default  the default return value
     *
     * @return  mixed  The return value if successful, otherwise the default value
     */
    public static function loadResult(mixed $default = null): mixed
    {
        $dbo = Application::getDB();
        try {
            $result = $dbo->loadResult();

            return $result ?: $default;
        }
        catch (Exception $exception) {
            self::logException($exception);
            Application::message($exception->getMessage(), Application::ERROR);

            return $default;
        }
    }

    /**
     * Method to get the first field of the first row of the result set from the database query and return it as an int
     * value.
     *
     * @param   string  $default  the default return value
     *
     * @return  string  The return value if successful, otherwise the default value
     */
    public static function loadString(string $default = ''): string
    {
        $result = self::loadResult();

        return $result ? (string) self::loadResult() : $default;
    }

    /**
     * Logs the exception.
     *
     * @param   Exception  $exception
     *
     * @return void
     */
    public static function logException(Exception $exception): void
    {
        $options = ['text_file' => 'groups_db_errors.php', 'text_entry_format' => '{DATETIME}:{MESSAGE}'];
        Log::addLogger($options, Log::ALL, ['com_groups.dbErrors']);
        $message = "\n\nError Message:\n--------------\n";
        $message .= print_r($exception->getMessage(), true);
        $message .= "\n\nQuery:\n------\n";
        $message .= print_r((string) self::getQuery(false), true);
        $message .= "\n\nCall Stack:\n-----------\n";
        $message .= print_r($exception->getTraceAsString(), true);
        $message .= "\n\n--------------------------------------------------------------------------------------------";
        $message .= "--------------------------------------";
        Log::add($message, Log::DEBUG, 'com_groups.dbErrors');
    }

    /**
     * Formats the values to form a set used in the predicate of a query restriction. <NOT> IN <value set>
     *
     * @param   array  $values  the values to aggregate
     * @param   bool   $negate  whether the set should be negated
     * @param   false  $quote   whether to quote the values
     *
     * @return string the comma separated values surrounded by braces
     */
    public static function makeSet(array $values, bool $negate = false, bool $quote = false): string
    {
        $values = $quote ? self::quote($values) : ArrayHelper::toInteger($values);
        $values = implode(',', $values);

        return $negate ? " NOT IN ($values)" : " IN ($values)";
    }

    /**
     * Creates a condition in name quotes.
     *
     * @param   string  $leftColumn   the left column in the comparison
     * @param   string  $rightColumn  the right column in the comparison
     * @param   string  $operator
     *
     * @return string an accurate representation of what is actually returned from the dbo quoteName function
     * @noinspection PhpMethodNamingConventionInspection
     */
    public static function qc(string $leftColumn, string $rightColumn, string $operator = '='): string
    {
        return self::qn($leftColumn) . " $operator " . self::qn($rightColumn);
    }

    /**
     * Creates conditions in name quotes from a multidimensional array.
     *
     * @param   array[]  $conditions  the conditions to quote [leftColumn, rightColumn <, operator>]
     * @param   string   $separator   the operator between the conditions themselves
     *
     * @return string an accurate representation of what is actually returned from the dbo quoteName function
     */
    public static function qcs(array $conditions, string $separator = 'AND'): string
    {
        $return = [];

        foreach ($conditions as $condition) {
            switch (count($condition)) {
                case 3:
                    [$leftColumn, $rightColumn, $operator] = $condition;
                    break;
                case 2:
                    [$leftColumn, $rightColumn] = $condition;
                    break;
                default:
                    continue 2;
            }

            $operator  = empty($operator) ? '=' : $operator;
            $return [] = self::qc($leftColumn, $rightColumn, $operator);
        }

        return implode(" $separator ", $return);
    }

    /**
     * Wraps the database quote name function for use outside a query class without PhpStorm complaining about
     * resolution.
     *
     * @param   string|string[]    $name   the column name or names
     * @param   array|string|null  $alias  the column alias or aliases, if arrays and incongruent sizes => empty array
     *                                     return value
     *
     * @return string|string[] an accurate representation of what is actually returned from the dbo quoteName function
     * @noinspection PhpMethodNamingConventionInspection
     */
    public static function qn(array|string $name, array|string $alias = null): array|string
    {
        return Application::getDB()->quoteName($name, $alias);
    }

    /**
     * Wraps the database quote function for use outside a query class without PhpStorm complaining about resolution and
     * inaccurate return typing.
     *
     * @param   string|string[]  $term    the term or terms to quote
     * @param   bool             $escape  whether to escape the name provided
     *
     * @return string|string[] an accurate representation of what is actually returned from the dbo quoteName function
     */
    public static function quote(array|string $term, bool $escape = true): array|string
    {
        return Application::getDB()->quote($term, $escape);
    }

    /**
     * Sets the SQL statement string for later execution.
     *
     * @param   string|DatabaseQuery  $query   The SQL statement to set either as a DatabaseQuery object or a string.
     * @param   int                   $offset  The affected row offset to set.
     * @param   int                   $limit   The maximum affected rows to set.
     *
     * @return  void
     */
    public static function setQuery(string|DatabaseQuery $query, int $offset = 0, int $limit = 0): void
    {
        Application::getDB()->setQuery($query, $offset, $limit);
    }
}
