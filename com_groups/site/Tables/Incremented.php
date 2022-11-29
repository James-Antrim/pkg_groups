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

trait Incremented
{
    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
     * @var int
     */
    public $id;
}