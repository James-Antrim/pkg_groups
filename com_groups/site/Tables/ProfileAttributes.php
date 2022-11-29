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
 * Class representing the profiles attributes table.
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
     * INT(11) NOT NULL (fk_pAttribs_profileID -> groups_profiles.id -> fk: users.id)
     * @var int
     */
    public $profileID;

    /**
     * @var bool TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     */
    public $published;

    /**
     * TEXT
     * @var string
     */
    public $value;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo)
    {
        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_profile_attributes', 'id', $dbo);
    }
}