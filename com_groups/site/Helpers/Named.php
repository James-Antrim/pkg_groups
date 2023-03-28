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

trait Named
{
    use Persistent;

    /**
     * Attempts to retrieve the name of the resource.
     *
     * @param int $resourceID the id of the resource
     *
     * @return string
     */
    public static function getName(int $resourceID): string
    {
        return self::getNameAttribute('name', $resourceID);
    }

    /**
     * Attempts to retrieve the name of the resource.
     *
     * @param string $columnName the substantive part of the column name to search for
     * @param int $resourceID the id of the resource
     *
     * @return string
     */
    public static function getNameAttribute(string $columnName, int $resourceID): string
    {
        $table = self::getTable();
        if (!$table->load($resourceID))
        {
            return '';
        }

        $tableFields = $table->getFields();
        if (array_key_exists($columnName, $tableFields))
        {
            // Some name columns may contain a null value
            return (string)$table->$columnName;
        }

        $localizedName = "{$columnName}_" . Application::getTag();
        if (array_key_exists($localizedName, $tableFields))
        {
            // Some name columns may contain a null value
            return (string)$table->$localizedName;
        }

        return '';
    }

    /**
     * Attempts to retrieve the plural of the resource.
     *
     * @param int $resourceID the id of the resource
     *
     * @return string
     */
    public static function getPlural(int $resourceID): string
    {
        return self::getNameAttribute('plural', $resourceID);
    }
}