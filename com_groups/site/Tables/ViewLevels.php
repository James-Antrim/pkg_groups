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

use Joomla\CMS\Table\ViewLevel;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;
use THM\Groups\Adapters\Application;

/**
 * Class representing the persons table.
 */
class ViewLevels extends ViewLevel
{
    use Ordered;

    /**
     * INT(10) UNSIGNED NOT NULL AUTOINCREMENT COMMENT 'Primary Key'
     * @var int
     */
    public int $id;

    /**
     * VARCHAR(5120) NOT NULL COMMENT 'JSON encoded access control.'
     * @var string
     */
    public string $rules;

    /**
     * VARCHAR(100) NOT NULL DEFAULT ''
     * @var string
     */
    public string $title;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct($dbo);
    }
}