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

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Uri\Uri;
use Joomla\Input\Input as JInput;
use THM\Groups\Adapters\{Application, Input, Text};
use THM\Groups\Helpers\Can;

class Controller extends BaseController
{
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
        $this->baseURL = $this->baseURL ?: Uri::base() . 'index.php?option=com_groups';
        parent::__construct($config, $factory, $app, $input);
    }

    /**
     * Default authorization check. Level component administrator. Override for nuance.
     * @return void
     */
    protected function authorize(): void
    {
        if (!Can::administrate()) {
            Application::error(403);
        }
    }

    /**
     * Default authorization check. Level component administrator. Override for nuance.
     * @return void
     */
    protected function authorizeAJAX(): void
    {
        if (!Can::administrate()) {
            echo Text::_('GROUPS_403');
            $this->app->close();
        }
    }

    /**
     * @inheritDoc
     */
    public function display($cachable = false, $urlparams = []): BaseController|Controller
    {
        $format = Input::getFormat();
        $view   = $this->input->get('view', 'Start');

        if (!class_exists("\\THM\\Groups\\Views\\$format\\$view")) {
            Application::error(503);
        }

        if (!Can::view($view)) {
            Application::error(403);
        }

        return parent::display($cachable, $urlparams);
    }
}