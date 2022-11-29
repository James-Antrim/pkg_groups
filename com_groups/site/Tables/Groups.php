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
 * Class representing the groups table.
 */
class Groups extends Table
{
    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT (fk_groups_groupID -> usergroups.id)
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
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo)
    {
        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_groups', 'id', $dbo);
    }
}