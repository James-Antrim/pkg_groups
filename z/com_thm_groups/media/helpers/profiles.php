<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{HTML, Text};
use THM\Groups\Helpers\{Attributes, Profiles as Helper, Templates, Types};

require_once 'attributes.php';
require_once 'router.php';
require_once JPATH_ROOT . '/administrator/components/com_thm_groups/tables/profiles.php';
require_once JPATH_ROOT . '/administrator/components/com_thm_groups/tables/profile_attributes.php';

/**
 * Class providing helper functions for profiles.
 */
class THM_GroupsHelperProfiles
{
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
