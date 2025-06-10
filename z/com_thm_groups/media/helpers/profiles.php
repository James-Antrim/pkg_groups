<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{Database as DB, HTML, Text};
use THM\Groups\Helpers\{Attributes, Profiles as Helper, Types, Users};
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
        $query = DB::query();
        $query->insert('#__thm_groups_profile_associations')
            ->columns(['profileID', 'role_associationID'])
            ->values("$profileID, $assocID");
        DB::set($query);

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
        $ntData = Helper::namesAndTitles($profileID, $withTitle, $withSpan);

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
     * Retrieves the id of the profile associated with the given alias.
     *
     * @param   string  $username  the username
     *
     * @return mixed int the profile id on distinct success, string if multiple profiles were found inconclusively,
     * otherwise 0
     *
     * @throws Exception
     */
    public static function getProfileIDByUserName(string $username): int
    {
        $query = DB::query();
        $query->select('DISTINCT p.id')
            ->from('#__thm_groups_profiles AS p')
            ->innerJoin('#__users AS u on u.id = p.id')
            ->where(DB::qc('u.username', $username, '=', true));

        DB::set($query);

        return DB::integer() ?: 0;
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
    public static function getRawProfile(int $profileID, bool $published = true): array
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
    public static function getRoleAssociations(int $profileID): array
    {
        $query = DB::query();
        $query->select('role_associationID')
            ->from('#__groups_profile_associations')
            ->where("profileID = $profileID");
        DB::set($query);

        return DB::integers() ?: [];
    }

    /**
     * Creates the HTML for the name container
     *
     * @param   int  $profileID  the id of the profile
     *
     * @return string the HTML string containing name information
     * @throws Exception
     */
    public static function getVCardLink(int $profileID): string
    {
        $icon = '<span class="icon-vcard" title="' . Text::_('VCARD_DOWNLOAD') . '"></span>';
        $url  = THM_GroupsHelperRouter::build(['view' => 'profile', 'profileID' => $profileID, 'format' => 'vcf']);

        return HTML::link($url, $icon);
    }

    /**
     * Parses the given string to check for a valid profile
     *
     * @param   string  $potentialProfile  the segment being checked
     *
     * @return int|string int the id if a distinct profile was found, string if no distinct profile was found, otherwise 0
     */
    public static function resolve($potentialProfile): int|string
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
