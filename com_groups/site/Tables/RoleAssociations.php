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

use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;
use THM\Groups\Adapters\Application;

/**
 * Class representing the table mapping the group <=> role relationship.
 */
class RoleAssociations extends Table
{
    /**
     * INT(11) UNSIGNED NOT NULL (fk: user_usergroups_map.id)
     * @var int
     */
    public int $mapID;

    /**
     * INT(11) UNSIGNED NOT NULL (fk: roles.id)
     * @var int
     */
    public int $roleID;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_role_associations', 'id', $dbo);
    }
}