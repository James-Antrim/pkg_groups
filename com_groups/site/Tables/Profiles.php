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
 * Class representing the profiles table.
 */
class Profiles extends Table
{
    /**
     * VARCHAR(255) DEFAULT null
     * @var string
     */
    public $alias;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $content;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $editing;

    /**
     * VARCHAR(255) DEFAULT null
     * The default is null because this field will be left blank by a certain subset of accounts.
     * @var null|string
     */
    public $forenames;

    /**
     * INT(11) NOT NULL (fk_profiles_userID -> users.id)
     * @var int
     */
    public $id;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $published;

    /**
     * VARCHAR(255) NOT NULL
     * @var string
     */
    public $surnames;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::getDB();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_profiles', 'id', $dbo);
    }

    // todo overwrite the appropriate function that doesn't create a fk entry on save/store
}