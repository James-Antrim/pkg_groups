<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\Log\Log;
use THM\Groups\Adapters\{Application, Database as DB, Input};
use THM\Groups\Controllers\Category;
use THM\Groups\Helpers\{Attributes, Can, Users};

defined('_JEXEC') or die;
require_once HELPERS . 'profiles.php';
require_once HELPERS . 'roles.php';

/**
 * Class loads form data to edit an entry.
 */
class THM_GroupsModelProfile extends JModelLegacy
{
    const IMAGE_PATH = JPATH_ROOT . IMAGE_PATH;

    /**
     * Associates a group and potentially multiple roles with the selected users
     *
     * @return  bool true on success, otherwise false.
     * @throws Exception
     */
    public function batch()
    {
        $app = JFactory::getApplication();

        if (!Can::manage()) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        $newProfileData  = $app->getUserStateFromRequest('.profiles', 'profiles', [], 'array');
        $requestedAssocs = json_decode(urldecode(Input::string('batch-data')), true);
        $selectedIDs     = Input::selectedIDs();

        if ($selectedIDs and !empty($requestedAssocs)) {
            return $this->batchRoles($selectedIDs, $requestedAssocs);
        }
        elseif (!empty($newProfileData['groupIDs']) and !empty($newProfileData['profileIDs'])) {
            return $this->batchProfiles($newProfileData['groupIDs'], $newProfileData['profileIDs']);
        }

        Application::message('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION', Application::ERROR);

        return false;
    }

