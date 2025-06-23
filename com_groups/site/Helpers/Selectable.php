<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use stdClass;

abstract class Selectable
{
    /**
     * Retrieves the ids of resources.
     * @return int[]
     */
    public static function ids(): array
    {
        return array_keys(static::resources());
    }

    /**
     * Returns a list of resource options.
     * @return stdClass[]
     */
    abstract public static function options(): array;

    /**
     * Returns an array of resources [id => resource].
     * @return stdClass[]
     */
    abstract public static function resources(): array;
}