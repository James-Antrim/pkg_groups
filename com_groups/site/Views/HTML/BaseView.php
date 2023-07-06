<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Exception;
use Joomla\CMS\MVC\View\HtmlView;
use THM\Groups\Adapters\Application;
use THM\Groups\Views\Named;

/**
 * View class for setting general context variables.
 */
abstract class BaseView extends HtmlView
{
    use Named;

    public bool $backend;
    protected string $layout;
    public bool $mobile;

    /**
     * @inheritDoc
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->_basePath = JPATH_SITE . '/components/com_groups';
        $this->_name     = $this->getName();

        // Set the default template search path
        $this->_setPath('helper', $this->_basePath . '/Helpers');
        $this->_setPath('layout', $this->_basePath . '/Layouts');
        $this->_setPath('template', $this->_basePath . '/templates');

        $this->backend = Application::backend();
        $this->mobile  = Application::mobile();
    }

    /**
     * Execute and display a template script. Does not dump the responsibility for exception handling onto inheriting classes.
     *
     * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null): void
    {
        try {
            parent::display($tpl);
        } catch (Exception $exception) {
            Application::handleException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getLayout(): string
    {
        return $this->layout ?: strtolower($this->_name);
    }
}