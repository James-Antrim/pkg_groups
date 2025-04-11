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

use Joomla\CMS\Access\Access;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\UserHelper;
use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Helpers\{Can, Groups};
use Joomla\Component\Users\Administrator\Model\UserModel;
use THM\Groups\Tables\{RoleAssociations, Users as UT, UserUsergroupMap as UUGM};

class Users extends ListController
{
    private const ADD = 1, RESET = 1, REMOVE = 0, STOP = 0;

    // The values are redundant, but understandable
    private const ACTIONS = [self::ADD, self::REMOVE, self::RESET, self::STOP];

    /**
     * @inheritdoc
     */
    protected string $item = 'User';

    /**
     * Activates the selected users.
     * @return void
     */
    public function activate(): void
    {
        $this->checkToken();
        $this->authorize();

        $app         = Application::instance();
        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = 0;

        PluginHelper::importPlugin('user');

        // Access checks.
        foreach ($selectedIDs as $selectedID) {
            $users = new UT();

            if ($users->load($selectedID)) {
                $current = $users->getProperties();

                if (empty($users->activation)) {
                    continue;
                }

                $users->block      = 0;
                $users->activation = '';

                // Trigger the before save event.
                $result = $app->triggerEvent('onUserBeforeSave', [$current, false, $users->getProperties()]);

                if (in_array(false, $result, true)) {
                    continue;
                }

                // Store the table.
                if (!$users->store()) {
                    Application::message($users->getError(), Application::ERROR);
                    continue;
                }

                // Fire the after save event
                $app->triggerEvent('onUserAfterSave', [$users->getProperties(), false, true, null]);
            }
        }

        $this->farewell($selected, $updated);
    }

    protected function authorize(): void
    {
        $authorized = match (debug_backtrace()[1]) {
            'activate', 'disableContent', 'disableEditing', 'enableContent', 'enableEditing', 'toggleBlock'
            => Can::changeState(),
            'batch' => Can::batchProcess(),
            'delete' => Can::delete(),
            default => Can::administrate()
        };

        if (!$authorized) {
            Application::error(403);
        }
    }

    /**
     * @return void
     */
    public function batch(): void
    {
        $this->checkToken();
        $this->authorize();

        $user  = Application::getUser();
        $super = $user->get('isRoot');

        $batchItems  = Input::getBatchItems();
        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);

        $actionValue = $batchItems->get('action');
        $batchMap    = false;
        $groupID     = (int) $batchItems->get('groupID');
        $roleID      = 0;

        if (is_numeric($actionValue) and in_array((int) $actionValue, self::ACTIONS) and $groupID) {
            /**
             * Joomla programmed a hard error if a non-super user batch processed a superuser at all. I am only going
             * to throw an error if the batch process is to add or remove a group assignment which would add or remove
             * super authorization.
             */
            if (!$super and Access::checkGroup($groupID, 'core.admin')) {
                Application::message('GROUPS_CANNOT_BATCH_SUPER_ADMIN', Application::ERROR);
                $this->farewell($selected);
            }

            $actionValue = (int) $actionValue;
            $batchMap    = true;
            $roleID      = (int) $batchItems->get('roleID');
        }

        $resetValue = $batchItems->get('reset');
        $batchReset = false;
        $resetting  = false;

        if (is_numeric($resetValue) and in_array((int) $resetValue, self::ACTIONS)) {
            $resetValue = (int) $resetValue;
            $batchReset = true;
            $resetting  = $resetValue === self::RESET;
        }

        if (!$batchMap and !$batchReset) {
            Application::message('GROUPS_NO_ACTION_CHOSEN', Application::NOTICE);
            $this->farewell();
        }

        $updated = 0;

        foreach ($selectedIDs as $selectedID) {
            if ($batchReset) {
                if ($resetting) {
                    if (!$super and Access::check($selectedID, 'core.admin')) {
                        Application::message('GROUPS_CANNOT_BATCH_SUPER_ADMIN', Application::WARNING);
                        continue;
                    }

                    if ($user->id === $selectedID) {
                        Application::message('GROUPS_CANNOT_BATCH_SELF', Application::WARNING);
                        continue;
                    }
                }

                $reset = $this->reset($selectedID, $resetValue);
            }
            else {
                $reset = true;
            }

            if ($batchMap) {
                $mapped = $this->map($selectedID, $actionValue, $groupID, $roleID);
            }
            else {
                $mapped = true;
            }


            if ($mapped and $reset) {
                $updated++;
            }
        }

