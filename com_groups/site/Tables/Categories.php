<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Joomla\Database\{DatabaseDriver, DatabaseInterface};
use THM\Groups\Adapters\Application;

/**
 * Class representing the category <=> person relations.
 */
class Categories extends Table
{
    /**
     * INT(11) NOT NULL
     * @var int
     */
    public int $categoryID;

    /**
     * INT(11) NOT NULL
     * @var int
     */
    public int $userID;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_categories', 'categoryID', $dbo);
    }
}