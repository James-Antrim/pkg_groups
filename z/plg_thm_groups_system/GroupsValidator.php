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

use THM\Groups\Helpers\Users;

require_once JPATH_ROOT . '/media/com_thm_groups/helpers/content.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/profiles.php';

class GroupsValidator
{
    /**
     * Validates the query against the dynamic content parameters
     *
     * @param   array &$query  the query parameters
     *
     * @return bool|int true if the query has all required parameters, and they are valid, false if the query is invalid,
     *               int 0 if the validity could not be determined due to missing parameters.
     * @throws Exception
     */
    public static function validate(array &$query): bool|int
    {
        if (!$query) {
            return 0;
        }

        // Ignore calls to irrelevant components
        if (!empty($query['option'])) {
            switch ($query['option']) {
                case 'com_thm_groups' :
                    break;
                case 'com_content' :
                    if (empty($query['view'])) {
                        return false;
                    }
                    return match ($query['view']) {
                        'article', 'category' => 0,
                        default => false,
                    };
                default:
                    return false;
            }
        }

        if (empty($query['view'])) {
            if (empty($query['id']) and empty($query['profileID'])) {
                return 0;
            }

            if (!empty($query['id']) and THM_GroupsHelperContent::getAlias($query['id'])) {
                $profileID = empty($query['profileID']) ?
                    THM_GroupsHelperContent::isAssociated($query['id']) :
                    THM_GroupsHelperContent::isAssociated($query['id'], $query['profileID']);

                if (empty($profileID)) {
                    return false;
                }

                $query['profileID'] = $profileID;
                $query['view']      = 'content';

                return true;
            }
            elseif (!empty($query['profileID']) and Users::alias($query['profileID'])) {
                $query['view'] = 'profile';

                return true;
            }

            return false;
        }
        else {
            switch ($query['view']) {
                case 'content_manager':
                case 'profile':
                case 'profile_edit':

                    $username = empty($query['username']) ? '' : $query['username'];
                    if ($username and $profileID = THM_GroupsHelperProfiles::getProfileIDByUserName($username)) {
                        $query['profileID'] = $profileID;
                        unset($query['username']);
                    }

                    // Inconclusive
                    if (empty($query['profileID'])) {
                        return 0;
                    }

                    // Success
                    if (Users::alias($query['profileID'])) {
                        return true;
                    }

                    // Irrelevant
                    return false;
                case 'content':
                    // Inconclusive
                    if (empty($query['id'])) {
                        return 0;
                    }

                    // Success
                    if (THM_GroupsHelperContent::getAlias($query['id'])) {
                        return true;
                    }

                    // Irrelevant
                    return false;
                case 'overview':
                    // Success
                    return true;
                default:
                    return false;
            }
        }
    }

}