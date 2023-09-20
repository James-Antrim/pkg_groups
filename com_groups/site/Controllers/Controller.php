<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use Exception;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Uri\Uri;
use Joomla\Input\Input as JInput;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Text;
use THM\Groups\Helpers\Can;

class Controller extends BaseController
{
    /**
     * Flag for calling context.
     * @var bool
     */
    protected bool $backend;

    /**
     * The URL to redirection into this component.
     * @var string
     */
    protected string $baseURL = '';

    /**
     * @inheritDoc
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, ?CMSApplication $app = null, ?JInput $input = null)
    {
        $this->backend = Application::backend();
        $this->baseURL = $this->baseURL ?: Uri::base() . '?option=com_groups';
        parent::__construct($config, $factory, $app, $input);
    }

    /**
     * @inheritDoc
     */
    public function display($cachable = false, $urlparams = []): BaseController|Controller
    {
        if (!$view = $this->input->get('view')) {
            Application::error(501);
        }

        $format = strtoupper($this->input->get('format', 'HTML'));
        if (!class_exists("\\THM\\Groups\\Views\\$format\\$view")) {
            Application::error(503);
        }

        if (!Can::view($view)) {
            Application::error(403);
        }

        return parent::display($cachable, $urlparams);
    }

    /**
     * Execute a task by triggering a method in the derived class.
     *
     * @param string $task The task to perform. If no matching task is found, the '__default' task is executed, if
     *                     defined.
     *
     * @return  mixed   The value returned by the called method.
     * @throws  Exception
     */
    public function executeArgs(string $task, array $args): mixed
    {
        $this->task = $task;

        $task = strtolower($task);

        if (isset($this->taskMap[$task])) {
            $doTask = $this->taskMap[$task];
        } elseif (isset($this->taskMap['__default'])) {
            $doTask = $this->taskMap['__default'];
        } else {
            throw new Exception(Text::sprintf('JLIB_APPLICATION_ERROR_TASK_NOT_FOUND', $task), 404);
        }

        // Record the actual task being fired
        $this->doTask = $doTask;

        return call_user_func_array([$this, $this->task], $args);
    }
}