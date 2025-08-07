<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;
use THM\Groups\Adapters\Application;

/**
 * Class representing the pages table.
 */
class Pages extends Table
{
    use Incremented;

    /**
     * INT(11) UNSIGNED NOT NULL (fk_pages_contentID -> content.id)
     * @var int
     */
    public int $contentID;

    /**
     * INT(11) NOT NULL (fk_pages_userID -> users.id)
     * @var int
     */
    public int $userID;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     * @bool
     */
    public int $featured = 0;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_pages', 'id', $dbo);
    }
}