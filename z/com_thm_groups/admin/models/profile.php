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
use THM\Groups\Controllers\Category as CategoryController;
use THM\Groups\Helpers\{Attributes, Can, Categories, Groups, Pages, Users};
use THM\Groups\Tables\{Categories as CategoriesTable, UserUsergroupMap, Users as UsersTable};

require_once HELPERS . 'profiles.php';

/**
 * Class loads form data to edit an entry.
 */
class THM_GroupsModelProfile extends JModelLegacy
{
    /**
     * Associates a group and potentially multiple roles with the selected users
     *
     * @return  bool true on success, otherwise false.
     */
    public function batch(): bool
    {
        if (!Can::manage()) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        $newProfileData  = Application::userRequestState('.profiles', 'profiles', [], 'array');
        $requestedAssocs = json_decode(urldecode(Input::string('batch-data')), true);
        $selectedIDs     = Input::selectedIDs();

        if ($selectedIDs and !empty($requestedAssocs)) {
            return $this->batchRoles($selectedIDs, $requestedAssocs);
        }
        elseif (!empty($newProfileData['groupIDs']) and !empty($newProfileData['profileIDs'])) {
            $this->batchProfiles($newProfileData['groupIDs'], $newProfileData['profileIDs']);
            return true;
        }

        Application::message('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION', Application::ERROR);

        return false;
    }

    /**
     * Associates the profiles with the given groups default group/role association.
     *
     * @param   int[]  $groupIDs  the ids of the selected groups
     * @param   int[]  $userIDs   the ids of the selected profiles
     *
     * @return void
     */
    private function batchProfiles(array $groupIDs, array $userIDs): void
    {
        foreach ($groupIDs as $groupID) {
            $assignedIDs   = Groups::profileIDs($groupID);
            $unassignedIDs = array_diff($userIDs, $assignedIDs);

            foreach ($unassignedIDs as $userID) {
                $assoc = ['group_id' => $groupID, 'user_id' => $userID];
                $map   = new UserUsergroupMap();
                if ($map->load($assoc)) {
                    continue;
                }

                $map->save($assoc);
            }
        }
    }

    /**
     * Associates the selected users with the selected group/role associations.
     *
     * @param   array  $userIDs
     * @param   array  $requestedAssocs  the ids of the group/role association with which the profiles should be associated
     *
     * @return bool
     */
    private function batchRoles(array $userIDs, array $requestedAssocs): bool
    {
        if (!$this->setJoomlaAssociations($userIDs, $requestedAssocs)) {
            return false;
        }

        return $this->setGroupsAssociations($userIDs, $requestedAssocs);
    }

    /**
     * Deletes the value for a specific profile picture attribute
     *
     * @param   int  $profileID    the id of the profile with which the picture is associated.
     * @param   int  $attributeID  the id of the attribute under which the value is stored.
     *
     * @return bool
     */
    public function deletePicture(int $profileID = 0, int $attributeID = 0): bool
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

        if (file_exists(Attributes::IMAGE_PATH . $fileName)) {
            unlink(Attributes::IMAGE_PATH . $fileName);
        }

        // Update new picture filename
        $query = DB::query()
            ->update('#__groups_profile_attributes')
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
     * @return  array
     */
    private function getGroupAssociations(array $requestedAssocs): array
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
     * Hides the public display of the user's profile. Access checks are performed in toggle.
     *
     * @return bool
     */
    public function hideContent(): bool
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

            $categoryDisabled = $this->toggleCategory($categoryID, Categories::HIDDEN);
            $contentDisabled  = $this->toggleColumn($profileID, 'content', Pages::HIDDEN);

