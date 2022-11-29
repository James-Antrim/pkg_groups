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

/**
 * Class representing the template attributes table.
 */
class TemplateAttributes extends Table
{
    /**
     * @inheritDoc
     */
    public function __construct(DatabaseInterface $dbo)
    {
        /** @var DatabaseDriver $dbo */
        parent::__construct('#__thm_groups_template_attributes', 'id', $dbo);
    }
}