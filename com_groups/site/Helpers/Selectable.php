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

interface Selectable
{
    /**
     * Returns a list of resource options.
     *
     * @return stdClass[]
     */
    public static function options(): array;

    /**
     * Returns a list of resource objects.
     *
     * @return stdClass[]
     */
    public static function resources(): array;
}