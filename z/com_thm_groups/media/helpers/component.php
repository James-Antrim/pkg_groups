<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@mni.thm.de>
 * @author      Ilja Michajlow, <ilja.michajlow@mni.thm.de>
 * @author      Dieudonne Timma Meyatchie, <dieudonne.timma.meyatchie@mni.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

// Protected Attributes
use THM\Groups\Helpers\Can;

define('FORENAME', 1);
define('SURNAME', 2);
define('EMAIL_ATTRIBUTE', 4);
define('TITLE', 5);
define('POSTTITLE', 7);

// Valid for both fields and attribute types
define('TEXT', 1);
define('URL', 3);
define('EMAIL', 6);

// Field types
define('EDITOR', 2);
define('FILE', 4);
define('CALENDAR', 5);
define('TELEPHONE', 7);

// Attribute types
define('HTML', 2);
define('IMAGE', 4);
define('DATE_EU', 5);
define('TELEPHONE_EU', 7);
define('NAME', 8);
define('SUPPLEMENT', 9);

// Protected Role
define('MEMBER', 1);

// Base URLs for which are often used
define('HELPERS', JPATH_ROOT . '/media/com_thm_groups/helpers/');
define('IMAGE_PATH', '/images/com_thm_groups/profile/');

/**
 * Class providing functions usefull to multiple component files
 */
class THM_GroupsHelperComponent
{
    /**
     * Checks access for edit views
     *
     * @param   object &$model   the model checking permissions
     * @param   int     $itemID  the id if the resource to be edited (empty for new entries)
     *
     * @return  bool  true if the user can access the edit view, otherwise false
     */
    public static function allowEdit(&$model, $itemID = 0)
    {
        // Admins can edit anything. Department and monitor editing is implicitly covered here.
        if (Can::administrate()) {
            return true;
        }

        $name = $model->get('name');

        // Views accessible with component create/edit access
        $resourceEditViews = [
            'attribute_edit',
            'attribute_type_edit',
            'profile_edit',
            'role_edit',
            'template_edit'
        ];
        if (in_array($name, $resourceEditViews)) {
            if ((int) $itemID > 0) {
                return $model->actions->{'core.edit'};
            }

            return $model->actions->{'core.create'};
        }

        return false;
    }

    /**
     * Calls the appropriate controller
     *
     * @param   boolean  $isAdmin  whether the file is being called from the backend
     *
     * @return  void
     * @throws Exception
     */
    public static function callController($isAdmin = true)
    {
        $basePath = $isAdmin ? JPATH_COMPONENT_ADMINISTRATOR : JPATH_COMPONENT_SITE;

        $handler = explode(".", JFactory::getApplication()->input->getCmd('task', ''));

        if (count($handler) > 1) {
            $task = $handler[1];
        }
        else {
            $task = $handler[0];
        }

        /** @noinspection PhpIncludeInspection */
        require_once $basePath . '/controller.php';
        $controllerObj = new THM_GroupsController;
        $controllerObj->execute($task);
        $controllerObj->redirect();
    }

    /**
     * Clean the cache
     *
     * @return  void
     * @throws Exception
     */
    public static function cleanCache()
    {
        $conf = JFactory::getConfig();

        $options = [
            'defaultgroup' => 'com_thm_groups',
            'cachebase'    => JFactory::getApplication()->isClient('administrator') ?
                JPATH_ADMINISTRATOR . '/cache' : $conf->get('cache_path', JPATH_SITE . '/cache'),
            'result'       => true,
        ];

        try {
            $cache = JCache::getInstance('callback', $options);
            $cache->clean();
        }
        catch (Exception $exception) {
            $options['result'] = false;
        }
        // Set the clean cache event
        if (isset($conf['event_clean_cache'])) {
            $event = $conf['event_clean_cache'];
        }
        else {
            $event = 'onContentCleanCache';
        }

        // Trigger the onContentCleanCache event.
        JEventDispatcher::getInstance()->trigger($event, $options);
    }

    /**
     * Cleans a given collection. Converts to array as necessary. Removes duplicate values. Enforces int type. Removes
     * 0 value indexes.
     *
     * @param   mixed  $array  the collection to be cleaned (array|object)
     *
     * @return array the converted array
     */
    public static function cleanIntCollection($array)
    {
        if (!is_array($array)) {
            if (!is_object($array)) {
                return [];
            }

            $array = Joomla\Utilities\ArrayHelper::fromObject($array);
        }

        $array = Joomla\Utilities\ArrayHelper::toInteger(array_filter(array_unique($array)));

        return $array;
    }

    /**
     * Removes empty tags or tags with &nbsp; recursively
     *
     * @param   string  $original  the original text
     *
     * @return string the text without empty tags
     */
    public static function removeEmptyTags($original)
    {
        $pattern = "/<[^\/>]*>([\s|\&nbsp;]?)*<\/[^>]*>/";
        $cleaned = preg_replace($pattern, '', $original);

        // If the text remains unchanged there is no more to be done => bubble up
        if ($original == $cleaned) {
            return $original;
        }

        // There could still be further empty tags which encased the original empties.
        return self::removeEmptyTags($cleaned);
    }
}
