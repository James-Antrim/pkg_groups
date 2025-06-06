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
use THM\Groups\Adapters\Application;

/**
 * Class representing the attributes table.
 */
class Attributes extends Table
{
    use Incremented, Ordered;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 => Both, 1 => Profile, 2 => Group'
     * @var int
     */
    public $context;

    /**
     * VARCHAR(255) NOT NULL DEFAULT ''
     * @var string
     */
    public $icon;

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
     * TEXT
     * @var string
     */
    public $options;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $required;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 => No, 1 => Yes'
     * @var int
     */
    public $showIcon;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0 => No, 1 => Yes'
     * @var int
     */
    public $showLabel;

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

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_attributes', 'id', $dbo);
    }

    /**
     * Gets the localized entry name.
     *
     * @param   int  $id
     *
     * @return string     *
     */
    public function getLabel(int $id): string
    {
        if (!$id) {
            return '';
        }

        if (!$this->load($id)) {
            return '';
        }

        if (Application::tag() === 'en') {
            return $this->label_en;
        }

        return $this->label_de;
    }
}