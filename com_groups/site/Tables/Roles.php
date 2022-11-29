<?php /** @noinspection PhpMissingFieldTypeInspection */

/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;

/**
 * Class representing the roles table.
 */
class Roles extends Table
{
    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
     * @var int
     */
    public $id;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public $name_de;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public $name_en;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public $names_de;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public $names_en;

    /**
     * INT(3) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public $ordering;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $protected;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo)
    {
        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_roles', 'id', $dbo);
    }
}