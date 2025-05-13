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
