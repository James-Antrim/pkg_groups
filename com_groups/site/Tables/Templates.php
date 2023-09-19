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
 * Class representing the templates table.
 */
class Templates extends Table
{
    use Incremented, Named;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $cards;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $roles;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var bool
     */
    public $vcards;

    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::getDB();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_templates', 'id', $dbo);
    }
}