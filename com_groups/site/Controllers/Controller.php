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
use Joomla\Input\Input as JInput;
use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Helpers\Can;

/**
 * Controller class for attribute types.
 */
class Controller extends BaseController
{
    /**
     * Flag for calling context.
     * @var bool
     */
    protected bool $backend;

    /**
     * The URL to redirection into this component.
     * @var string
     */
    protected string $baseURL = '';

    /**
     * The list view to redirect to after completion of form view functions.
     * @var string
     */
    protected string $list = '';

    /**
     * @inheritDoc
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, ?CMSApplication $app = null, ?JInput $input = null)
    {
        $this->backend = Application::backend();
        $this->baseURL = $this->baseURL ?: Uri::base() . '?option=com_groups';
        parent::__construct($config, $factory, $app, $input);
    }

    /**
     * Saves resource data and redirects to the same view of the same resource.
     * @return void
     */
    public function apply(): void
    {
        // Check for request forgeries
        $this->checkToken();

        $fqName = 'THM\\Groups\\Models\\' . $this->name;

        $model = new $fqName();
        if ($resourceID = $model->save()) {
            if ($this->backend) {
                $this->setRedirect("$this->baseURL&view=$this->name&id=$resourceID");
                return;
            }

            Application::error(501);
        }

        $referrer = Input::getInput()->server->getString('HTTP_REFERER');
        $this->setRedirect($referrer);
    }

    /**
     * Closes the form view without saving changes.
     * @return void
     */
    public function cancel(): void
    {
        if ($this->backend) {

            // A form view without a registered list
            if (empty($this->list)) {
                Application::message('Form view does not have its corresponding list view coded.', Application::ERROR);
                $this->setRedirect(Uri::base());

                return;
            }

            $this->setRedirect("$this->baseURL&view=$this->list");

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

        if ($this->backend) {
            $this->setRedirect("$this->baseURL&view=$this->name");
            return;
        }

        Application::error(501);
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
     * @param int  $selected the number of accounts selected for processing
     * @param int  $updated  the number of accounts changed by the calling function
     * @param bool $delete   whether the change affected by the calling function was a deletion
     *
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
        $this->setRedirect("$this->baseURL&view=$view");
    }

    /**
     * Checks against unauthenticated access and returns the id of the current user.
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
     * Saves resource data and redirects to the same view of the copy resource.
     * @return void
     */
    public function save(): void
    {
        // Check for request forgeries
        $this->checkToken();
        $fqName = 'THM\\Groups\\Models\\' . $this->name;

        $model = new $fqName();

        // Success
        if ($model->save()) {

            // There is no nuance in the administrative area
            if ($this->backend and $this->list) {
                $this->setRedirect("$this->baseURL&view=$this->list");
                return;
            }

            // Not yet implemented
            Application::error(501);
        }

        $referrer = Input::getInput()->server->getString('HTTP_REFERER');
        $this->setRedirect($referrer);
    }

    /**
     * Saves resource data and redirects to the same view of the copy resource.
     * @return void
     */
    public function save2copy(): void
    {
        // Check for request forgeries
        $this->checkToken();
        $fqName = 'THM\\Groups\\Models\\' . $this->name;

        $model = new $fqName();

        // Reset the id for the new entry.
        Input::set('id', 0);

        // Success => redirect to the edit view of the new resource
        if ($newID = $model->save()) {
            $this->setRedirect("$this->baseURL&view=$this->name&id=$newID");
            return;
        }

        $referrer = Input::getInput()->server->getString('HTTP_REFERER');
        $this->setRedirect($referrer);
    }

    /**
     * Saves resource data and redirects to the same view of a new resource.
     * @return void
     */
    public function save2new(): void
    {
        // Check for request forgeries
        $this->checkToken();
        $fqName = 'THM\\Groups\\Models\\' . $this->name;

        $model = new $fqName();

        // Success => redirect to an empty edit view
        if ($model->save()) {
            $this->setRedirect("$this->baseURL&view=$this->name&id=0");
            return;
        }

        $referrer = Input::getInput()->server->getString('HTTP_REFERER');
        $this->setRedirect($referrer);
    }

    /**
     * Updates a boolean column for multiple entries in a
     *
     * @param string $name        the table class name
     * @param string $column      the table column / object property
     * @param array  $selectedIDs the ids of the resources whose properties will be updated
     * @param bool   $value       the value to update to
     *
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