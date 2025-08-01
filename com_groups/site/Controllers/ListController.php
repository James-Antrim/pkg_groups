<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use Exception;
use Joomla\CMS\{Application\CMSApplication, Table\Table};
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Input\Input as CoreInput;
use THM\Groups\Adapters\{Application, Input, Text};
use THM\Groups\Tables\Ordered;

/**
 * Class performs access checks, user actions and redirection for listed resources.
 */
abstract class ListController extends Controller
{
    /**
     * The item view to redirect to for the creation of new resources
     * @var string
     */
    protected string $item = '';

    /** @inheritDoc */
    public function __construct(
        $config = [],
        ?MVCFactoryInterface $factory = null,
        ?CMSApplication $app = null,
        ?CoreInput $input = null
    )
    {
        if (empty($this->item)) {
            Application::error(501);
        }

        parent::__construct($config, $factory, $app, $input);
    }

    /**
     * Redirects to the form view for the creation of a new resource.
     * @return void
     */
    public function add(): void
    {
        $this->setRedirect("$this->baseURL&view=" . strtolower($this->item) . "&layout=edit");
    }

    /**
     * Deletes the selected resources.
     * @return void
     */
    public function delete(): void
    {
        $this->checkToken();
        $this->authorize();

        if (!$selectedIDs = Input::selectedIDs()) {
            Application::message('NO_SELECTION', Application::WARNING);

            return;
        }

        $selected = count($selectedIDs);

        $deleted = 0;

        foreach ($selectedIDs as $selectedID) {
            $table = $this->getTable();

            if ($table->delete($selectedID)) {
                $deleted++;
            }
        }

        $this->farewell($selected, $deleted, true);
    }

    /**
     * An extract for redirecting back to the list view and providing a message for the number of entries updated.
     *
     * @param   int   $selected      the number of accounts selected for processing
     * @param   int   $updated       the number of accounts changed by the calling function
     * @param   bool  $delete        whether the change affected by the calling function was a deletion
     * @param   bool  $autoRedirect  whether the function should initiate redirection automatically
     *
     * @return void
     */
    protected function farewell(int $selected = 0, int $updated = 0, bool $delete = false, bool $autoRedirect = false): void
    {
        if ($selected) {
            if ($selected === $updated) {
                $key     = $updated === 1 ? '1_' : 'X_';
                $key     .= $delete === true ? 'DELETED' : 'UPDATED';
                $message = $updated === 1 ? Text::_($key) : Text::sprintf($key, $updated);
                $type    = Application::MESSAGE;
            }
            else {
                $message = $delete ?
                    Text::sprintf('X_OF_X_DELETED', $updated, $selected) :
                    Text::sprintf('X_OF_X_UPDATED', $updated, $selected);
                $type    = Application::WARNING;
            }

            Application::message($message, $type);
        }
        elseif ($updated) {
            $key = $updated === 1 ? '1_UPDATED' : 'X_UPDATED';
            Application::message(Text::sprintf($key, $updated));
        }

        if ($autoRedirect) {
            $this->setRedirect("$this->baseURL&view=" . strtolower(Application::uqClass($this)));
        }

        try {
            $this->display();
        }
        catch (Exception $exception) {
            Application::handleException($exception);
        }
    }

    /**
     * Instances a table object corresponding to the controller's name.
     * @return Table
     */
    protected function getTable(): Table
    {
        $fqName = 'THM\\Groups\\Tables\\' . Application::ucClass($this->name);

        return new $fqName();
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     * @return  void
     */
    public function saveOrderAjax(): void
    {
        $this->checkToken();
        $this->authorizeAJAX();
        $table = $this->getTable();

        if (!property_exists($table, 'ordering')) {
            echo Text::_('501');

            return;
        }

        $ordering    = 1;
        $resourceIDs = Input::array('cid');

        foreach ($resourceIDs as $resourceID) {
            /** @var Ordered|Table $table */
            $table = $this->getTable();
            $table->load($resourceID);
            $table->ordering = $ordering;
            $table->store();
            $ordering++;
        }

        echo Text::_('Request performed successfully.');

        Application::close();
    }

    /**
     * Initiates toggling of boolean values in a column.
     *
     * @param   string  $column  the column in which the values are stored
     * @param   bool    $value   the target value
     *
     * @return void
     */
    protected function toggle(string $column, bool $value): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::selectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool($column, $selectedIDs, $value);

        $this->farewell($selected, $updated);
    }

    /**
     * Updates a boolean column for multiple entries in a
     *
     * @param   string  $column       the table column / object property
     * @param   array   $selectedIDs  the ids of the resources whose properties will be updated
     * @param   bool    $value        the value to update to
     *
     * @return int
     */
    protected function updateBool(string $column, array $selectedIDs, bool $value): int
    {
        $table = $this->getTable();

        if (!property_exists($table, $column)) {
            Application::message('TABLE_COLUMN_NONEXISTENT', Application::ERROR);

            return 0;
        }

        $total = 0;
        $value = (int) $value;

        foreach ($selectedIDs as $selectedID) {
            $table = $this->getTable();

            if ($table->load($selectedID) and $table->$column !== $value) {
                $table->$column = $value;

                if ($table->store()) {
                    $total++;
                }
            }
        }

        return $total;
    }

    /**
     * Zeros out the values of the given column
     *
     * @param   string  $table   the table where the column is located
     * @param   string  $column  the column to be zeroed
     *
     * @return bool true on success, otherwise, false
     */
    protected function zeroColumn(string $table, string $column): bool
    {
        $db = Application::database();

        // Perform one query to set the column values to 0 instead of two for search and replace
        $query = $db->getQuery(true)
            ->update($db->quoteName("#__groups_$table"))
            ->set($db->quoteName($column) . " = 0");
        $db->setQuery($query);

        return $db->execute();
    }
}