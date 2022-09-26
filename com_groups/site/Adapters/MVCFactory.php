<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Exception;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormFactoryAwareInterface;
use Joomla\CMS\MVC\Factory\MVCFactory as Base;
use Joomla\Event\DispatcherAwareInterface;
use Joomla\Input\Input;

class MVCFactory extends Base
{
	/**
	 * @inheritDoc
	 */
	public function createController($name, $prefix, array $config, CMSApplicationInterface $app, Input $input)
	{
		$name           = preg_replace('/[^A-Z0-9_]/i', '', $name);
		$className      = "THM\Groups\Controllers\\$name";
		$config['name'] = $name;
		$controller     = new $className($config, $this, $app, $input);
		$this->setFormFactoryOnObject($controller);
		$this->setDispatcherOnObject($controller);

		return $controller;
	}

	/**
	 * @inheritDoc
	 */
	public function createModel($name, $prefix = '', array $config = [])
	{
		$name      = preg_replace('/[^A-Z0-9_]/i', '', $name);
		$className = "THM\Groups\Models\\$name";
		$model     = new $className($config, $this);
		$this->setFormFactoryOnObject($model);
		$this->setDispatcherOnObject($model);

		return $model;
	}

	/**
	 * @inheritDoc
	 */
	public function createView($name, $prefix = '', $type = 'HTML', array $config = [])
	{
		$supported = ['HTML', 'JSON', 'VCF'];
		$type      = strtoupper(preg_replace('/[^A-Z0-9_]/i', '', $type));

		if (!in_array($type, $supported))
		{
			//501 error
		}

		$name      = preg_replace('/[^A-Z0-9_]/i', '', $name);
		$className = "THM\Groups\Views\\$type\\$name";
		$view      = new $className($config);
		$this->setFormFactoryOnObject($view);
		$this->setDispatcherOnObject($view);

		return $view;
	}

	/**
	 * @inheritDoc
	 */
	public function createTable($name, $prefix = '', array $config = [])
	{
		// Clean the parameters
		$name = preg_replace('/[^A-Z0-9_]/i', '', $name);

		if (!in_array($name, $this->getTableClasses()))
		{

		}

		$className = "THM\Groups\Tables\\$name";
		$dbo       = array_key_exists('dbo', $config) ? $config['dbo'] : Factory::getDbo();

		return new $className($dbo);
	}

	/**
	 * Returns a standard classname, if the class doesn't exist null is returned.
	 *
	 * @param   string  $suffix  The suffix
	 * @param   string  $prefix  The prefix
	 *
	 * @return  string|null  The class name
	 *
	 * @since   3.10.0
	 */
	protected function getClassName(string $suffix, string $prefix)
	{
		if (!$prefix)
		{
			$prefix = Factory::getApplication();
		}

		$className = 'THM\\Groups' . '\\' . ucfirst($prefix) . '\\' . $suffix;

		if (!class_exists($className))
		{
			return null;
		}

		return $className;
	}

	/**
	 * Checks for the available Table classes.
	 *
	 * @return array
	 */
	private function getTableClasses(): array
	{
		$tables = [];
		foreach (glob(JPATH_SITE . '/components/com_groups/Tables/*') as $table)
		{
			$table    = str_replace(JPATH_SITE . '/components/com_groups/Tables/', '', $table);
			$tables[] = str_replace('.php', '', $table);
		}

		return $tables;
	}

	/**
	 * Sets the internal form factory on the given object.
	 *
	 * @param   object  $object  The object
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	private function setFormFactoryOnObject($object)
	{
		if (!$object instanceof FormFactoryAwareInterface)
		{
			return;
		}

		try
		{
			$object->setFormFactory($this->getFormFactory());
		}
		catch (Exception $exception)
		{
			// Ignore it
		}
	}

	/**
	 * Sets the internal event dispatcher on the given object.
	 *
	 * @param   object  $object  The object
	 *
	 * @return  void
	 *
	 * @since   4.1.0
	 */
	private function setDispatcherOnObject($object)
	{
		if (!$object instanceof DispatcherAwareInterface)
		{
			return;
		}

		try
		{
			$object->setDispatcher($this->getDispatcher());
		}
		catch (Exception $exception)
		{
			// Ignore it
		}
	}
}