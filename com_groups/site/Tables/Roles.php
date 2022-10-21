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
 * Class representing the roles table.
 */
class Roles extends Table
{
	// INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
	public $id;

	// VARCHAR(100) NOT NULL
	public $name_de;
	public $name_en;
	public $names_de;
	public $names_en;

	// INT(3) UNSIGNED NOT NULL
	public $ordering;

	// TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
	public $protected;

	/**
	 * @inheritDoc
	 */
	public function __construct(DatabaseInterface $dbo)
	{
		/** @var DatabaseDriver $dbo */
		parent::__construct('#__groups_roles', 'id', $dbo);
	}
}