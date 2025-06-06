<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\Text;
use THM\Groups\Helpers\{Attributes, Types, Users};
use THM\Groups\Controllers\Profile as Controller;

require_once 'attributes.php';
require_once 'groups.php';
require_once 'router.php';
require_once JPATH_ROOT . '/administrator/components/com_thm_groups/tables/profiles.php';
require_once JPATH_ROOT . '/administrator/components/com_thm_groups/tables/profile_attributes.php';


/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperProfiles
{
    /**
     * Adds an association profile => group in the Joomla table mapping this relationship
     *
     * @param   int  $profileID  the id of the profile to associate
     * @param   int  $groupID    the id of the group to associate the profile with
     *
     * @return void if an exception occurs it is handled as such
     * @throws Exception
     */
    public static function associateJoomlaGroup($profileID, $groupID)
    {
        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $query->insert('#__user_usergroup_map')->columns("user_id, group_id")->values("$profileID, $groupID");
        $dbo->setQuery($query);

        try {
            $dbo->execute();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return;
        }

    }

    /**
     * Associates a profile with a given group/role association
     *
     * @param   int  $profileID  the id of the profile to associate
     * @param   int  $assocID    the id of the group/role association with which to associate it
     *
     * @return bool
     *
     * @throws Exception
     */
    public static function associateRole($profileID, $assocID)
    {
        if ($existingID = THM_GroupsHelperRoles::getAssocID($assocID, $profileID, 'profile')) {
            return $existingID;
        }

        $profiles = JTable::getInstance('profiles', 'thm_groupsTable');

        // Profile is new
        if (!$profiles->load($profileID)) {
            $controller = new Controller();
            $controller->create($profileID);
        }

        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $query->insert('#__thm_groups_profile_associations')
            ->columns(['profileID', 'role_associationID'])
            ->values("$profileID, $assocID");
        $dbo->setQuery($query);

        try {
            $dbo->execute();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return false;
        }

        return THM_GroupsHelperRoles::getAssocID($assocID, $profileID, 'profile');
    }

    /**
     * Corrects missing group associations caused by missing event triggers from batch processing in com_user.
     *
     * @return void if an exception occurs it is handled as such
     * @throws Exception
     */
    public static function correctGroups()
    {
        $dbo = JFactory::getDbo();

        // Associations that are in Groups, but not in Joomla
        $query = $dbo->getQuery(true);
        $query->select('DISTINCT pAssoc.profileID, rAssoc.groupID, uum.user_id')
            ->from('#__thm_groups_profile_associations AS pAssoc')
            ->innerJoin('#__thm_groups_role_associations as rAssoc on pAssoc.role_associationID = rAssoc.id')
            ->leftJoin('#__user_usergroup_map as uum on uum.user_id = pAssoc.profileID and uum.group_id = rAssoc.groupID')
            ->where('uum.user_id IS NULL');
        $dbo->setQuery($query);

        try {
            $missingAssocs = $dbo->loadAssocList();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return;
        }

        if (!empty($missingAssocs)) {
            foreach ($missingAssocs as $missingAssoc) {
                self::associateJoomlaGroup($missingAssoc['profileID'], $missingAssoc['groupID']);
            }
        }

        $stdGroups = "1,2,3,4,5,6,7,8";

        // Users in relevant groups are missing from Groups
        $query = $dbo->getQuery(true);
        $query->select('DISTINCT user_id')
            ->from('#__user_usergroup_map AS uum')
            ->where("group_id NOT IN ($stdGroups)")
            ->where('user_id NOT IN (SELECT id FROM #__thm_groups_profiles)');
        $dbo->setQuery($query);

        try {
            $missingIDs = $dbo->loadColumn();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return;
        }

        if ($missingIDs) {
            $controller = new Controller();
            foreach ($missingIDs as $missingID) {
                $controller->create($missingID);
            }
        }

        // Associations that are in Joomla, but not in Groups
        $query = $dbo->getQuery(true);
        $query->select('DISTINCT uum.user_id AS profileID, ra.id AS assocID')
            ->from('#__user_usergroup_map AS uum')
            ->innerJoin('#__thm_groups_profiles AS profile ON profile.id = uum.user_id')
            ->innerJoin('#__thm_groups_role_associations AS ra ON ra.groupID = uum.group_id AND ra.roleID = 1')
            ->leftJoin('#__thm_groups_profile_associations AS pa ON pa.profileID = profile.id AND pa.role_associationID = ra.id')
            ->where("uum.group_id NOT IN ($stdGroups)")
            ->where('pa.id IS NULL');
        $dbo->setQuery($query);

        try {
            $missingAssocs = $dbo->loadAssocList();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return;
        }

        if ($missingAssocs) {
            foreach ($missingAssocs as $missingAssoc) {
                self::associateRole($missingAssoc['profileID'], $missingAssoc['assocID']);
            }
        }
    }

    /**
     * Creates the name to be displayed
     *
     * @param   int   $profileID  the user id
     * @param   bool  $withTitle  whether the titles should be displayed
     * @param   bool  $withSpan   whether the attributes should be contained in individual spans for style assignments
     *
     * @return  string  the profile name
     * @throws Exception
     */
    public static function getDisplayName($profileID, $withTitle = false, $withSpan = false)
    {
        $ntData = self::getNamesAndTitles($profileID, $withTitle, $withSpan);

        $text = "{$ntData['preTitle']} {$ntData['forename']} {$ntData['surname']}";

        // The dagger for deceased was moved in the called function
        if (!empty($ntData['postTitle'])) {
            $text .= ", {$ntData['postTitle']}";
        }

        return trim($text);
    }

    /**
     * Creates HTML for the display of a profile
     *
     * @param   int   $profileID   the id of the profile
     * @param   int   $templateID  the id of the template
     * @param   bool  $suppress    whether to suppress long texts
     * @param   bool  $showImage   whether to suppress image attributes
     *
     * @return string the HTML of the profile
     * @throws Exception
     */
    public static function getDisplay($profileID, $templateID = 0, $suppress = false, $showImage = true)
    {
        $preRendered     = [Attributes::SUPPLEMENT_PRE, FORENAME, SURNAME, Attributes::SUPPLEMENT_POST];
        $attributes      = [];
        $imageAttributes = [];

        $attributeIDs = THM_GroupsHelperAttributes::getAttributeIDs(true, $templateID);

        foreach ($attributeIDs as $attributeID) {

            if (in_array($attributeID, $preRendered)) {
                continue;
            }

            $attribute = THM_GroupsHelperAttributes::getAttribute($attributeID, $profileID, true);

            if (empty($attribute['value']) or empty(trim($attribute['value']))) {
                continue;
            }

            $renderedAttribute = THM_GroupsHelperAttributes::getDisplay($attribute, $suppress);

            if ($attribute['typeID'] == Types::IMAGE) {
                if ($showImage) {
                    $imageAttributes[$attribute['id']] = $renderedAttribute;
                }
            }
            else {
                $attributes[$attribute['id']] = $renderedAttribute;
            }
        }

        return implode('', $imageAttributes) . implode('', $attributes);
    }

    /**
     * Creates the name to be displayed
     *
     * @param   int   $profileID  the user id
     * @param   bool  $withTitle  whether the titles should be displayed
     * @param   bool  $withSpan   whether the attributes should be contained in individual spans for style assignments
     *
     * @return  string  the profile name
     * @throws Exception
     */
    public static function getLNFName($profileID, $withTitle = false, $withSpan = false)
    {
        $ntData = self::getNamesAndTitles($profileID, $withTitle, $withSpan);

        $text = empty($ntData['forename']) ? $ntData['surname'] : "{$ntData['surname']}, {$ntData['forename']} ";
        $text .= " {$ntData['preTitle']} {$ntData['postTitle']}";

        return trim($text);
    }

    /**
     * Creates the HTML for the name container
     *
     * @param   int   $profileID  the id of the profile
     * @param   bool  $newTab     whether the profile should open in a new tab
     *
     * @return string the HTML string containing name information
     * @throws Exception
     */
    public static function getNameContainer($profileID, $newTab = false)
    {
        $text    = self::getDisplayName($profileID, true, true);
        $url     = THM_GroupsHelperRouter::build(['view' => 'profile', 'profileID' => $profileID]);
        $attribs = [];
        if ($newTab) {
            $attribs['target'] = '_blank';
        }
        $link      = JHtml::link($url, $text, $attribs);
        $vCardLink = self::getVCardLink($profileID);

        return '<div class="attribute-wrap attribute-header">' . $link . $vCardLink . '<div class="clearFix"></div></div>';
    }

    /**
     * Retrieves data to be used in functions returning profile names and titles
     *
     * @param   int   $profileID  the user id
     * @param   bool  $withTitle  whether the titles should be displayed
     * @param   bool  $withSpan   whether the attributes should be contained in individual spans for style assignments
     *
     * @return  array the name and title data
     * @throws Exception
     */
    private static function getNamesAndTitles($profileID, $withTitle = false, $withSpan = false)
    {
        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $query->select('sn.value AS surname, fn.value AS forename')
            ->select('prt.value AS preTitle, prt.published AS prePublished')
            ->select('pot.value AS postTitle, pot.published AS postPublished')
            ->from('#__thm_groups_profile_attributes AS sn')
            ->leftJoin('#__thm_groups_profile_attributes AS fn ON fn.profileID = sn.profileID')
            ->leftJoin('#__thm_groups_profile_attributes AS prt ON prt.profileID = sn.profileID')
            ->leftJoin('#__thm_groups_profile_attributes AS pot ON pot.profileID = sn.profileID')
            ->where("sn.profileID = $profileID")
            ->where("sn.attributeID = " . SURNAME)
            ->where("fn.attributeID = " . FORENAME)
            ->where("prt.attributeID = " . Attributes::SUPPLEMENT_PRE)
            ->where("pot.attributeID = " . Attributes::SUPPLEMENT_POST);

        $dbo->setQuery($query);

        try {
            if (!$results = $dbo->loadAssoc()) {
                return [];
            }
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

        }

        if (empty($withTitle) or empty($results['prePublished'])) {
            $results['preTitle'] = '';
        }

        if (empty($withTitle) or empty($results['postPublished'])) {
            $results['postTitle'] = '';
        }
        else {
            // Special handling for deceased
            if (strpos($results['postTitle'], '†') !== false) {
                $results['surname']   .= ' †';
                $results['postTitle'] = trim(str_replace('†', '', $results['postTitle']));
            }
        }

        if ($withSpan) {
            $results['surname']   = empty($results['surname']) ? '' : '<span class="name-value">' . $results['surname'] . '</span>';
            $results['forename']  = empty($results['forename']) ? '' : '<span class="name-value">' . $results['forename'] . '</span>';
            $results['preTitle']  = empty($results['preTitle']) ? '' : '<span class="title-value">' . $results['preTitle'] . '</span>';
            $results['postTitle'] = empty($results['postTitle']) ? '' : '<span class="title-value">' . $results['postTitle'] . '</span>';
        }

        return empty($results) ? [] : $results;
    }

    /**
     * Retrieves the id of the profile associated with the given alias.
     *
     * @param   string  $username  the username
     *
     * @return mixed int the profile id on distinct success, string if multiple profiles were found inconclusively,
     * otherwise 0
     *
     * @throws Exception
     */
    public static function getProfileIDByUserName($username)
    {
        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);
        $query->select('DISTINCT p.id')
            ->from('#__thm_groups_profiles AS p')
            ->innerJoin('#__users AS u on u.id = p.id')
            ->where('u.username = ' . $dbo->quote($username));

        $dbo->setQuery($query);

        try {
            $profileID = $dbo->loadResult();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return 0;
        }

        return empty($profileID) ? 0 : $profileID;
    }

    /**
     * Retrieves the attributes for a given profile id in their raw format
     *
     * @param   int   $profileID  the id whose values are sought
     * @param   bool  $published  whether only published values should be returned
     *
     * @return array the profile attributes
     * @throws Exception
     */
    public static function getRawProfile($profileID, $published = true)
    {
        $attributes           = [];
        $attributeIDs         = THM_GroupsHelperAttributes::getAttributeIDs(true);
        $authorizedViewAccess = JFactory::getUser()->getAuthorisedViewLevels();

        foreach ($attributeIDs as $attributeID) {

            $attribute = THM_GroupsHelperAttributes::getAttribute($attributeID, $profileID, $published);

            $emptyValue   = (empty($attribute['value']) or empty(trim($attribute['value'])));
            $unAuthorized = (empty($attribute['value']) or !in_array($attribute['viewLevelID'], $authorizedViewAccess));
            if ($emptyValue or $unAuthorized) {
                continue;
            }

            $attributes[$attribute['id']] = $attribute;
        }

        return $attributes;
    }

    /**
     * Gets the role association ids associated with the profile
     *
     * @param   int  $profileID  the id of the profile
     *
     * @return array the role association ids associated with the profile
     * @throws Exception
     */
    public static function getRoleAssociations($profileID)
    {
        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);

        $query->select('role_associationID')
            ->from('#__thm_groups_profile_associations')
            ->where("profileID = $profileID");

        $dbo->setQuery($query);

        try {
            $assocs = $dbo->loadColumn();
        }
        catch (Exception $exception) {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return [];
        }

        return empty($assocs) ? [] : $assocs;
    }

    /**
     * Creates the HTML for the name container
     *
     * @param   int  $profileID  the id of the profile
     *
     * @return string the HTML string containing name information
     * @throws Exception
     */
    public static function getVCardLink($profileID)
    {
        $icon = '<span class="icon-vcard" title="' . Text::_('VCARD_DOWNLOAD') . '"></span>';
        $url  = THM_GroupsHelperRouter::build(['view' => 'profile', 'profileID' => $profileID, 'format' => 'vcf']);

        return JHtml::link($url, $icon);
    }

    /**
     * Parses the given string to check for a valid profile
     *
     * @param   string  $potentialProfile  the segment being checked
     *
     * @return mixed int the id if a distinct profile was found, string if no distinct profile was found, otherwise 0
     * @throws Exception
     */
    public static function resolve($potentialProfile)
    {
        if (is_numeric($potentialProfile)) {
            $profileID = $potentialProfile;
        } // Corrected pre 3.8 URL formatting
        elseif (preg_match('/^(\d+)\-([a-zA-Z\-]+)(\-\d+)*$/', $potentialProfile, $matches)) {
            $profileID      = $matches[1];
            $potentialAlias = $matches[2];
        } // Original faulty URL formatting
        elseif (preg_match('/^\d+-(\d+)-([a-zA-Z\-]+)$/', $potentialProfile, $matches)) {
            $profileID      = $matches[1];
            $potentialAlias = $matches[2];
        }

        if (!empty($profileID) and !empty($potentialAlias) and $profileID != Users::idByAlias($potentialAlias)) {
            return 0;
        }

        $potentialAlias = empty($potentialAlias) ? $potentialProfile : $potentialAlias;
        if (empty($profileID) or !is_numeric($profileID)) {
            $profileID = Users::idByAlias($potentialAlias);
        }

        if ($profileID and is_numeric($profileID)) {
            if (is_numeric($profileID)) {
                return Users::published($profileID) ? $profileID : 0;
            } // Disambiguation necessary
            elseif (is_string($profileID)) {
                return $profileID;
            }
        }

        return 0;
    }
}
