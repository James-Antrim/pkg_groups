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
use Joomla\CMS\MVC\View\ListView as Core;
use Joomla\CMS\Uri\Uri;
use stdClass;
use THM\Groups\Adapters\{Application, Document, Input, Text, Toolbar};
use THM\Groups\Controllers\Controller;
use THM\Groups\Helpers\Can;

/** @inheritDoc */
abstract class ListView extends Core
{
    use Configured;
    use Tasked;
    use Titled;

    /** @var bool the value of the relevant authorizations in context. */
    public bool $allowBatch = false;
    protected string $baseURL = '';
    /** @var string The default text for an empty result set. */
    public string $empty = '';
    /** @var array The header information to display indexed by the referenced attribute. */
    public array $headers = [];
    protected string $layout = 'list';
    /** @var array the open items. */
    public array $toDo = [];

    /**
     * Constructor
     *
     * @param   array  $config  An optional associative array of configuration settings.
     */
    public function __construct(array $config)
    {
        $this->option = 'com_groups';

        // If this is not explicitly set going in Joomla will default to default without looking at the object property value.
        $config['layout'] = $this->layout;

        parent::__construct($config);

        $this->baseURL = $this->baseURL ?: Uri::base() . 'index.php?option=com_groups';
        $this->configure();
    }

    /**
     * Adds the add and delete buttons to the toolbar.
     * @return void
     */
    protected function addBasicButtons(): void
    {
        $this->addAdd();
        $this->addDelete();
    }

    /**
     * Adds a button to delete resources.
     */
    protected function addAdd(): void
    {
        $controller = $this->getName();
        $toolbar    = Toolbar::instance();
        $toolbar->addNew("$controller.add");
    }

    /**
     * Adds a button to delete resources.
     */
    protected function addDelete(): void
    {
        $controller = $this->getName();
        $toolbar    = Toolbar::instance();
        $toolbar->delete("$controller.delete")->message(Text::_('DELETE_CONFIRM'))->listCheck(true);
    }

    /** @inheritDoc */
    protected function addToolBar(): void
    {
        $this->toDo[] = 'Add help to the toolbar dynamically.';
        // MVC name identity is now the internal standard
        $controller = $this->getName();
        $this->title(strtoupper($controller));

        if (Application::backend() and Can::administrate()) {
            $toolbar = Toolbar::instance();
            $toolbar->preferences('com_groups');
        }
    }

    /**
     * Checks user authorization and initiates redirects accordingly. General access is now regulated through the
     * below-mentioned functions. Views with public access can be further restricted here as necessary.
     * @return void
     * @see Controller::display(), Can::view()
     */
    protected function authorize(): void
    {
        // See comment.
    }

    /**
     * Readies an item for output.
     *
     * @param   int       $index  the current iteration number
     * @param   stdClass  $item   the current item being iterated
     * @param   array     $options
     *
     * @return void
     */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        // Overridable as needed.
    }

    /**
     * Processes items for output.
     *
     * @param   array  $options
     *
     * @return void
     */
    protected function completeItems(array $options = []): void
    {
        $index = 0;

        foreach ($this->items as $item) {
            $this->completeItem($index, $item, $options);
            $index++;
        }
    }

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null): void
    {
        $this->authorize();

        try {
            parent::display($tpl);
        }
        catch (Exception $exception) {
            Application::error($exception->getCode(), $exception->getMessage());
        }
    }

    /**
     * Checks whether the list items have been filtered.
     * @return bool true if the results have been filtered, otherwise false
     */
    protected function filtered(): bool
    {
        if ($filters = (array) $this->state->get('filter')) {
            // Search for filter value which has been set
            foreach ($filters as $filter) {
                // Empty values or none value
                if ($filter and $filter !== Input::NONE) {
                    return true;
                }

                // Positive empty values must be explicitly tested
                if ($filter === 0 or $filter === '0') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Initializes the headers after the form and state properties have been initialized.
     * @return void
     */
    abstract protected function initializeColumns(): void;

    /** @inheritDoc */
    protected function initializeView(): void
    {
        // TODO: check submenu viability

        parent::initializeView();

        $this->empty = $this->empty ?: Text::_('EMPTY_RESULT_SET');

        $this->subTitle();
        $this->supplement();
        $this->initializeColumns();
        $this->completeItems();
        $this->modifyDocument();
    }

    /**
     * Adds scripts and stylesheets to the document.
     */
    protected function modifyDocument(): void
    {
        Document::script('cacheMiss');
        Document::style('list');
    }
}