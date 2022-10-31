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
use Joomla\Database\DatabaseInterface;

/**
 * Class representing the attribute types table.
 */
class AttributeTypes extends Table
{
	/**
	 * A JSON string containing the configuration of the attribute type.
	 * TEXT
	 */
	public $configuration;

	// INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
	public $id;

	// TINYINT(2) UNSIGNED NOT NULL DEFAULT 1
	public $inputID;

	// VARCHAR(100) NOT NULL
	public $name_de;
	public $name_en;

	/**
	 * @inheritDoc
	 */
	public function __construct(DatabaseInterface $dbo)
	{
		/** @var DatabaseDriver $dbo */
		parent::__construct('#__groups_attribute_types', 'id', $dbo);
	}
}