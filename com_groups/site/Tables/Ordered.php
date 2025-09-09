<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

trait Ordered
{
    /**
     * INT(x) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public int $ordering = 0;
}