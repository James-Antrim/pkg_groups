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

use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\MVC\View\ListView as Base;
use Joomla\CMS\Uri\Uri;
use THM\Groups\Adapters\{Application, HTML};
use THM\Groups\Views\Named;

/**
 * View class for handling lists of items.
 * - Overrides/-writes to avoid deprecated code in the platform or promote ease of use
 * - Supplemental functions to extract common code from list models
 */
abstract class ListView extends Base
{
    use Configured, Named;

    const NONE = -1;

    protected $_layout = 'list';
    public bool $allowBatch = false;
    public array $headers = [];
    public array $todo = [];

    /**
     * Constructor
     *
     * @param array $config An optional associative array of configuration settings.
     */
    public function __construct(array $config)
    {
        $this->option = 'com_groups';

        // If this is not explicitly set going in Joomla will default to default without looking at the object property value.
        $config['layout'] = $this->_layout;

        parent::__construct($config);

        $this->configure();
    }

    /**
     * Add the page title and toolbar.
     * @return  void
     */
    protected function addToolbar(): void
    {
        $titleKey = 'GROUPS_' . strtoupper($this->_name);

        ToolbarHelper::title(Text::_($titleKey), '');

        /*if (Can::administrate()) {
            ToolbarHelper::preferences('com_groups');
            //ToolbarHelper::divider();
        }*/

        //ToolbarHelper::help('Users:_Groups');
    }

    /**
     * Supplements item information for display purposes as necessary.
     */
    protected function completeItems()
    {
        // Filled by inheriting classes as necessary.
    }

    /**
     * @inheritDoc
     */
    public function display($tpl = null): void
    {
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            Application::message(implode("\n", $errors), 'error');
            Application::redirect('', 500);
        }

        HTML::stylesheet(Uri::root() . 'components/com_groups/css/global.css');

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
     * Initializes the headers after the view has been initialized.
     */
    abstract protected function initializeHeaders(): void;

    /**
     * @inheritDoc
     */
    protected function initializeView(): void
    {
        // TODO: check submenu viability

        parent::initializeView();

        // All the tools are now there.
        $this->initializeHeaders();
        $this->completeItems();
    }
}