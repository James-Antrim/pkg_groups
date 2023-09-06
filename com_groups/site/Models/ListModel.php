<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use Exception;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel as Base;
use Joomla\CMS\Table\Table;
use Joomla\Database\QueryInterface;
use Joomla\Registry\Registry;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Adapters\Text;
use THM\Groups\Helpers\Can;

/**
 * Model class for handling lists of items.
 * - Overrides/-writes to avoid deprecated code in the platform or promote ease of use
 * - Supplemental functions to extract common code from list models
 */
abstract class ListModel extends Base
{
    use Named;

    protected int $defaultLimit = 50;

    protected string $defaultOrdering;

    /**
     * A state object. Overrides the use of the deprecated CMSObject.
     * @var    Registry
     */
    protected $state = null;

    /**
     * @inheritdoc
     * The state is set here to prevent the use of a deprecated CMSObject as the state in the stateAwareTrait.
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        $this->state = new Registry();

        try {
            parent::__construct($config, $factory);
        } catch (Exception $exception) {
            Application::handleException($exception);
        }
    }

    /**
     * Adds a binary value filter clause for the given $query;
     *
     * @param QueryInterface $query the query to modify
     * @param string         $name  the attribute whose value to filter against
     *
     * @return void modifies the query if a binary value was delivered in the request
     */
    protected function binaryFilter(QueryInterface $query, string $name): void
    {
        $value = $this->state->get($name);

        // State default for get is null and default for request is either an empty string or not being set.
        if (!$this->isBinary($value)) {
            return;
        }

        $value = (int) $value;

        // Typical filter names are in the form 'filter.column'
        $column = strpos($name, '.') ? substr($name, strpos($name, '.') + 1) : $name;
        $column = $this->getDatabase()->quoteName($column);
        $query->where("$column = $value");
    }

    /**
     * Deletes entries.
     * @return void
     */
    abstract public function delete(): void;

    /**
     * Filters out form inputs which should not be displayed due to menu settings.
     *
     * @param Form $form the form to be filtered
     *
     * @return void modifies $form
     */
    protected function filterFilterForm(Form $form): void
    {
        // No implementation is the default implementation.
    }

    /**
     * @inheritDoc
     * Replacing the deprecated CMSObject with Registry makes the parent no longer function correctly this compensates
     * for that.
     */
    public function getActiveFilters(): array
    {
        $activeFilters = [];

        if (!empty($this->filter_fields)) {
            foreach ($this->filter_fields as $filter) {
                $filterName = 'filter.' . $filter;
                $value      = $this->state->get($filterName);

                if ($value or is_numeric($value)) {
                    $activeFilters[$filter] = $value;
                }
            }
        }

        return $activeFilters;
    }

    /**
     * Gets the filter form. Overwrites the parent to have form names analog to the view names in which they are used.
     * Also has enhanced error reporting in the event of failure.
     *
     * @param array $data     data
     * @param bool  $loadData load current data
     *
     * @return  Form|null  the form object or null if the form can't be found
     */
    public function getFilterForm($data = [], $loadData = true): ?Form
    {
        $this->filterFormName = strtolower($this->name);

        $context = $this->context . '.filter';
        $options = ['control' => '', 'load_data' => $loadData];

        try {
            return $this->loadForm($context, $this->filterFormName, $options);
        } catch (Exception $exception) {
            Application::handleException($exception);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function loadForm($name, $source = null, $options = [], $clear = false, $xpath = false): Form
    {
        if ($form = parent::loadForm($name, $source, $options, $clear, $xpath)) {
            $this->filterFilterForm($form);
        }

        return $form;
    }

    /**
     * Checks whether the given value can safely be interpreted as a binary value.
     *
     * @param mixed $value the value to be checked
     *
     * @return bool if the value can be interpreted as a binary integer
     */
    protected function isBinary(mixed $value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        $value = (int) $value;

        return !(($value > 1 or $value < 0));
    }

    /**
     * Adds a standard order clause for the given $query;
     *
     * @param QueryInterface $query the query to modify
     *
     * @return void modifies the query if a binary value was delivered in the request
     */
    protected function orderBy(QueryInterface $query): void
    {
        if ($columns = $this->state->get('list.ordering')) {
            if (preg_match('/, */', $columns)) {
                $columns = explode(',', preg_replace('/, */', ',', $columns));
            }

            $columns = $query->quoteName($columns);

            $direction = strtoupper($query->escape($this->getState('list.direction', 'ASC')));

            if (is_array($columns)) {
                $columns = implode(" $direction, ", $columns);
            }

            $query->order("$columns $direction");
        }
    }

    /**
     * @inheritDoc
     */
    protected function populateState($ordering = null, $direction = null): void
    {
        /** @var CMSApplication $app */
        $app = Application::getApplication();
        parent::populateState($ordering, $direction);


        // Receive & set filters
        $filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', [], 'array');
        foreach ($filters as $input => $value) {
            $this->state->set('filter.' . $input, $value);
        }

        $list = $app->getUserStateFromRequest($this->context . '.list', 'list', [], 'array');
        foreach ($list as $input => $value) {
            $this->state->set("list.$input", $value);
        }

        $direction    = 'ASC';
        $fullOrdering = "$this->defaultOrdering ASC";
        $ordering     = $this->defaultOrdering;

        if (!empty($list['fullordering']) and !str_contains($list['fullordering'], 'null')) {
            $pieces          = explode(' ', $list['fullordering']);
            $validDirections = ['ASC', 'DESC', ''];

            if (in_array(end($pieces), $validDirections)) {
                $direction = array_pop($pieces);
            }

            if ($pieces) {
                $ordering = implode(' ', $pieces);
            }

            $fullOrdering = "$ordering $direction";
        }

        $this->state->set('list.fullordering', $fullOrdering);
        $this->state->set('list.ordering', $ordering);
        $this->state->set('list.direction', $direction);

        if ($format = Input::getCMD('format') and $format !== 'html') {
            $limit = 0;
            $start = 0;
        } else {
            $limit = (isset($list['limit']) && is_numeric($list['limit'])) ? $list['limit'] : $this->defaultLimit;
            $start = $this->getUserStateFromRequest('limitstart', 'limitstart', 0);
            $start = ($limit != 0 ? (floor($start / $limit) * $limit) : 0);
        }

        $this->setState('list.limit', $limit);
        $this->setState('list.start', $start);
    }

    /**
     * Method to save the reordered set.
     * @return  void
     */
    public function saveorder(): void
    {
        if (!Can::administrate()) {
            echo Text::_('GROUPS_403');
            return;
        }

        $fqName = 'THM\\Groups\\Tables\\' . $this->name;

        /** @var Table $table */
        $table = new $fqName();

        if (!property_exists($table, 'ordering')) {
            echo Text::_('GROUPS_501');
            return;
        }

        $ordering    = 1;
        $resourceIDs = Input::getArray('cid');

        foreach ($resourceIDs as $resourceID) {
            $table = new $fqName();
            $table->load($resourceID);
            $table->ordering = $ordering;
            $table->store();
            $ordering++;
        }

        echo Text::_('Request performed successfully.');

        $this->cleanCache();
    }
}