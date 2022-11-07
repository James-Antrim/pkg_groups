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
 * Class representing the attributes table.
 */
class Attributes extends Table
{
	/**
	 * A JSON string containing the configuration of the attribute type.
	 * TEXT
	 */
	public $configuration;

	// VARCHAR(255) NOT NULL DEFAULT ''
	public $icon;

	// INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
	public $id;

	// VARCHAR(100) NOT NULL
	public $label_de;
	public $label_en;

	// INT(3) UNSIGNED NOT NULL DEFAULT 0
	public $ordering;

	// TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
	public $required;

	// INT(11) UNSIGNED NOT NULL
	public $typeID;

	// INT(10) UNSIGNED DEFAULT 1
	public $viewLevelID;

	/**
	 * @inheritDoc
	 */
	public function __construct(DatabaseInterface $dbo)
	{
		/** @var DatabaseDriver $dbo */
		parent::__construct('#__groups_attribute_types', 'id', $dbo);
	}
}