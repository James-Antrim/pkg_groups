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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Uri\Uri;
use Joomla\Input\Input;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;

/**
 * Controller class for attribute types.
 */
class Controller extends BaseController
{
    protected bool $backend;
    protected string $list = '';

    /**
     * @inheritDoc
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, ?CMSApplication $app = null, ?Input $input = null)
    {
        $this->backend = Application::backend();
        parent::__construct($config, $factory, $app, $input);
    }

    /**
     * Closes the form view without saving changes.
     *
     * @return void
     */
    public function cancel(): void
    {
        $base = Uri::base();
        if ($this->backend) {
            if (empty($this->list)) {
                Application::message('Form view does not have its corresponding list view coded.', Application::ERROR);
                $this->setRedirect($base);
                return;
            }

            $this->setRedirect("$base?option=com_groups&view=$this->list");
            return;
        }

        Application::error(501);
    }

    /**
     * Calls the appropriate model delete function and redirects to the appropriate list. Authorization occurs in the
     * called model.
     */
    public function delete(): void
    {
        // Check for request forgeries
        $this->checkToken();

        $fqName = 'THM\\Groups\\Models\\' . $this->name;

        $model = new $fqName();
        $model->delete();

        $this->setRedirect("index.php?option=com_groups&controller=$this->name");
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
     * An extract for redirecting back to the list view and providing a message for the number of entries updated.
     *
     * @param int $selected the number of accounts selected for processing
     * @param int $updated the number of accounts changed by the calling function
     * @param bool $delete whether the change affected by the calling function was a deletion
     * @return void
     */
    protected function farewell(int $selected = 0, int $updated = 0, bool $delete = false): void
    {
        if ($selected) {
            if ($selected === $updated) {
                $key     = $updated === 1 ? 'GROUPS_1_' : 'GROUPS_X_';
                $key     .= $delete === true ? 'DELETED' : 'UPDATED';
                $message = $updated === 1 ? Text::_($key, $updated) : Text::sprintf($key, $updated);
                $type    = Application::MESSAGE;
            } else {
                $message = $delete ?
                    Text::sprintf('GROUPS_XX_DELETED', $updated, $selected) : Text::sprintf('GROUPS_XX_UPDATED', $updated, $selected);
                $type    = Application::WARNING;
            }

            Application::message($message, $type);
        }

        $view = Application::getClass($this);
        $this->setRedirect("index.php?option=com_groups&view=$view");
    }

    /**
     * Checks against unauthenticated access and returns the id of the current user.
     *
     * @return int
     */
    protected function getUserID(): int
    {
        if (!$userID = Application::getUser()->id) {
            Application::error(401);
        }

        return $userID;
    }

    /**
     * Updates a boolean column for multiple entries in a
     * @param string $name
     * @param string $column
     * @param array $selectedIDs
     * @param bool $value
     * @return int
     */
    protected function updateBool(string $name, string $column, array $selectedIDs, bool $value): int
    {
        $fqName = "THM\Groups\Tables\\$name";
        $table  = new $fqName();

        if (!property_exists($table, $column)) {
            Application::message(Text::_('GROUPS_TABLE_COLUMN_NONEXISTENT'), Application::ERROR);

            return 0;
        }

        $total = 0;
        $value = (int) $value;

        foreach ($selectedIDs as $selectedID) {
            $table = new $fqName();

            if ($table->load($selectedID) and $table->$column !== $value) {
                $table->$column = $value;

                if ($table->store()) {
                    $total++;
                }
            }
        }

        return $total;
    }
}