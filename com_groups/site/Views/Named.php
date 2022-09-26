<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views;

use THM\Groups\Helpers\Component;

trait Named
{
	/**
	 * The name of the view
	 *
	 * @var    string
	 */
	protected $_name;

	/**
	 * Method to get the object name. Original overwrite to avoid Joomla thrown exception. Currently also used for
	 * non-HTML hierarchy views.
	 *
	 * @return  string  The name of the model
	 */
	public function getName(): string
	{
		if (empty($this->_name))
		{
			$this->_name = Component::getClass($this);
		}

		return $this->_name;
	}
}