<?php
/**
 * @package     THM\Groups\Models
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace THM\Groups\Models;

use THM\Groups\Adapters\Application;

trait Named
{
	/**
	 * Context string for the model type.  This is used to handle uniqueness
	 * when dealing with the getStoreId() method and caching data structures.
	 *
	 * @var    string
	 */
	protected $context;

	/**
	 * The model (base) name
	 *
	 * @var    string
	 */
	protected $name;

	/**
	 * Method to get the model name
	 *
	 * The model name. By default parsed using the classname or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @return  string  The name of the model
	 */
	public function getName(): string
	{
		if (empty($this->name))
		{
			$this->name    = Application::getClass($this);
			$this->context = strtolower('com_groups.' . $this->getName());
		}

		return $this->name;
	}
}