            if (!$categoryDisabled or !$contentDisabled) {
                return false;
            }
        }

        return true;
    }

    /**
     * Allows public display of personal content. Access checks are performed in toggle.
     *
     * @return bool
     */
    public function publishContent(): bool
    {
        if (!Can::manage()) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);

            return false;
        }

        $profileIDs = Input::selectedIDs();
        $value      = Attributes::PUBLISHED;
        foreach ($profileIDs as $profileID) {
            if (!Users::published($profileID)) {
                continue;
            }

            if (!$categoryID = Users::categoryID($profileID)) {
                $category   = new CategoryController();
                $categoryID = $category->create($profileID);
            }

            if (!$this->toggleCategory($categoryID, $value) or !$this->toggleColumn($profileID, 'content', $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Saves user profile information
     *
     * @return  bool|int
     */
    public function save(): bool|int
    {
        $data = Input::post();

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
            ->set(DB::qcs([
                ['alias', $alias],
                ['forenames', $forenames],
                ['name', $names],
                ['published', $published],
                ['surnames', $surnames]
            ]))
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
     * @return  bool|string
     */
    public function saveCropped(): bool|string
    {
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
        $invalid           = ($file['size'] > 10000000 or !in_array(pathinfo($filename, PATHINFO_EXTENSION), $allowedExtensions));

        if ($invalid) {
            return false;
        }

        $attributeID = Input::integer('attributeID');
        $newFileName = $profileID . "_" . $attributeID . "." . strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $path        = Attributes::IMAGE_PATH . "/$newFileName";

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
     */
    private function saveValues(array $formData): bool
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
                Application::message($message, Application::ERROR);

                return false;
            }

            $value     = Input::removeEmptyTags($rawValue);
            $published = empty($attribute['published']) ? Attributes::HIDDEN : Attributes::PUBLISHED;

            $query = DB::query();
            $query->update('#__groups_profile_attributes')
                ->set(DB::qcs([['value', $value], ['published', $published]]))
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
     * @return  bool
     */
    private function setGroupsAssociations(array $profileIDs, array $requestedAssocs): bool
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
            return true;
        }

        // Is also a partial fail.
        if ($partialSuccess) {
            Application::message('PARTIAL_ASSOCIATION_FAIL', Application::WARNING);

            return true;
        }

        return false;
    }

    /**
     * Maps users to Joomla user groups.
     *
     * @param   array  $profileIDs  an array with profile ids (joomla user ids)
     * @param   array  $batchData   an array with groups and roles
     *
     * @return bool
     */
    private function setJoomlaAssociations(array $profileIDs, array $batchData): bool
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
        return DB::execute();
    }

    /**
     * Toggles a binary entity property value
     *
     * @return  bool
     */
    public function toggle(): bool
    {
        if (!Can::manage()) {
            Application::message('JLIB_RULES_NOT_ALLOWED', Application::ERROR);
            return false;
        }

        if (!$userID = Input::id()) {
            return false;
        }

        $value = !Input::bool('value');
        $value = (int) $value;

        switch (Input::cmd('attribute')) {
            case 'editing':
                return $this->toggleColumn($userID, 'editing', $value);
            case 'content':

                if (!$categoryID = Users::categoryID($userID)) {
                    $category   = new CategoryController();
                    $categoryID = $category->create($userID);
                }

                if (!Users::published($userID)) {
                    return false;
                }

                return $this->toggleCategory($categoryID, $value) and $this->toggleColumn($userID, 'content', $value);
            case 'published':

                if ($value) {
                    return $this->toggleColumn($userID, 'published', 1);
                }

                if ($categoryID = Users::categoryID($userID)) {
                    $this->toggleCategory($categoryID, Categories::HIDDEN);
                }

                $profileDisabled = $this->toggleColumn($userID, 'published', 0);
                $contentDisabled = $this->toggleColumn($userID, 'content', 0);

                return $contentDisabled and $profileDisabled;
            default:
                return false;
        }
    }

    /**
     * Toggles the category's published value.
     *
     * @param   int  $categoryID
     * @param   int  $value
     *
     * @return bool
     */
    private function toggleCategory(int $categoryID, int $value): bool
    {
        $table = new CategoriesTable();
        if ($table->load($categoryID)) {
            $table->published = $value;
            return $table->store();
        }
        return false;
    }

    /**
     * Updates a binary column value in the users table.
     *
     * @param   int     $userID
     * @param   string  $column
     * @param   int     $value
     *
     * @return bool
     */
    private function toggleColumn(int $userID, string $column, int $value): bool
    {
        $table = new UsersTable();
        if ($table->load($userID)) {
            $table->{$column} = $value;
            return $table->store();
        }
        return false;
    }
}
