<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      Dennis Priefer, <dennis.priefer@mni.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

use JetBrains\PhpStorm\NoReturn;
use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Helpers\Users;

require_once HELPERS . 'profiles.php';

/**
 * Site controller class for component com_thm_groups
 *
 * Main controller for the site section of the component
 * @link        www.thm.de
 */
class THM_GroupsController extends JControllerLegacy
{
    private int $profileID;

    private string $resource;

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
        $task           = JFactory::getApplication()->input->get('task', '');
        $taskParts      = explode('.', $task);
        $this->resource = $taskParts[0];
    }

    /**
     * Saves changes to the profile and returns to the edit view
     *
     * @return  void
     * @throws Exception
     */
    public function apply(): void
    {
        $this->preProcess();

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_thm_groups/models');
        $model   = JModelLegacy::getInstance('profile', 'THM_GroupsModel');
        $success = $model->save();

        $app = JFactory::getApplication();

        if ($success) {
            $app->enqueueMessage(JText::_('COM_THM_GROUPS_SAVE_SUCCESS'));
        }
        else {
            $app->enqueueMessage(JText::_('COM_THM_GROUPS_SAVE_FAIL'), 'error');
        }

        Application::redirect(THM_GroupsHelperRouter::build(['profileID' => $this->profileID, 'view' => 'profile_edit']));
    }

    /**
     * Saves changes to the profile and returns to the edit view
     *
     * @return  void
     * @throws Exception
     */
    public function cancel(): void
    {
        $this->preProcess();
        Application::redirect(THM_GroupsHelperRouter::build(['profileID' => $this->profileID, 'view' => 'profile']));
    }

    /**
     * Checks in content
     *
     * @return void
     * @throws Exception
     */
    public function checkin(): void
    {
        if ($this->getModel($this->resource)->checkin()) {
            Application::message('SAVE_SUCCESS');
        }
        else {
            Application::message('SAVE_FAIL', Application::ERROR);
        }

        Application::redirect(Input::referrer());
    }

    /**
     * Calls delete function for picture in the model
     *
     * @return  void outputs a blank string on success, otherwise affects no change
     * @throws Exception
     */
    #[NoReturn] public function deletePicture(): void
    {
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_thm_groups/models');
        $model   = JModelLegacy::getInstance('profile', 'THM_GroupsModel');
        $success = $model->deletePicture();

        echo empty($success) ? 'error' : '';

        Application::close();
    }

    /**
     * Sets object variables and checks access rights. Redirects on insufficient access.
     *
     * @return  void
     * @throws Exception
     */
    private function preProcess(): void
    {
        $input = JFactory::getApplication()->input;
        $data  = $input->get('jform', [], 'array');

        $this->profileID = $data['profileID'];

        if (!Users::editing($this->profileID)) {
            JFactory::getApplication()->enqueueMessage(JText::_('JLIB_RULES_NOT_ALLOWED'), 'error');
            $isPublished  = Users::published($this->profileID);
            $profileAlias = Users::alias($this->profileID);
            if ($isPublished and $profileAlias) {
                Application::redirect(THM_GroupsHelperRouter::build(['profileID' => $this->profileID]));
            }

            Application::redirect();
        }
    }

    /**
     * Saves changes to the profile and redirects to the profile on success
     *
     * @return  void
     * @throws Exception
     */
    public function save(): void
    {
        $this->preProcess();

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_thm_groups/models');
        $model   = JModelLegacy::getInstance('profile', 'THM_GroupsModel');
        $success = $model->save();

        $app    = JFactory::getApplication();
        $params = ['profileID' => $this->profileID];

        if ($success) {
            $app->enqueueMessage(JText::_('COM_THM_GROUPS_SAVE_SUCCESS'));
            $params['view'] = 'profile';
        }
        else {
            $app->enqueueMessage(JText::_('COM_THM_GROUPS_SAVE_FAIL'), 'error');
            $params['view'] = 'profile_edit';
        }

        Application::redirect(THM_GroupsHelperRouter::build($params));
    }

    /**
     * Saves the cropped image and outputs the saved image on success.
     *
     * @return  void outputs the saved image on success, otherwise affects no change
     * @throws Exception
     */
    #[NoReturn] public function saveCropped(): void
    {
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_thm_groups/models');
        $model   = JModelLegacy::getInstance('profile', 'THM_GroupsModel');
        $success = $model->saveCropped();

        if ($success !== false) {
            echo $success;
        }

        Application::close();
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     *
     * @return  void
     *
     * @throws Exception
     */
    #[NoReturn] public function saveOrderAjax(): void
    {
        $pks   = Input::selectedIDs();
        $order = array_keys($pks);

        if ($this->getModel($this->resource)->saveorder($pks, $order)) {
            echo "1";
        }

        Application::close();
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

        Application::redirect(Input::referrer());
    }
}
