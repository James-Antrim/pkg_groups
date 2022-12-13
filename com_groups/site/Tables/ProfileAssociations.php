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
 * Class representing the profile <=> group & role relation.
 */
class ProfileAssociations extends Table
{
    use Incremented;

    /**
     * INT(11) UNSIGNED NOT NULL (fk_pAssocs_assocID -> groups_role_associations.id)
     * @var int
     */
    public $assocID;

    /**
     * INT(11) NOT NULL (fk_pAssocs_profileID -> groups_profiles.id -> fk: users.id)
     * @var int
     */
    public $profileID;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::getDB();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_profile_associations', 'id', $dbo);
    }
}