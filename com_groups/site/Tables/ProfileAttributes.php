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
 * Class representing the person attributes table.
 */
class ProfileAttributes extends Table
{
    use Incremented;

    /**
     * INT(11) UNSIGNED NOT NULL (fk_pAttribs_attributeID -> groups_attributes.id)
     * @var int
     */
    public $attributeID;

    /**
     * @var bool TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     */
    public $published;

    /**
     * INT(11) NOT NULL (fk_pAttribs_personID -> persons.id -> fk: users.id)
     * @var int
     */
    public $userID;

    /**
     * TEXT
     * @var string
     */
    public $value;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::getDB();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_profile_attributes', 'id', $dbo);
    }
}