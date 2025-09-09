<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Joomla\Database\{DatabaseDriver, DatabaseInterface};
use THM\Groups\Adapters\Application;

/**
 * Class representing the person attributes table.
 */
class ProfileAttributes extends Table
{
    use Published;

    /**
     * INT(11) UNSIGNED NOT NULL (fk_pAttribs_attributeID -> groups_attributes.id)
     * @var int
     */
    public int $attributeID;

    /**
     * INT(11) NOT NULL (fk_pAttribs_personID -> persons.id -> fk: users.id)
     * @var int
     */
    public int $userID;

    /**
     * TEXT
     * @var string|null
     */
    public string|null $value;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_profile_attributes', 'id', $dbo);
    }
}