        $this->farewell($selected, $updated);
    }

    /**
     * Blocks the selected users.
     * @return void
     */
    public function block(): void
    {
        $this->toggleBlock(true);
    }

    /**
     * Deletes user accounts.
     * @return void
     * @see UserModel::delete()
     */
    public function delete(): void
    {
        $this->checkToken();
        $this->authorize();

        /** @var CMSApplication $app */
        $app         = Application::instance();
        $selectedIDs = Input::getSelectedIDs();
        $user        = Application::getUser();
        $super       = $user->get('isRoot');

        $selected = count($selectedIDs);
        $deleted  = 0;

        PluginHelper::importPlugin('user');

        foreach ($selectedIDs as $selectedID) {

            if ($selectedID === $user->id) {
                Application::message('GROUPS_CANNOT_DELETE_SELF', Application::ERROR);
                continue;
            }

            if (!$super and Access::check($selectedID, 'core.admin')) {
                Application::message('GROUPS_CANNOT_DELETE_SUPER_ADMIN', Application::WARNING);
                continue;
            }

            $users = new UT();

            if ($users->load($selectedID)) {
                $properties = $users->getProperties();

                $app->triggerEvent('onUserBeforeDelete', [$properties]);

                if (!$users->delete()) {
                    Application::message($users->getError(), Application::ERROR);
                    continue;
                }

                $deleted++;

                $app->triggerEvent('onUserAfterDelete', [$properties, true, '']);
            }
        }

        $this->farewell($selected, $deleted, true);
    }

    /**
     * Disables content management for the selected users.
     * @return void
     * @todo integrate component parameters
     * @todo integrate category suppression
     */
    public function disableContent(): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool('content', $selectedIDs, false);

        // todo add category suppression

        $this->farewell($selected, $updated);
    }

    /**
     * Disables profile editing for the selected users.
     * @return void
     * @todo integrate component parameters
     */
    public function disableEditing(): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool('editing', $selectedIDs, false);

        $this->farewell($selected, $updated);
    }

    /**
     * Enables content management for the selected users.
     * @return void
     * @todo integrate component parameters
     * @todo integrate category creation
     */
    public function enableContent(): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool('content', $selectedIDs, true);

        // todo add category creation

        $this->farewell($selected, $updated);
    }

    /**
     * Enables profile editing for the selected users.
     * @return void
     * @todo integrate component parameters
     */
    public function enableEditing(): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool('editing', $selectedIDs, true);

        $this->farewell($selected, $updated);
    }

    /**
     * Filters selected accounts for the current user.
     *
     * @param   array  $selectedIDs
     *
     * @return array
     */
    private function filterSelected(array $selectedIDs): array
    {
        if (!$userID = Application::getUser()->id) {
            Application::error(401);
        }

        return in_array($userID, $selectedIDs) ? [$userID] : [];
    }

    /**
     * Maps / removes mappings of the given user id to the given groups & roles.
     *
     * @param   int  $userID   the id of the user to (dis-) associate
     * @param   int  $action   the action to be performed on the user
     * @param   int  $groupID  the id of the group to be (dis-) associated
     * @param   int  $roleID   the id of the role to be (dis-) associated
     *
     * @return bool
     */
    private function map(int $userID, int $action, int $groupID, int $roleID): bool
    {
        $mapData = ['group_id' => $groupID, 'user_id' => $userID];
        $map     = new UUGM();
        $map->load($mapData);

        if ($action === self::REMOVE) {
            // Mapping doesn't exist
            if (!$map->id) {
                return false;
            }

            // Delete the mapping
            if (!$roleID) {
                return $map->delete();
            }

            $assoc     = new RoleAssociations();
            $assocData = ['mapID' => $map->id, 'roleID' => $roleID];

            // Association doesn't exist
            if (!$assoc->load($assocData)) {
                return false;
            }

            // Delete the association
            return $assoc->delete();
        }

        if (!$map->id and !$map->save($mapData)) {
            // Doesn't exist and couldn't be created
            return false;
        }

        // No role requested or
        if (!$roleID or in_array($groupID, Groups::DEFAULT)) {
            return true;
        }

        $assoc     = new RoleAssociations();
        $assocData = ['mapID' => $map->id, 'roleID' => $roleID];

        return ($assoc->load($assocData) or $assoc->save($assocData));
    }

    /**
     * Publishes the selected accounts' profiles.
     * @return void
     */
    public function publish(): void
    {
        $this->togglePublished(true);
    }

    /**
     * Resets the user account with the given id. Joomla did not raise events here, so I also did not...
     *
     * @param   int   $userID  the id of the user account
     * @param   bool  $value   the value which the requireReset column should be set to
     *
     * @return bool
     */
    private function reset(int $userID, bool $value): bool
    {
        $this->checkToken();

        $users = new UT();
        $value = (int) $value;

        // The most expedient way to check for redundant execution.
        if (!$users->load($userID) or $users->requireReset === $value) {
            return false;
        }

        // Joomla overwrites 0 with '' somewhere if store is called on the users table.
        unset($users);

        $db = Application::getDB();

        $query = $db->getQuery(true)
            ->update($db->quoteName('#__users'))
            ->set($db->quoteName('requireReset') . " = $value")
            ->where($db->quoteName('id') . " = $userID");
        $db->setQuery($query);

        return $db->execute();
    }

    /**
     * Toggles the block state of the user.
     *
     * @param   bool  $value
     *
     * @return void
     */
    private function toggleBlock(bool $value): void
    {
        $this->checkToken();
        $this->authorize();

        /** @var CMSApplication $app */
        $app         = Application::instance();
        $block       = $value === true;
        $selectedIDs = Input::getSelectedIDs();
        $user        = Application::getUser();
        $super       = $user->get('isRoot');

        $selected = count($selectedIDs);
        $updated  = 0;

        PluginHelper::importPlugin('user');

        // Prepare the logout options.
        $options = ['clientid' => $app->get('shared_session', '0') ? null : 0];

        foreach ($selectedIDs as $selectedID) {
            if ($block and $selectedID === $user->id) {
                Application::message('GROUPS_CANNOT_BLOCK_SELF', Application::ERROR);
                continue;
            }

            if (!$super and Access::check($selectedID, 'core.admin')) {
                Application::message('GROUPS_CANNOT_BLOCK_SUPER_ADMIN', Application::WARNING);
                continue;
            }

            $users = new UT();

            if ($users->load($selectedID)) {
                $current = $users->getProperties();

                // Skip changing of same state
                if ($users->block == $value) {
                    continue;
                }

                $users->block = (int) $value;

                // If unblocking, also change password reset count to zero to unblock reset
                if (!$block) {
                    $users->resetCount = 0;
                }

                /**
                 * The check function is not called because the value set for the columns are predetermined and the
                 * existing values in other columns have nothing to do with this.
                 */

                $result = $app->triggerEvent('onUserBeforeSave', [$current, false, $users->getProperties()]);

                if (in_array(false, $result, true)) {
                    continue;
                }

                if (!$users->store()) {
                    Application::message($users->getError(), Application::ERROR);
                    continue;
                }

                $updated++;

                if ($users->block) {
                    UserHelper::destroyUserSessions($users->id);
                }

                $app->triggerEvent('onUserAfterSave', [$users->getProperties(), false, true, null]);

                // Log the user out.
                if ($block) {
                    $app->logout($users->id, $options);
                }
            }
        }

        $this->farewell($selected, $updated);
    }

    /**
     * Toggles the values of the published column.
     *
     * @param   bool  $value
     *
     * @return void
     */
    private function togglePublished(bool $value): void
    {
        $this->checkToken();

        $selectedIDs = Input::getSelectedIDs();

        // Deviating authorization because of personal account access
        if (!Can::changeState()) {
            $selectedIDs = $this->filterSelected($selectedIDs);
        }

        if (empty($selectedIDs)) {
            Application::error(403);
        }

        $selected = count($selectedIDs);
        $updated  = $this->updateBool('published', $selectedIDs, $value);

        $this->farewell($selected, $updated);
    }

    /**
     * Unblocks the selected users.
     * @return void
     */
    public function unblock(): void
    {
        $this->toggleBlock(false);
    }

    /**
     * Unpublishes the selected accounts' profiles.
     * @return void
     */
    public function unpublish(): void
    {
        $this->togglePublished(false);
    }
}