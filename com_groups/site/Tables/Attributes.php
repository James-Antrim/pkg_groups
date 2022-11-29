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
 * Class representing the attributes table.
 */
class Attributes extends Table
{
    /**
     * TEXT
     * @var string
     */
    public $configuration;

    /**
     * VARCHAR(255) NOT NULL DEFAULT ''
     * @var string
     */
    public $icon;

    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
     * @var int
     */
    public $id;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public $label_de;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public $label_en;

    /**
     * INT(3) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public $ordering;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $required;

    /**
     * INT(11) UNSIGNED NOT NULL (fk_attributes_typeID -> groups_types.id)
     * @var int
     */
    public $typeID;


    /**
     * INT(10) UNSIGNED DEFAULT 1 (fk_attributes_viewLevelID -> viewlevels.id)
     * @var int
     */
    public $viewLevelID;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo)
    {
        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_attributes', 'id', $dbo);
    }
}