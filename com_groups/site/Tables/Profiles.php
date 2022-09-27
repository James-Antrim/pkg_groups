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

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

/**
 * Class representing the profiles table.
 */
class Profiles extends Table
{
	/**
	 * @inheritDoc
	 */
	public function __construct(DatabaseDriver $dbo)
    {
        parent::__construct('#__thm_groups_profiles', 'id', $dbo);
    }
}