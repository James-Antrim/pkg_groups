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
    use Published;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * @var string
     */
    public $activation;

    /**
     * VARCHAR(255) DEFAULT NULL
     * @var null|string
     */
    public $alias;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * Name of used authentication plugin
     * @var string
     */
    public $authProvider;

    /**
     * TINYINT(4) NOT NULL DEFAULT 0
     * @var bool
     */
    public $block;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $content;

    /**
     * INT(11) DEFAULT NULL
     * @var int
     */
    public $converisID;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $editing;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * Magic property in parent.
     * @var string
     */
    public $email;

    /**
     * VARCHAR(255) DEFAULT null
     * The default is null because this field will be left blank by a certain subset of accounts.
     * @var null|string
     */
    public $forenames;

    /**
     * INT(11) NOT NULL
     * Magic property in parent.
     * @var int
     */
    public $id;

    /**
     * DATETIME
     * Magic property in parent.
     * @var string
     */
    public $lastResetTime;

    /**
     * DATETIME
     * Magic property in parent.
     * @var string
     */
    public $lastvisitDate;

    /**
     * VARCHAR(400) NOT NULL DEFAULT ''
     * @var string
     */
    public $name;

    /**
     * VARCHAR(1000) DEFAULT ''
     * One time emergency passwords
     * Magic property in parent.
     * @var string
     */
    public $otep;

    /**
     * VARCHAR(1000) DEFAULT ''
     * Two-factor authentication encrypted keys
     * Magic property in parent.
     * @var string
     */
    public $otpKey;

    /**
     * MEDIUMTEXT NOT NULL
     * JSON String
     * @var string
     */
    public $params;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * @var string
     */
    public $password;

    /**
     * DATETIME NOT NULL
     * Magic property in parent.
     * @var string
     */
    public $registerDate;

    /**
     * TINYINT(4) DEFAULT 0
     * Require user to reset password on next login
     * @var int
     */
    public $requireReset;

    /**
     * INT(11) NOT NULL DEFAULT 0
     * Count of password resets since lastResetTime
     * @var int
     */
    public $resetCount;

    /**
     * TINYINT(4) DEFAULT 0
     * Magic property in parent.
     * @var bool
     */
    public $sendEmail;

    /**
     * VARCHAR(255) DEFAULT NULL
     * @var null|string
     */
    public $surnames;

    /**
     * VARCHAR(150) NOT NULL DEFAULT ''
     * @var string
     */
    public $username;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct($dbo);
    }
}