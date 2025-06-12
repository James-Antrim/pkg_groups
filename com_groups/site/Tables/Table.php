<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Exception;
use Joomla\CMS\Table\Table as Core;
use ReflectionClass;
use ReflectionProperty;
use THM\Groups\Adapters\{Application, Text};

/**
 * Models the resource alluded to in the inheriting class name.
 * Wrapper to prevent unnecessary try/catch handling in client objects and standardized property retrieval after Joomla
 * declared their implementation deprecated.
 */
abstract class Table extends Core
{
    use Resettable;

    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
     *
     * @var int
     */
    public int $id = 0;

    /**
     * Wraps the parent load function in a try catch clause to avoid redundant handling in other classes.
     *
     * @param   mixed  $keys     An optional primary key value to load the row by, or an array of fields to match.
     *                           If not set the instance property value is used.
     * @param   bool   $reset    True to reset the default values before loading the new row.
     *
     * @return  bool
     */
    public function load($keys = null, $reset = true): bool
    {
        try {
            return parent::load($keys, $reset);
        }
        catch (Exception $exception) {
            Application::message($exception->getMessage(), Application::ERROR);

            return false;
        }
    }

    /**
     * Adds a fail message for check functions that can fail. Returns false so to inline the entire fail process.
     * @return bool
     */
    protected function fail(): bool
    {
        Application::message(Text::sprintf('TABLE_CHECK_FAIL', Application::uqClass(get_called_class())), Application::ERROR);
        return false;
    }

    /**
     * Returns an associative array of object properties.
     *
     * @param   bool  $public
     *
     * @return  array
     */
    public function getProperties($public = true): array
    {
        $properties = [];
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {

            // Public untyped property from Joomla.
            if (is_null($property->getType())) {
                continue;
            }

            $column              = $property->getName();
            $properties[$column] = $this->$column ?? null;
        }
        return $properties;
    }

    /**
     * @inheritDoc
     */
    public function store($updateNulls = true): bool
    {
        return parent::store($updateNulls);
    }
}