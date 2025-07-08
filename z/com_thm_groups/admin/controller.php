<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{Application, Input};

defined('_JEXEC') or die;

/**
 * THMGroupsController class for component com_thm_groups
 */
class THM_GroupsController extends JControllerLegacy
{
    private $resource;

    /**
     * Class constructor
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @throws Exception
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        $taskParts      = explode('.', Input::task());
        $this->resource = $taskParts[0];
    }

    /**
     * Adding
     *
     * @return  void redirects to the edit view for a new resource entry
     * @throws Exception
     */
    public function add()
    {
        $input = Input::instance();
        $input->set('view', "{$this->resource}_edit");
        $input->set('id', 0);
        parent::display();
    }

    /**
     * Saves changes to the resource and redirects back to the edit view of the same resource.
     *
     * @return void
     * @throws Exception
     */
    public function apply(): void
    {
        $data       = Input::post();
        $resourceID = empty($data['id']) ? Input::id() : $data['id'];

        if ($savedID = $this->getModel($this->resource)->save()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        $input      = Input::instance();
        $resourceID = $savedID ?: $resourceID;
        $input->set('id', $resourceID);
        $input->set('view', "{$this->resource}_edit");
        parent::display();
    }

    /**
     * Saves changes to multiple resources and redirects back to resource list view.
     *
     * @return void
     * @throws Exception
     */
    public function batch(): void
    {
        if ($this->getModel($this->resource)->batch()) {
            Application::message('BATCH_SUCCESS');
        }
        else {
            Application::message('BATCH_FAIL');
        }

        Input::instance()->set('view', "{$this->resource}_manager");
        parent::display();
    }

    /**
     * Deletes selected resource entries
     *
     * @return void
     * @throws Exception
     */
    public function delete(): void
    {
        if ($this->getModel($this->resource)->delete()) {
            Application::message('DELETE_SUCCESS');
        }
        else {
            Application::message('DELETE_FAIL', Application::ERROR);
        }

        Input::instance()->set('view', "{$this->resource}_manager");
        parent::display();
    }

    /**
     * Calls delete function for picture in the model
     *
     * @return  void outputs a blank string on success, otherwise affects no change
     * @throws Exception
     */
    public function deletePicture()
    {
        $model   = $this->getModel('profile');
        $success = $model->deletePicture();

        if ($success) {
            echo '';
        }

        JFactory::getApplication()->close();
    }

    /**
     * Redirects to the edit view for the resource
     *
     * @return void
     * @throws Exception
     */
    public function edit(): void
    {
        $input = Input::instance();
        $input->set('view', "{$this->resource}_edit");
        $input->set('id', Input::selectedID());
        parent::display();
    }

    /**
     * Featured content is offered in profile menus
     *
     * @return  void
     * @throws Exception
     */
    public function feature(): void
    {
        if ($this->getModel($this->resource)->feature()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        Input::instance()->set('view', "{$this->resource}_manager");
        parent::display();

    }

    /**
     * Enables profile specific content
     *
     * @return void
     * @throws Exception
     */
    public function publishContent(): void
    {
        if ($this->getModel($this->resource)->publishContent()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        Input::instance()->set('view', "{$this->resource}_manager");
        parent::display();
    }

    /**
     * Saves changes to the resource and redirects to the list view
     *
     * @return void
     * @throws Exception
     */
    public function save(): void
    {
        if ($this->getModel($this->resource)->save()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        Input::instance()->set('view', "{$this->resource}_manager");
        parent::display();
    }

    /**
     * Saves changes to the resource and redirects to the list view
     *
     * @return void
     * @throws Exception
     */
    public function save2copy(): void
    {
        $existingID = Input::id();

        $input = Input::instance();
        $input->set('id', 0);

        if ($newID = $this->getModel($this->resource)->save()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        $input->set('id', $newID ?: $existingID);
        $input->set('view', "{$this->resource}_edit");
        parent::display();
    }

    /**
     * Saves the selected attribute and redirects to a new page
     * to create a new attribute
     *
     * @return void
     * @throws Exception
     */
    public function save2new(): void
    {
        if ($this->getModel($this->resource)->save()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        $input = Input::instance();
        $input->set('id', 0);
        $input->set('view', "{$this->resource}_edit");
        parent::display();
    }

    /**
     * Saves the crop of the selected image. As this function called via ajax it does not have the structure typical to
     * the rest of the functions of this class.
     *
     * @return  void outputs the saved image on success, otherwise affects no change
     * @throws Exception
     */
    public function saveCropped()
    {
        $model   = $this->getModel('profile');
        $success = $model->saveCropped();

        if ($success) {
            echo $success;
        }

        JFactory::getApplication()->close();
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     *
     * @return  void
     *
     * @throws Exception
     */
    public function saveOrderAjax()
    {
        $model             = $this->getModel($this->resource);
        $functionAvailable = (method_exists($model, 'saveorder'));

        if ($functionAvailable) {
            // Get the input
            $pks   = Input::selectedIDs();
            $order = array_keys($pks);

            if ($model->saveorder($pks, $order)) {
                echo "1";
            }
        }

        // Close the application
        JFactory::getApplication()->close();
    }

    /**
     * Toggles binary resource properties and redirects back to the list view
     *
     * @return void
     * @throws Exception
     */
    public function toggle(): void
    {
        if ($this->getModel($this->resource)->toggle()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        Input::instance()->set('view', "{$this->resource}_manager");
        parent::display();
    }

    /**
     * Hides the public display of user content
     *
     * @return void
     * @throws Exception
     */
    public function unpublishContent(): void
    {
        if ($this->getModel($this->resource)->unpublishContent()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        Input::instance()->set('view', "{$this->resource}_manager");
        parent::display();
    }
}
