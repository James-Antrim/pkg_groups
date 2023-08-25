<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Exception;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\View\HtmlView;
use THM\Groups\Adapters\Application;
use THM\Groups\Views\Named;

class Base extends HtmlView
{
    use Configured, Named;

    /**
     * Joomla hard coded default value.
     *
     * @var string
     */
    protected string $layout = 'default';

    public array $todo = [];

    /**
     * Constructor
     *
     * @param array $config An optional associative array of configuration settings.
     */
    public function __construct(array $config)
    {
        // If this is not explicitly set going in Joomla will default to default without looking at the object property value.
        $config['layout'] = $this->layout;

        parent::__construct($config);

        $this->canDo = ContentHelper::getActions('com_users');
        $this->configure();
    }

    /**
     * Execute and display a template script. Wraps standard execution with exception handling.
     *
     * @param string $tpl unused
     *
     * @return  void
     */
    public function display($tpl = null): void
    {
        try {
            parent::display();
        } catch (Exception $exception) {
            Application::handleException($exception);
        }
    }
}