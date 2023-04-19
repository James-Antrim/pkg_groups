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
 * Class representing the person <=> group & role relation.
 */
class UserUsergroupMap extends Table
{
    use Incremented;

    /**
     * INT(10) UNSIGNED NOT NULL (user_usergroup_map.group_id -> usergroups.id)
     * @var int
     */
    public $group_id;

    /**
     * INT(10) UNSIGNED NOT NULL (user_usergroup_map.user_id -> users.id)
     * @var int
     */
    public $user_id;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::getDB();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__user_usergroup_map', 'id', $dbo);
    }
}