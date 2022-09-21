<?php
/**
 * @category    Joomla module
 * @package     THM_Groups
 * @subpackage  mod_thm_groups_profiles
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2017 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */
$extraClasses = !empty($params['showImage'])? ' with-image' : '';
foreach ($profileIDs as $profileID) {
    echo '<div class="profile-container' . $extraClasses . '">';
    echo THM_GroupsHelperProfiles::getNameContainer($profileID, true);
    if (!empty($dynamicParams['groupID']) and !empty($params) and !empty($params['showRoles'])) {
        echo THM_GroupsHelperRoles::getRoles($profileID, $dynamicParams['groupID']);
    }
    echo THM_GroupsHelperProfiles::getDisplay($profileID, $dynamicParams['templateID'], true, $params['showImage']);
    echo '<div class="clearFix"></div></div>';
}