    /**
     * Associates the profiles with the given groups default group/role association.
     *
     * @param   array  $groupIDs    the ids of the selected groups
     * @param   array  $profileIDs  the ids of the selected profiles
     *
     * @return bool
     * @throws Exception
     */
    private function batchProfiles($groupIDs, $profileIDs)
    {
        foreach ($groupIDs as $groupID) {
            $memberAssocID = THM_GroupsHelperRoles::getAssocID(MEMBER, $groupID, 'group');

            foreach ($profileIDs as $profileID) {
                if (!THM_GroupsHelperProfiles::associateRole($profileID, $memberAssocID)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Associates the selected profiles with the selected group/role associations.
     *
     * @param   array  $profileIDs       the selected profileIDs
     * @param   array  $requestedAssocs  the ids of the group/role association with which the profiles should be associated
     *
     * @return bool
     * @throws Exception
     */
    private function batchRoles($profileIDs, $requestedAssocs)
    {
        if (!$this->setJoomlaAssociations($profileIDs, $requestedAssocs)) {
            return false;
        }

        return $this->setGroupsAssociations($profileIDs, $requestedAssocs);
    }

    /**
     * Deletes the value for a specific profile picture attribute
     *
     * @param   int  $profileID    the id of the profile with which the picture is associated.
     * @param   int  $attributeID  the id of the attribute under which the value is stored.
     *
     * @return mixed
     * @throws Exception
     */
    public function deletePicture($profileID = 0, $attributeID = 0)
    {
        $profileID   = Input::integer('profileID', $profileID);
        $attributeID = Input::integer('attributeID', $attributeID);

        if (!Users::editing($profileID)) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        $query = DB::query();
        $query->select('value')
            ->from('#__groups_profile_attributes')
            ->where(DB::qcs([['attributeID', $attributeID], ['profileID', $profileID]]));
        DB::set($query);
        $fileName = DB::string();

        // Button was pushed although there was no saved picture?
        if (empty($fileName)) {
            return true;
        }

        if (file_exists(self::IMAGE_PATH . $fileName)) {
            unlink(self::IMAGE_PATH . $fileName);
        }

        // Update new picture filename
        $query = DB::query();

        // Update the database with new picture information
        $query->update('#__groups_profile_attributes')
            ->set(DB::qc('value', '', '=', true))
            ->where(DB::qcs([['attributeID', $attributeID], ['profileID', $profileID]]));
        DB::set($query);
        DB::execute();

        return true;
    }

    /**
     * Returns a list of group assoc ids matching the request data
     *
     * @param   array  $requestedAssocs  An array with groups and roles
     *
     * @return  array with ids
     * @throws Exception
     */
    private function getGroupAssociations($requestedAssocs)
    {
        $assocs = [];

        foreach ($requestedAssocs as $requestedAssoc) {
            foreach ($requestedAssoc['roles'] as $role) {
                $query = DB::query();
                $query->select('id')
                    ->from('#__thm_groups_role_associations')
                    ->where("groupID = '{$requestedAssoc['id']}'")
                    ->where("roleID = {$role['id']}");
                DB::set($query);
                $assocID          = DB::integer();
                $assocs[$assocID] = $assocID;
            }
        }

        return $assocs;
    }

    /**
     * Allows public display of personal content. Access checks are performed in toggle.
     *
     * @return bool
     * @throws Exception
     */
    public function publishContent()
    {
        if (!Can::manage()) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        $profileIDs = Input::selectedIDs();
        foreach ($profileIDs as $profileID) {
            if (!$categoryID = Users::categoryID($profileID)) {
                $category   = new Category();
                $categoryID = $category->create($profileID);
            }

            if (!Users::published($profileID)) {
                return false;
            }

            $categoryEnabled = $this->updateCategoryPublishing($categoryID, 1);
            $contentEnabled  = $this->updateBinaryValue($profileID, 'contentEnabled', 1);

            if (!$categoryEnabled or !$contentEnabled) {
                return false;
            }
        }

        return true;
    }

    /**
     * Saves user profile information
     *
     * @return  mixed  int profile ID on success, otherwise false
     * @throws Exception
     */
    public function save()
    {
        $data = Input::post();

        // Ensuring int will fail access checks on manipulated ids.
        $profileID = $data['profileID'];

        if (!Users::editing($profileID)) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        if (!$this->saveValues($data)) {
            Application::message('SAVE_FAIL', Application::ERROR);
            return false;
        }

        /**
         * @todo: Get the fore- and surnames and the published value from the profile form
         */
        $forenames = $data['forenames'];
        $published = $data['published'];
        $surnames  = $data['surnames'];
        $names     = $forenames ? $surnames : "$forenames $surnames";
        $alias     = Users::createAlias($profileID, $names);

        $query = DB::query();
        $query->update(DB::qn('#__users'))
            ->set([
                DB::qc('alias', $alias),
                DB::qc('forenames', $forenames),
                DB::qc('name', $names),
                DB::qc('published', $published),
                DB::qc('surnames', $surnames),
            ])
            ->where(DB::qc('id', $profileID));
        DB::set($query);

        if (!DB::execute()) {
            Application::message('500', Application::ERROR);
        }

        return $profileID;
    }

    /**
     * Saves the cropped image that was uploaded via ajax in the profile_edit.view
     *
     * @return  bool|mixed|string
     * @throws Exception
     */
    public function saveCropped()
    {
        $app       = JFactory::getApplication();
        $profileID = Input::integer('profileID');

        if (!Users::editing($profileID)) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        $input = Input::instance();
        $file  = $input->files->get('data');

        if (empty($file)) {
            return false;
        }

        $filename = Input::string('filename');

        // TODO: Make these configurable
        $allowedExtensions = ['bmp', 'gif', 'jpg', 'jpeg', 'png', 'BMP', 'GIF', 'JPG', 'JPEG', 'PNG'];
        $invalid           = ($file['size'] > 10000000 or !in_array(pathinfo($filename, PATHINFO_EXTENSION),
                $allowedExtensions));

        if ($invalid) {
            return false;
        }

        $attributeID = Input::integer('attributeID');
        $newFileName = $profileID . "_" . $attributeID . "." . strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $path        = self::IMAGE_PATH . "/$newFileName";

        // Out with the old
        $deleted = $this->deletePicture($profileID, $attributeID);
        Application::message("Deleted: $deleted!");

        if (!$deleted) {
            return false;
        }

        // Upload new cropped image
        $uploaded = JFile::upload($file['tmp_name'], $path);

        // Create thumbs and send back prev image to the form
        if ($uploaded) {
            $position      = strpos($path, 'images' . DIRECTORY_SEPARATOR);
            $convertedPath = substr($path, $position);

            // Adding a random number ensures that the browser no longer uses the cached image.
            $random = rand(1, 100);

            return "<img  src='" . JURI::root() . $convertedPath . "?force=$random" . "'/>";
        }

        return false;
    }

    /**
     * Updates all profile attribute values and publication statuses.
     *
     * @param   array  $formData  the submitted form data
     *
     * @return  bool true on success, otherwise false
     * @throws Exception
     */
    private function saveValues($formData)
    {
        $profileID = $formData['profileID'];

        foreach ($formData as $attributeID => $attribute) {
            if (is_string($attribute)) {
                continue;
            }

            $rawValue = empty(strip_tags(trim($attribute['value']))) ? '' : trim($attribute['value']);

            if (str_contains($rawValue, '<script')) {
                $options = ['text_file' => 'groups_xss_attempts.php', 'text_entry_format' => '{DATETIME}:{MESSAGE}'];
                Log::addLogger($options, Log::ALL, ['com_thm_groups.XSSAttempts']);
                $message = "\n\nPerson:\n--------------\n";
                $message .= JFactory::getUser()->name;
                $message .= "\n\nAttempt:\n------\n";
                $message .= '"' . print_r($rawValue, true) . '"';
                $message .= "\n\n--------------------------------------------------------------------------------------------";
                $message .= "--------------------------------------";
                Log::add($message, Log::DEBUG, 'com_thm_groups.XSSAttempts');

                $message = 'Script tags are not allowed due to the danger of persistent cross-site scripting.<br>';
                $message .= 'Your username and your script "' . htmlentities($rawValue) . '" have been logged.';
                JFactory::getApplication()->enqueueMessage($message, 'error');

                return false;
            }

            $value     = DB::quote(Input::removeEmptyTags($rawValue));
            $published = empty($attribute['published']) ? Attributes::UNPUBLISHED : Attributes::PUBLISHED;

            $query = DB::query();
            $query->update('#__groups_profile_attributes')
                ->set([
                    DB::qc('value', $value),
                    DB::qc('published', $published),
                ])
                ->where(DB::qcs([['attributeID', $attributeID], ['profileID', $profileID]]));
            DB::set($query);

            if (!DB::execute()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Associates the profile with the given groups/roles
     *
     * @param   array  $profileIDs       the profile IDs which assignments are being edited
     * @param   array  $requestedAssocs  an array of groups and roles
     *
     * @return  boolean  True on success, false on failure
     * @throws Exception
     */
    private function setGroupsAssociations($profileIDs, $requestedAssocs)
    {
        // Can only occur by manipulation.
        if (empty($profileIDs) or empty($requestedAssocs)) {
            return true;
        }

        $roleAssociations = $this->getGroupAssociations($requestedAssocs);

        // Can only occur by manipulation.
        if (empty($roleAssociations)) {
            return false;
        }

        $completeSuccess = true;
        $partialSuccess  = false;

        foreach ($profileIDs as $profileID) {

            $profileAssociations = THM_GroupsHelperProfiles::getRoleAssociations($profileID);

            foreach ($roleAssociations as $assocID) {
                if (!in_array($assocID, $profileAssociations)) {
                    $success         = THM_GroupsHelperProfiles::associateRole($profileID, $assocID);
                    $completeSuccess = ($completeSuccess and $success);
                    $partialSuccess  = ($partialSuccess or $success);
                }
            }
        }

        if ($completeSuccess) {
            return $completeSuccess;
        }

        // Is also a partial fail.
        if ($partialSuccess) {
            JFactory::getApplication()->enqueueMessage('COM_THM_GROUPS_PARTIAL_ASSOCIATION_FAIL', 'warning');

            return $partialSuccess;
        }

        return false;
    }

    /**
     * Maps users to Joomla user groups.
     *
     * @param   array  $profileIDs  an array with profile ids (joomla user ids)
     * @param   array  $batchData   an array with groups and roles
     *
     * @return bool true on success, otherwise false
     * @throws Exception
     */
    private function setJoomlaAssociations($profileIDs, $batchData)
    {
        $existingQuery = DB::query();
        $existingQuery->select('id')->from('#__user_usergroup_map')
            ->where("user_id IN ('" . implode("','", $profileIDs) . "')");
        $query = DB::query();
        $query->insert('#__user_usergroup_map')->columns('user_id, group_id');
        $values = [];

        foreach ($profileIDs as $profileID) {
            foreach ($batchData as $groupData) {
                $values[] = [$profileID, $groupData['id']];
            }
        }

        $query->values($values);
        DB::set($query);

        try {
            return DB::execute();
        }
        catch (Exception $exception) {
            // Ignore duplicate entry exception
            if ($exception->getCode() === 1062) {
                return true;
            }
            else {
                JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

                return false;
            }
        }
    }

    /**
     * Toggles a binary entity property value
     *
     * @return  boolean  true on success, otherwise false
     * @throws Exception
     */
    public function toggle()
    {
        if (!Can::manage()) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        if (!$profileID = Input::id()) {
            return false;
        }

        // Toggle is called using the current value.
        $value = !Input::bool('value');

        switch (Input::cmd('attribute')) {
            case 'canEdit':
                return $this->updateBinaryValue($profileID, 'canEdit', $value);
            case 'contentEnabled':

                if (!$categoryID = Users::categoryID($profileID)) {
                    $category   = new Category();
                    $categoryID = $category->create($profileID);
                }

                if (!Users::published($profileID)) {
                    return false;
                }

                if ($value) {
                    $categoryEnabled = $this->updateCategoryPublishing($categoryID, 1);
                    $contentEnabled  = $this->updateBinaryValue($profileID, 'contentEnabled', 1);

                    return $categoryEnabled and $contentEnabled;
                }

                $categoryDisabled = $this->updateCategoryPublishing($categoryID, 0);
                $contentDisabled  = $this->updateBinaryValue($profileID, 'contentEnabled', 0);

                return $categoryDisabled and $contentDisabled;
            case 'published':

                if ($value) {
                    return $this->updateBinaryValue($profileID, 'published', 1);
                }

                if ($categoryID = Users::categoryID($profileID)) {
                    $this->updateCategoryPublishing($categoryID, 0);
                }

                $profileDisabled = $this->updateBinaryValue($profileID, 'published', 0);
                $contentDisabled = $this->updateBinaryValue($profileID, 'contentEnabled', 0);

                return $contentDisabled and $profileDisabled;
            default:
                return false;
        }
    }

    /**
     * Updates a binary value.
     *
     * @param   int     $profileID  the profile id
     * @param   string  $column     the name of the column to update
     * @param   mixed   $value      the new value to assign
     *
     * @return bool true if the query executed successfully, otherwise false
     * @throws Exception
     */
    private function updateBinaryValue($profileID, $column, $value)
    {
        $value = empty($value) ? 0 : 1;
        $query = DB::query();
        $query->update('#__thm_groups_profiles')->set("$column = $value")->where("id = $profileID");
        DB::set($query);

        try {
            return DB::bool();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return false;
        }
    }

    private function updateCategoryPublishing($categoryID, $value)
    {
        $query = DB::query();
        $query->update('#__categories')->set("published = $value")->where("id = $categoryID");
        DB::set($query);

        try {
            return DB::bool();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return false;
        }
    }

    /**
     * Hides the public display of the user's profile. Access checks are performed in toggle.
     *
     * @return bool
     * @throws Exception
     */
    public function unpublishContent()
    {
        if (!Can::manage()) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        $profileIDs = Input::selectedIDs();
        foreach ($profileIDs as $profileID) {
            if (!$categoryID = Users::categoryID($profileID)) {
                continue;
            }

            $categoryDisabled = $this->updateCategoryPublishing($categoryID, 0);
            $contentDisabled  = $this->updateBinaryValue($profileID, 'contentEnabled', 0);

            if (!$categoryDisabled or !$contentDisabled) {
                return false;
            }
        }

        return true;
    }
}
