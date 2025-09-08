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

use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;
use THM\Groups\Adapters\Application;

/**
 * Class representing the template attributes table.
 */
class TemplateAttributes extends Table
{
    use Ordered;

    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
     * @var int
     */
    public int $attributeID;

    /**
     * TINYINT(1) UNSIGNED  NOT NULL DEFAULT 0
     * @var int
     * @bool
     */
    public int $showLabel;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public int $showIcon;

    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
     * @var int
     */
    public int $templateID;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_template_attributes', 'id', $dbo);
    }
}