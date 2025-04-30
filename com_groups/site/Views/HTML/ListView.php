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

use Joomla\CMS\MVC\View\ListView as Base;
use stdClass;
use THM\Groups\Adapters\{Application, Text, Toolbar};
use THM\Groups\Helpers\Can;

/**
 * View class for handling lists of items.
 * - Overrides/-writes to avoid deprecated code in the platform or promote ease of use
 * - Supplemental functions to extract common code from list models
 */
abstract class ListView extends Base
{
    use Configured;
    use Titled;

    protected const NONE = -1;

    public bool $allowBatch = false;
    public string $empty = '';
    public array $headers = [];
    protected string $layout = 'list';
    /** @var array The default text for an empty result set. */
    public array $todo = [];

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

        $this->configure();
    }

    /** @inheritDoc */
    protected function addToolBar(): void
    {
        // MVC name identity is now the internal standard
        $controller = $this->getName();
        $this->title(strtoupper($controller));

        if (Application::backend() and Can::administrate()) {
            $toolbar = Toolbar::getInstance();
            $toolbar->preferences('com_groups');
        }

        //ToolbarHelper::help('Users:_Groups');
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
    abstract protected function completeItem(int $index, stdClass $item, array $options = []): void;

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

    /** @inheritDoc */
    public function display($tpl = null): void
    {
        $this->authorize();

        //HTML::stylesheet(Uri::root() . 'components/com_groups/css/global.css');

        parent::display($tpl);
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
                if ($filter and $filter !== self::NONE) {
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

        // All the tools are now there.
        $this->initializeColumns();
        $this->completeItems();
    }
}