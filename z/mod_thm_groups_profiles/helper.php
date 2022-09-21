<?php
/**
 * @package     THM_Groups
 * @extension   mod_thm_groups_profiles
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

JLoader::import('joomla.application.component.model');
JLoader::import('category', JPATH_SITE . '/components/com_content/models');

/**
 * Data retrieval class for the THM Groups profile module.
 *
 * @category  Joomla.Module
 * @package   thm_groups
 */
class THM_GroupsHelperProfilesModule
{
    /**
     * Parses the text of the accessed article for THM Groups Members hooks.
     *
     * @param   int $articleID the id of the article to be parsed
     *
     * @return mixed array of parameters if successful, otherwise false
     * @throws Exception
     */
    public static function getArticleParameters($articleID)
    {
        $article = JControllerLegacy::getInstance('Content')->getModel('Article')->getItem($articleID);
        $matches = self::getContentParameters($article->introtext . $article->fulltext);

        return empty($matches) ? self::getCategoryParameters($article->catid) : $matches;
    }

    /**
     * Parse the text of a content category for THM Groups Members hooks.
     *
     * @param   int $categoryID the id of the category to be parsed
     *
     * @return mixed array of parameters if successful, otherwise false
     */
    public static function getCategoryParameters($categoryID)
    {
        $category = JModelLegacy::getInstance('Category', 'ContentModel')->getCategory($categoryID);

        if (!empty($category)) {
            return self::getContentParameters($category->description);
        }

        return [];
    }

    /**
     * Searches text for THM Groups Members hooks.
     *
     * @param   string $text the text to be searched
     *
     * @return  mixed array if hooks were found, otherwise false
     */
    private static function getContentParameters($text)
    {
        $pattern = '/{thm[_]?groups[A-Za-z0-9]*\s(.*)}/';
        $text    = strip_tags(html_entity_decode($text));
        preg_match($pattern, $text, $matches);

        if (empty($matches)) {
            return false;
        }

        $hooks      = explode('|', $matches[1]);
        $parameters = [];

        foreach ($hooks as $hook) {
            if (strpos($hook, '=') === false) {
                continue;
            }

            list($key, $value) = explode('=', $hook);

            $possibleValues = explode(',', $value);

            if ($key == 'uid' or $key == 'profileIDs') {
                $parameters['profileIDs'] = $possibleValues;
            } // Only one value is allowed for profile and group parameters
            elseif ($key == 'pid' or $key == 'templateIDs' or $key == 'templateID') {
                $parameters['templateID'] = $possibleValues[0];
            } elseif ($key == 'gid' or $key == 'groupIDs' or $key == 'groupID') {
                $parameters['groupID'] = $possibleValues[0];
            }
        }

        return $parameters;
    }

    /**
     * Parses matches found in the article or category text in order to extract useful parameters
     *
     * @param   array  $dynamicParams an array with dynamic parameter values
     * @param   object $moduleParams  the module parameters
     *
     * @return  array the profileID of the profiles to display
     */
    public static function getProfileIDs($dynamicParams, $moduleParams)
    {
        if (empty($dynamicParams) or (empty($dynamicParams['groupID']) and empty($dynamicParams['profileIDs']))) {
            return [];
        }

        // Individually listed profile IDs are not sorted
        if (isset($dynamicParams['profileIDs'])) {
            return $dynamicParams['profileIDs'];
        }

        if (!empty($dynamicParams['groupID'])) {
            if (empty($moduleParams) or empty($moduleParams['showRoles'])) {
                $profileIDs = THM_GroupsHelperGroups::getProfileIDs($dynamicParams['groupID']);
                return self::sortProfiles($profileIDs);
            }

            $profileIDs = [];
            $assocIDs = THM_GroupsHelperGroups::getRoleAssocIDs($dynamicParams['groupID']);
            foreach ($assocIDs as $assocID) {
                $roleProfileIDs = THM_GroupsHelperGroups::getProfileIDsByAssoc($assocID);
                $sortedProfilesIDs = self::sortProfiles($roleProfileIDs);
                $profileIDs = array_merge($profileIDs, $sortedProfilesIDs);
            }
            return $profileIDs;
        }

        return [];
    }

    /**
     * Sorts the profiles according to the surnames and forenames of the profiles.
     *
     * @param array $profiles the profiles to be sorted
     *
     * @return array the sorted profiles
     */
    private static function sortProfiles($profiles) {

        $profiles = array_flip($profiles);
        foreach (array_keys($profiles) as $profileID) {
            $name = THM_GroupsHelperProfiles::getLNFName($profileID);
            $profiles[$profileID] = $name;
        }
        asort($profiles);
        return array_flip($profiles);
    }
}
