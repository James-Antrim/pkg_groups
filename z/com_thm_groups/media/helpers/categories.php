<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

// Added here for calls from plugins
use THM\Groups\Helpers\{Can, Profiles, Users};

require_once 'component.php';
require_once 'profiles.php';

use THM\Groups\Helpers\Categories as Helper;

/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperCategories
{
    /**
     * Checks whether the user is authorized to edit the contents of the given category
     *
     * @param   int  $categoryID  the id of the category
     *
     * @return bool true if the user may edit the the category's content, otherwise false
     * @throws Exception
     */
    public static function canCreate($categoryID)
    {
        if (Can::manage()) {
            return true;
        }

        $user           = JFactory::getUser();
        $canCreate      = $user->authorise('core.create', 'com_content.category.' . $categoryID);
        $profileID      = Helper::userID($categoryID);
        $isOwn          = $profileID === $user->id;
        $isPublished    = Users::published($profileID);
        $contentEnabled = Users::content($profileID);

        return ($canCreate and $isOwn and $isPublished and $contentEnabled);
    }

    /**
     * Checks whether the user is authorized to edit the contents of the given category
     *
     * @param   int  $categoryID  the id of the category
     *
     * @return bool true if the user may edit the the category's content, otherwise false
     * @throws Exception
     */
    public static function canEdit($categoryID)
    {
        if (Can::manage()) {
            return true;
        }

        $user       = JFactory::getUser();
        $canEdit    = $user->authorise('core.edit', 'com_content.category.' . $categoryID);
        $canEditOwn = $user->authorise('core.edit.own', 'com_content.category.' . $categoryID);
        $profileID  = Helper::userID($categoryID);
        $isOwn      = $profileID === $user->id;

        // Irregardless of configuration only administrators and content owners should be able to edit
        $editEnabled    = (($canEdit or $canEditOwn) and $isOwn);
        $isPublished    = Users::published($profileID);
        $contentEnabled = Users::content($profileID);
        $profileEnabled = ($isPublished and $contentEnabled);

        return ($editEnabled and $profileEnabled);
    }

    /**
     * Creates a content category for the profile
     *
     * @param   int  $profileID  the id of the user for whom the category is to be created
     *
     * @return int the id of the category if created
     * @throws Exception
     */
    public static function create($profileID)
    {
        $categoryID = 0;
        $parentID   = Helper::root();

        if ($parentID > 0) {
            // Create category and get its ID
            $categoryID = self::createContentCategory($parentID, $profileID);

            // Change created_user_id attribute in db, because of bug
            self::setCreator($profileID, $categoryID);

            // Map category to profile
            self::mapProfile($profileID, $categoryID);
        }

        return $categoryID;
    }

    /**
     * Creates a content category for the user's personal content
     *
     * @param   int  $parentID   Parent ID of this Category entry
     * @param   int  $profileID  Id of user
     *
     * @return  mixed int the id of the created category on success, otherwise false
     * @throws Exception
     */
    private static function createContentCategory($parentID, $profileID)
    {
        $dbo = JFactory::getDBO();

        // Get the path of the root category
        $query = $dbo->getQuery(true);
        $query->select("path")->from("#__categories")->where("id = '$parentID'");
        $dbo->setQuery($query);

        try {
            $path = $dbo->loadResult();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return false;
        }

        $alias    = Users::alias($profileID);
        $category = JTable::getInstance('Category', 'JTable');

        $category->title           = Profiles::name($profileID);
        $category->alias           = $alias;
        $category->path            = "$path/$alias";
        $category->extension       = 'com_content';
        $category->published       = 1;
        $category->access          = 1;
        $category->params          = '{"target":"","image":""}';
        $category->metadata        = '{"page_title":"","author":"","robots":""}';
        $category->created_user_id = $profileID;
        $category->language        = '*';

        // Append category to parent as last child
        $category->setLocation($parentID, 'last-child');

        return empty($category->store()) ? false : $category->id;
    }

    /**
     * Gets the profile's category id
     *
     * @param   int  $profileID  the user id
     *
     * @return  mixed  int on successful query, null if the query failed, 0 on exception or if user is empty
     * @throws Exception
     */
    public static function getIDByProfileID($profileID)
    {
        $contentEnabled = Users::content($profileID);

        if (!$contentEnabled) {
            return 0;
        }

        $dbo   = JFactory::getDBO();
        $query = $dbo->getQuery(true);

        $query->select('cc.id')
            ->from('#__categories AS cc')
            ->innerJoin('#__thm_groups_categories AS gc ON gc.id = cc.id')
            ->where("profileID = '$profileID'");
        $dbo->setQuery($query);

        try {
            $categoryID = $dbo->loadResult();
        }
        catch (Exception $exc) {
            JFactory::getApplication()->enqueueMessage($exc->getMessage(), 'error');

            return 0;
        }

        if (!empty($categoryID)) {
            return $categoryID;
        }

        self::create($profileID);

        return self::getIDByProfileID($profileID);
    }

    /**
     * Creates an association mapping a profile to a content category
     *
     * @param   int  $profileID   the profile ID
     * @param   int  $categoryID  the category ID to be associated with the profile
     *
     * @return  bool true on success, otherwise false
     * @throws Exception
     */
    private static function mapProfile($profileID, $categoryID)
    {
        $dbo   = JFactory::getDBO();
        $query = $dbo->getQuery(true);
        $query->insert('#__thm_groups_categories')->set("profileID = '$profileID'")->set("id = '$categoryID'");
        $dbo->setQuery($query);

        try {
            $success = $dbo->execute();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return false;
        }

        return empty($success) ? false : true;
    }

    /**
     * Set the created_user_id attribute for a category
     *
     * @param   int  $profileID   the profile id
     * @param   int  $categoryID  category id
     *
     * @return bool true on success, otherwise false
     * @throws Exception
     */
    private static function setCreator($profileID, $categoryID)
    {
        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);

        $query->update('#__categories')->set("created_user_id = '$profileID'")->where("id = '$categoryID'");
        $dbo->setQuery($query);

        try {
            $success = $dbo->execute();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return false;
        }

        return empty($success) ? false : true;
    }
}
