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
 * Class representing the template attributes table.
 */
class TemplateAttributes extends Table
{
    use Incremented, Ordered;

    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
     * @var int
     */
    public $attributeID;

    /**
     * TINYINT(1) UNSIGNED  NOT NULL DEFAULT 0
     * @var bool
     */
    public $showLabel;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public $showIcon;

    /**
     * INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
     * @var int
     */
    public $templateID;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_template_attributes', 'id', $dbo);
    }
}