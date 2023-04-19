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

use Joomla\CMS\Table\User;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;
use THM\Groups\Adapters\Application;

/**
 * Class representing the persons table.
 */
class Users extends User
{
    /**
     * INT(11) NOT NULL
     * Magic property in parent.
     * @var int
     */
    public $id;

    /**
     * VARCHAR(400) NOT NULL DEFAULT ''
     * @var string
     */
    public $name;

    /**
     * VARCHAR(150) NOT NULL DEFAULT ''
     * @var string
     */
    public $username;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * Magic property in parent.
     * @var string
     */
    public $email;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * @var string
     */
    public $password;

    /**
     * TINYINT(4) NOT NULL DEFAULT 0
     * @var bool
     */
    public $block;

    /**
     * TINYINT(4) DEFAULT 0
     * Magic property in parent.
     * @var bool
     */
    public $sendEmail;

    /**
     * DATETIME NOT NULL
     * Magic property in parent.
     * @var string
     */
    public $registerDate;

    /**
     * DATETIME
     * Magic property in parent.
     * @var string
     */
    public $lastvisitDate;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * @var string
     */
    public $activation;

    /**
     * MEDIUMTEXT NOT NULL
     * JSON String
     * @var string
     */
    public $params;

    /**
     * DATETIME
     * Magic property in parent.
     * @var string
     */
    public $lastResetTime;

    /**
     * INT(11) NOT NULL DEFAULT 0
     * Count of password resets since lastResetTime
     * @var int
     */
    public $resetCount;

    /**
     * VARCHAR(1000) DEFAULT ''
     * Two-factor authentication encrypted keys
     * Magic property in parent.
     * @var string
     */
    public $otpKey;

    /**
     * VARCHAR(1000) DEFAULT ''
     * One time emergency passwords
     * Magic property in parent.
     * @var string
     */
    public $otep;

    /**
     * TINYINT(4) DEFAULT 0
     * Require user to reset password on next login
     * @var bool
     */
    public $requireReset;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * Name of used authentication plugin
     * @var string
     */
    public $authProvider;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::getDB();

        /** @var DatabaseDriver $dbo */
        parent::__construct($dbo);
    }
}