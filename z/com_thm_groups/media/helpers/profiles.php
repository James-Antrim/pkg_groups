<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{Database as DB, HTML, Text};
use THM\Groups\Helpers\{Attributes, Profiles as Helper, Templates, Types};
use THM\Groups\Controllers\Profile;
use THM\Groups\Tables\Users;

require_once 'attributes.php';
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
     * @return int
     */
    public static function associateRole(int $profileID, int $assocID): int
    {
        if ($existingID = THM_GroupsHelperRoles::getAssocID($assocID, $profileID, 'profile')) {
            return $existingID;
        }

        $table = new Users();

        // Profile is new
        if (!$table->load($profileID) and empty($table->surnames)) {
            Profile::create($profileID);
        }

        $query = DB::query();
        $query->insert(DB::qn('#__groups_profile_associations'))
            ->columns(['profileID', 'role_associationID'])
            ->values([$profileID, $assocID]);
        DB::set($query);
        DB::execute();

        return THM_GroupsHelperRoles::getAssocID($assocID, $profileID, 'profile');
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
     */
    public static function getDisplay(int $profileID, int $templateID = 0, bool $suppress = false, bool $showImage = true): string
    {
        $preRendered     = [Attributes::SUPPLEMENT_PRE, Attributes::SUPPLEMENT_POST];
        $attributes      = [];
        $imageAttributes = [];

        $attributeIDs = Templates::attributeIDs($templateID);

        foreach ($attributeIDs as $attributeID) {

            if (in_array($attributeID, $preRendered)) {
                continue;
            }

            $attribute = Attributes::raw($attributeID, $profileID);

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
     */
    public static function getNameContainer(int $profileID, bool $newTab = false): string
    {
        $text    = Helper::name($profileID, true, true);
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
     * @return mixed
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
     * Gets the role association ids associated with the profile
     *
     * @param   int  $profileID  the id of the profile
     *
     * @return array the role association ids associated with the profile
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
     */
    public static function getVCardLink(int $profileID): string
    {
        $icon = '<span class="icon-vcard" title="' . Text::_('VCARD_DOWNLOAD') . '"></span>';
        $url  = THM_GroupsHelperRouter::build(['view' => 'profile', 'profileID' => $profileID, 'format' => 'vcf']);

        return HTML::link($url, $icon);
    }
}
