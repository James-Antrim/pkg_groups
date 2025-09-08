<?php
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
    public string $activation;

    /**
     * VARCHAR(255) DEFAULT NULL
     * @var string|null
     */
    public string|null $alias = null;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * Name of used authentication plugin
     * @var string
     */
    public string $authProvider;

    /**
     * TINYINT(4) NOT NULL DEFAULT 0
     * @var int
     * @bool
     */
    public int $block;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     * @bool
     */
    public int $content;

    /**
     * INT(11) DEFAULT NULL
     * @var int|null
     */
    public int|null $converisID = null;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     * @bool
     */
    public int $editing;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * Magic property in parent.
     * @var string
     */
    public string $email;

    /**
     * VARCHAR(255) DEFAULT null
     * The default is null because this field will be left blank by a certain subset of accounts.
     * @var string|null
     */
    public string|null $forenames = null;

    /**
     * INT(11) NOT NULL
     * Magic property in parent.
     * @var int
     */
    public int $id;

    /**
     * DATETIME
     * Magic property in parent.
     * @var string|null
     */
    public string|null $lastResetTime;

    /**
     * DATETIME
     * Magic property in parent.
     * @var string|null
     */
    public string|null $lastvisitDate;

    /**
     * VARCHAR(400) NOT NULL DEFAULT ''
     * @var string
     */
    public string $name;

    /**
     * VARCHAR(1000) DEFAULT ''
     * One time emergency passwords
     * Magic property in parent.
     * @var string
     */
    public string $otep;

    /**
     * VARCHAR(1000) DEFAULT ''
     * Two-factor authentication encrypted keys
     * Magic property in parent.
     * @var string
     */
    public string $otpKey;

    /**
     * MEDIUMTEXT NOT NULL
     * JSON String
     * @var string|null
     */
    public string|null $params = '';

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * @var string
     */
    public string $password;

    /**
     * DATETIME NOT NULL
     * Magic property in parent.
     * @var string|null
     */
    public string|null $registerDate;

    /**
     * TINYINT(4) DEFAULT 0
     * Require user to reset password on next login
     * @var int
     */
    public int $requireReset;

    /**
     * INT(11) NOT NULL DEFAULT 0
     * Count of password resets since lastResetTime
     * @var int
     */
    public int $resetCount;

    /**
     * TINYINT(4) DEFAULT 0
     * Magic property in parent.
     * @var int
     * @bool
     */
    public int $sendEmail;

    /**
     * VARCHAR(255) DEFAULT NULL
     * @var string|null
     */
    public string|null $surnames = null;

    /**
     * VARCHAR(150) NOT NULL DEFAULT ''
     * @var string
     */
    public string $username;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct($dbo);
    }
}