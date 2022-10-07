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
use Joomla\CMS\Dispatcher\ComponentDispatcher;
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Base class for a Joomla Component Dispatcher
 *
 * Dispatchers are responsible for checking ACL of a component if appropriate and
 * choosing an appropriate controller (and if necessary, a task) and executing it.
 *
 * @since  4.0.0
 */
class Dispatcher extends ComponentDispatcher
{
	/**
	 * The MVC factory
	 *
	 * @var  MVCFactory
	 */
	protected $mvcFactory;

	/**
	 * The URL option for the component.
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $option = 'com_groups';

	/**
	 * @inheritDoc
	 */
	public function dispatch()
	{
		// Check component access permission
		$this->checkAccess();

		$command = $this->input->getCmd('task', 'display');

		// Check for a controller.task command.
		if (strpos($command, '.') !== false)
		{
			[$controller, $task] = explode('.', $command);
			$this->input->set('controller', $controller);
			$this->input->set('task', $task);
		}
		else
		{
			$controller = $this->input->get('controller', 'Controller');
			$task       = $command;
		}

		$config['option'] = $this->option;
		$config['name']   = $controller;
		$controller       = $this->getController($controller, ucfirst($this->app->getName()), $config);

		try
		{
			$controller->execute($task);
		}
		catch (Exception $exception)
		{
			Component::error(500);
		}

		$controller->redirect();
	}

	/**
	 * Get a controller from the component
	 *
	 * @param   string  $name    Controller name
	 * @param   string  $client  Optional client (like Administrator, Site etc.)
	 * @param   array   $config  Optional controller config
	 *
	 * @return  BaseController
	 *
	 * @since   4.0.0
	 */
	public function getController(string $name, string $client = '', array $config = []): BaseController
	{
		// Set up the client
		$client = $client ?: ucfirst($this->app->getName());

		// Get the controller instance
		return $this->mvcFactory->createController(
			$name,
			$client,
			$config,
			$this->app,
			$this->input
		);
	}
}
