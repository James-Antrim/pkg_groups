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
 * Class representing the table mapping the group <=> role relationship.
 */
class RoleAssociations extends Table
{
    use Incremented;

    /**
     * INT(11) UNSIGNED NOT NULL (fk: groups.id)
     * @var int
     */
    public $groupID;

    /**
     * INT(11) UNSIGNED NOT NULL (fk: roles.id)
     * @var int
     */
    public $roleID;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo)
    {
        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_role_associations', 'id', $dbo);
    }

    /**
     * Gets the id of the association for the given group and role ids.
     *
     * @param int $groupID
     * @param int $roleID
     *
     * @return int|null the id if existent, otherwise null
     */
    public function getAssocID(int $groupID, int $roleID): ?int
    {
        if ($this->load(['groupID' => $groupID, 'roleID' => $roleID]))
        {
            return $this->id;
        }

        return null;
    }
}