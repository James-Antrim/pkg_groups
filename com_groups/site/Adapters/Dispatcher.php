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
 * @inheritDoc
 * Adjusts the component dispatcher which kept calling for a controller named 'display'.
 */
class Dispatcher extends ComponentDispatcher
{
    /**
     * @inheritdoc
     */
    protected $mvcFactory;

    /**
     * @inheritdoc
     */
    protected $option = 'com_groups';

    /**
     * @inheritDoc
     */
    public function dispatch(): void
    {
        // Check component access permission
        $this->checkAccess();

        $command = Input::getTask();

        // Check for a controller.task command.
        if (str_contains($command, '.')) {
            [$controller, $task] = explode('.', $command);
            $this->input->set('controller', $controller);
            $this->input->set('task', $task);
        } elseif (!$controller = Input::getController()) {
            if (Application::backend()) {
                $controller = 'Groups';
            } else {
                Application::redirect();
            }
        }

        $task = $task ?? $command;

        $config['option'] = $this->option;
        $config['name']   = $controller;
        $controller       = $this->getController($controller, ucfirst($this->app->getName()), $config);

        try {
            $controller->execute($task);
        } catch (Exception $exception) {
            Application::handleException($exception);
        }

        $controller->redirect();
    }

    /**
     * @inheritDoc
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
