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

require_once 'component.php';
require_once 'profiles.php';

use Joomla\CMS\Table\Category as CoreTable;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\{Categories as Helper, Profiles, Users};

/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperCategories
{
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
     * @param   int  $parentID  Parent ID of this Category entry
     * @param   int  $userID
     *
     * @return  mixed int the id of the created category on success, otherwise false
     * @throws Exception
     */
    private static function createContentCategory($parentID, $userID)
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

        $alias = Users::alias($userID);

        //@todo: make the existing profile => category map table aptly named
        //@todo: wrap the categories table
        /** @var CoreTable $category */
        $category = Application::factory()->createTable('Category', '', ['dbo' => Application::database()]);

        $category->title           = Profiles::name($userID);
        $category->alias           = $alias;
        $category->path            = "$path/$alias";
        $category->extension       = 'com_content';
        $category->published       = 1;
        $category->access          = 1;
        $category->params          = '{"target":"","image":""}';
        $category->metadata        = '{"page_title":"","author":"","robots":""}';
        $category->created_user_id = $userID;
        $category->language        = '*';

        // Append category to parent as last child
        $category->setLocation($parentID, 'last-child');

        return empty($category->store()) ? false : $category->id;
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
