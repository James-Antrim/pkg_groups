<?php
/**
 * @category    Joomla plugin
 * @package     THM_Groups
 * @subpackage  plg_thm_groups_system.site
 * @name        GroupsParser
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2019 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

require_once JPATH_ROOT . '/media/com_thm_groups/helpers/content.php';

use Joomla\CMS\Language\Text;
use THM\Groups\Helpers\{Categories, Pages, Users};

class GroupsParser
{
    /**
     * Checks whether the second last path item to a groups profile item
     *
     * @param   string  $possibleCategory  the path segment being checked
     *
     * @return array the query items which could be resolved
     * @throws Exception
     */
    public static function category(string $possibleCategory): array
    {
        $query = [];
        if (!preg_match('/^(\d+)-([a-zA-Z\-\d]+)$/', $possibleCategory, $cParams)) {
            return $query;
        }

        $profileID = Categories::userID($cParams[1]);

        // The profile ID does not match the paired alias.
        if ($profileID != Users::idByAlias($cParams[2])) {
            return $query;
        }

        if (Users::published($profileID)) {
            $query['profileID'] = $profileID;
            $query['view']      = 'profile';
        }

        return $query;
    }

    /**
     * Checks whether the path items provide the information required for dynamic linking via legacy configurations
     *
     * @param   array  $pathItems  the segments of the path
     *
     * @return array the parsed attributes
     * @throws Exception
     */
    public static function groupsLegacySEF(array $pathItems): array
    {
        $return        = [];
        $dynamicViews  = ['articles', 'content', 'content_manager', 'profile', 'profile_edit', 'singlearticle'];
        $allowedDepth2 = ['articles', 'content_manager', 'profile', 'profile_edit'];
        $allowedDepth3 = ['content', 'singlearticle'];

        // Remove the useless segment if existent
        if ($layoutSegment = array_search('default', $pathItems)) {
            unset($pathItems[$layoutSegment]);
        }

        $segmentCount = count($pathItems);

        // Legacy SEF URLs require at lease the view name and a resource ID
        if ($segmentCount <= 2) {
            return $return;
        }

        $lastItem = rawurldecode(array_pop($pathItems));

        // The view name is positioned wrong
        if (in_array($lastItem, $dynamicViews)) {
            return $return;
        }

        $secondLastItem = rawurldecode(array_pop($pathItems));

        // The views expected at a depth of two were not found.
        if (!in_array($secondLastItem, $allowedDepth2) and $segmentCount >= 3) {
            $thirdLastItem = rawurldecode(array_pop($pathItems));

            // Depth exceeded
            if (!in_array($thirdLastItem, $allowedDepth3)) {
                return $return;
            }
        }

        if (empty($thirdLastItem)) {
            switch ($secondLastItem) {
                case 'content':
                case 'singlearticle':
                    $return['id'] = THM_GroupsHelperContent::resolve($lastItem);

                    if (empty($return['id'])) {
                        return [];
                    }

                    $return['profileID'] = Pages::authorID($return['id']);

                    $return['view'] = $pathItems[0];
                    break;

                case 'articles':
                case 'content_manager':
                    $return['profileID'] = Users::resolve($lastItem);
                    $return['view']      = 'content_manager';
                    break;

                case 'profile':
                case 'profile_edit':
                    $return['profileID'] = Users::resolve($lastItem);
                    $return['view']      = $secondLastItem;
                    break;

            }
        }
        else {
            $return['profileID'] = Users::resolve($secondLastItem);
            $return['id']        = THM_GroupsHelperContent::resolve($lastItem);

            // Invalid profile id, but valid content id => use the profileID associated with the content
            if (empty($return['profileID']) and !empty($return['id'])) {
                $return['profileID'] = Pages::authorID($return['id']);
            }

            $return['view'] = 'content';
        }

        return $return;
    }

    /**
     * Checks whether the segments provide the information required for dynamic linking from groups to groups
     *
     * @param   array &$pathItems  the segments of the path
     *
     * @return array the query
     * @throws Exception
     */
    public static function groupsSEF(array $pathItems): array
    {
        $query = [];
        if (empty($pathItems)) {
            return $query;
        }

        $lastItem       = array_pop($pathItems);
        $lastItem       = rawurldecode($lastItem ?: '');
        $secondLastItem = array_pop($pathItems);
        $secondLastItem = rawurldecode($secondLastItem ?: '');

        $lang = JFactory::getLanguage();
        $lang->load('com_thm_groups');

        // Resolve modern sef links first
        if ($lastItem === Text::_('COM_THM_GROUPS_OVERVIEW_ALIAS')) {
            // No unfiltered listing right now
            //$query['search'] = '';
            //$query['view']   = 'overview';
        }
        elseif ($secondLastItem === Text::_('COM_THM_GROUPS_DISAMBIGUATION_ALIAS')) {
            $query['search'] = $lastItem;
            $query['view']   = 'overview';
        }
        elseif ($lastItem === Text::_('COM_THM_GROUPS_CONTENT_MANAGER_ALIAS')) {
            $profileID = Users::idByAlias($secondLastItem);
            if (!empty($profileID)) {
                $query['view']      = 'content_manager';
                $query['profileID'] = $profileID;
            }
        }
        elseif ($lastItem === Text::_('COM_THM_GROUPS_EDIT_ALIAS')) {
            $profileID = Users::idByAlias($secondLastItem);
            if (!empty($profileID)) {
                $query['view']      = 'profile_edit';
                $query['profileID'] = $profileID;
            }
        }
        elseif ($lastItem === 'vcf' or $lastItem === 'json') {
            $profileID = Users::idByAlias($secondLastItem);
            if (!empty($profileID)) {
                $query['view']      = 'profile';
                $query['profileID'] = $profileID;
                $query['format']    = $lastItem;
            }
        }
        elseif (empty($secondLastItem)) {
            $profileID = Users::idByAlias($lastItem);
            if (!empty($profileID)) {
                $query['view']      = 'profile';
                $query['profileID'] = $profileID;
            }
        }
        else {
            $profileID = Users::idByAlias($secondLastItem);
            if (!empty($profileID)) {
                $query['profileID'] = $profileID;
                $contentID          = Pages::id($lastItem, $profileID);
                if (empty($contentID)) {
                    $query['view'] = 'profile';
                }
                else {
                    $query['view'] = 'content';
                    $query['id']   = $contentID;
                }
            }
        }

        if (!empty($query['profileID']) and !is_numeric($query['profileID'])) {
            $query['search'] = $query['profileID'];
            $query['view']   = 'overview';
            unset($query['profileID']);
        }

        if (empty($query['view'])) {
            return [];
        }

        $query['option'] = 'com_thm_groups';

        return $query;
    }

    /**
     * Checks whether the segments provide the information required for dynamic linking from groups to groups using the
     * links as joomla would have created them with the old router.
     *
     * @param   array &$pathItems  the segments of the path
     *
     * @return array the query
     * @throws Exception
     */
    public static function groupsJoomla(array $pathItems): array
    {
        $query          = [];
        $lastItem       = rawurldecode(array_pop($pathItems));
        $secondLastItem = count($pathItems) ? rawurldecode(array_pop($pathItems)) : false;

        // Rules with context. If context does not resolve to something relevant the query is returned empty.
        if ($secondLastItem) {
            if ($secondLastItem === 'profile_edit' or $secondLastItem === 'content_manager') {
                $query['profileID'] = Users::resolve($lastItem);
                $query['view']      = $secondLastItem;
            }
            elseif ($profileID = Users::resolve($secondLastItem)) {
                if ($contentID = THM_GroupsHelperContent::resolve($lastItem, $profileID)) {
                    $query['id']   = $contentID;
                    $query['view'] = 'content';
                }
                else {
                    $query['view'] = 'profile';
                }
                $query['profileID'] = $profileID;
            }
            elseif ($profileID = Categories::resolve($secondLastItem)) {
                if ($profileID === true) {
                    // Second last is Groups root category
                    $query['profileID'] = Categories::resolve($lastItem);
                    $query['view']      = 'profile';
                }
                else {
                    if ($contentID = THM_GroupsHelperContent::resolve($lastItem, $profileID)) {
                        $query['id']   = $contentID;
                        $query['view'] = 'content';
                    }
                    else {
                        $query['view'] = 'profile';
                    }
                    $query['profileID'] = $profileID;
                }
            }

            if (!empty($query['view'])) {
                $query['option'] = 'com_thm_groups';
            }

            return $query;
        }

        $searchProfileID = Users::resolve($lastItem);
        if ($searchProfileID and is_numeric($searchProfileID)) {
            $query['profileID'] = $searchProfileID;
            $query['view']      = 'profile';
        }
        elseif ($categoryProfileID = Categories::resolve($lastItem)) {
            if ($categoryProfileID === true) {
                $query['search'] = '';
                $query['view']   = 'overview';
            }
            else {
                $query['profileID'] = $categoryProfileID;
                $query['view']      = 'profile';
            }
        }
        elseif ($contentID = THM_GroupsHelperContent::resolve($lastItem)) {
            if ($profileID = THM_GroupsHelperContent::isAssociated($contentID)) {
                $query['id']        = $contentID;
                $query['profileID'] = $profileID;
                $query['view']      = 'content';
            }
        }
        elseif ($searchProfileID and is_string($searchProfileID)) {
            $query['search'] = $searchProfileID;
            $query['view']   = 'overview';
        }

        if (!empty($query['view'])) {
            $query['option'] = 'com_thm_groups';

            return $query;
        }

        return [];
    }
}