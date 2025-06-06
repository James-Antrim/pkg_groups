<?php /** @noinspection PhpMissingFieldTypeInspection */

/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Joomla\CMS\Table\Usergroup;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;
use THM\Groups\Adapters\Application;

/**
 * Class representing the persons table.
 */
class UserGroups extends Usergroup
{
    /**
     * INT(10) UNSIGNED NOT NULL COMMENT 'Primary Key'
     * @var int
     */
    public $id;

    /**
     * INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Nested set lft.'
     * @var int
     */
    public $lft;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * @var string
     */
    public $title;

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Adjacency List Reference'
     * @var int
     */
    public $parent_id;

    /**
     * INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.'
     * @var int
     */
    public $rgt;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct($dbo);
    }
}