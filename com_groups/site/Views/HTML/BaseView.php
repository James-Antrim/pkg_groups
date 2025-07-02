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
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\View\HtmlView;
use THM\Groups\Adapters\Application;

/**
 * View class for setting general context variables.
 */
abstract class BaseView extends HtmlView
{
    use Configured;
    use Titled;


    /**
     * The name of the layout to use during rendering.
     * @var string
     */
    protected string $layout = 'default';

    /**
     * Inheritance stems from BaseDatabaseModel, not BaseModel. BaseDatabaseModel is higher in the Joomla internal
     * hierarchy used for Joomla Admin, Form, List, ... models which in turn are the parents for the Organizer abstract
     * classes of similar names.
     * @var BaseDatabaseModel
     */
    protected BaseDatabaseModel $model;

    public bool $useCoreUI = true;

    /** @inheritDoc */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->configure();
    }

    /**
     * Execute and display a template script.
     * Wrapper for parent to avoid dumping the responsibility for exception handling onto inheriting classes.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     *
     * @see     HtmlView::display(), HtmlView::loadTemplate()
     */
    public function display($tpl = null): void
    {
        try {
            parent::display($tpl);
        }
        catch (Exception $exception) {
            Application::handleException($exception);
        }
    }

    /** @inheritDoc */
    public function getLayout(): string
    {
        return $this->layout ?: strtolower($this->_name);
    }

    /**
     * Modifies the document by adding script and style declarations.
     *
     * @return void modifies the document
     */
    public function modifyDocument(): void
    {
        return;
    }

    /** @inheritDoc */
    public function setModel($model, $default = false): BaseDatabaseModel
    {
        $this->model = parent::setModel($model, $default);

        return $this->model;
    }
}