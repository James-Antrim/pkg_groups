<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{Application, Database as DB, Form, Input, Text};
use THM\Groups\Helpers\{Attributes as Helper, Profiles};

require_once 'attribute_types.php';
require_once 'fields.php';

/**
 * Class providing helper functions for attributes.
 */
class THM_GroupsHelperAttributes
{
    /**
     * Configures the form for the relevant attribute type
     *
     * @param   int   $attributeID  the id of the attribute to be configured to
     * @param   Form  $form         the form being modified
     *
     * @return void configures the form for the relevant field
     */
    public static function configureForm(int $attributeID, Form $form): void
    {
        $typeID = Helper::typeID($attributeID);

        THM_GroupsHelperAttribute_Types::configureForm($typeID, $form);

        $options = Helper::parameters($attributeID);
        foreach ($options as $option => $value) {
            $form->setValue($option, null, $value);
        }

        // Not editable once set
        if ($attributeID) {
            $form->setFieldAttribute('typeID', 'readonly', 'true');
        }

        // The surname is always required.
        if ($attributeID == SURNAME or $attributeID == EMAIL_ATTRIBUTE) {
            $form->setFieldAttribute('required', 'readonly', 'true');
        }

        // Special handling => no labeling
        if (in_array($attributeID, [FORENAME, SURNAME, TITLE, POSTTITLE]) or $typeID == IMAGE) {
            $form->setFieldAttribute('showIcon', 'readonly', 'true');
            $form->setFieldAttribute('showLabel', 'readonly', 'true');
            $form->removeField('icon');
        }

        // Joomla derived attributes are always published at the basic level
        if (in_array($attributeID, [FORENAME, SURNAME, EMAIL_ATTRIBUTE])) {
            $form->setFieldAttribute('published', 'readonly', 'true');
        }

        // Always public access
        if (in_array($attributeID, [FORENAME, SURNAME, EMAIL_ATTRIBUTE, TITLE, POSTTITLE])) {
            $form->setFieldAttribute('viewLevelID', 'readonly', 'true');
        }

        if (in_array($attributeID, [FORENAME, SURNAME, EMAIL_ATTRIBUTE, TITLE, POSTTITLE])) {
            $form->setFieldAttribute('label', 'readonly', 'true');
            foreach ($options as $option => $value) {
                $form->setFieldAttribute($option, 'readonly', 'true');
            }
        }
    }

    /**
     * Gets the HTML for displaying the attribute
     *
     * @param $attribute
     * @param $suppress
     *
     * @return array|string|string[]
     */
    public static function getDisplay($attribute, $suppress): array|string
    {
        $html = '<div class="attribute-DISPLAYTYPE">LABELCONTENTS<div class="clearFix"></div></div>';

        if ($attribute['typeID'] == IMAGE) {
            $html  = str_replace('DISPLAYTYPE', 'image', $html);
            $label = '';
        }
        else {
            if ($attribute['typeID'] == HTML) {
                $html = str_replace('DISPLAYTYPE', 'html', $html);
            }
            else {
                $html = str_replace('DISPLAYTYPE', 'wrap', $html);
            }

            $label = THM_GroupsHelperAttributes::getLabel($attribute);
        }

        $contents = '<div class="attribute-value attribute-LABELTYPE">VALUE<div class="clearFix"></div></div>';

        if (empty($label)) {
            $contents = str_replace('LABELTYPE', 'no-label', $contents);
        }
        elseif (empty(strip_tags($label))) {
            $contents = str_replace('LABELTYPE', 'iconed', $contents);
        }
        else {
            $visibleLength = strlen(strip_tags($label));
            if ($visibleLength > 10) {
                $contents = str_replace('LABELTYPE', 'break', $contents);
            }
            else {
                $contents = str_replace('LABELTYPE', 'labeled', $contents);
            }
        }

        $value    = self::getValueDisplay($attribute, $suppress);
        $contents = str_replace('VALUE', $value, $contents);

        $html = str_replace('LABEL', $label, $html);

        return str_replace('CONTENTS', $contents, $html);
    }

    /**
     * Retrieves an image attribute
     *
     * @param   array  $attribute    the attribute
     * @param   int    $profileID    the profileID
     * @param   int    $attributeID  the attributeID
     *
     * @return string the image HTML
     */
    public static function getImage(array $attribute, int $profileID = 0, int $attributeID = 0): string
    {
        if ($attribute and !empty($attribute['value'])) {
            $value = $attribute['value'];
        }
        elseif ($profileID and $attributeID) {
            $query = DB::query();
            $query->select('value')
                ->from('#__thm_groups_profile_attributes')
                ->where("profileID = $profileID")
                ->where("attributeID = $attributeID");
            DB::set($query);

            $value = DB::string();

        }
        else {
            return '';
        }

        if (empty($value)) {
            return '';
        }

        $relativePath = Helper::IMAGE_PATH . $value;
        $file         = JPATH_ROOT . "/$relativePath";

        if (file_exists($file)) {

            // This can often prevent the browser from using a cached image with the same 'name'
            $random = rand(1, 100);
            $url    = JUri::root() . $relativePath . "?force=$random";

            $alt = empty($profileID) ? Text::_('PROFILE_IMAGE') : Profiles::name($profileID);

            return JHtml::image($url, $alt, ['class' => 'edit_img']);
        }

        return '';
    }

    /**
     * Creates an input aggregate for the given attribute
     *
     * @param   int  $attributeID  the id of the attribute filled by the input
     * @param   int  $profileID    the id of the profile with which the attribute is associated
     *
     * @return string the HTML of the attribute input aggregate
     */
    public static function getInput(int $attributeID, int $profileID): string
    {
        $attribute = Helper::raw($attributeID, $profileID, false);

        $label            = self::getLabel($attribute, true);
        $input            = THM_GroupsHelperFields::getInput($profileID, $attribute);
        $isSite           = !Application::backend();
        $textBased        = in_array($attribute['fieldID'], [TEXT, URL, EMAIL, TELEPHONE]);
        $inline           = ((!$isSite and $textBased) or ($isSite and !$textBased));
        $publishInput     = self::getPublishInput($attribute, $inline);
        $viewLevelDisplay = self::getViewLevelDisplay($attribute, $inline);
        $supplement       = '<div class="control-supplement">' . $publishInput . $viewLevelDisplay . '<div class="clearFix"></div></div>';

        $controlLabel = '<div class="control-label">XXXX</div>';
        if (!$textBased) {
            // Supplement behind the label
            if ($isSite) {
                $controlLabel = str_replace('XXXX', $label, $controlLabel);
                $controlLabel .= $supplement;
            } // Supplement in the label
            else {
                $controlLabel = str_replace('XXXX', $label . $supplement, $controlLabel);
            }
        }
        else {
            $controlLabel = str_replace('XXXX', $label, $controlLabel);
        }

        $controlInput = '<div class="controls">' . $input . '</div>';
        if ($textBased) {
            $controlInput .= $supplement;
        }

        $groupClass   = ($isSite and $textBased) ? 'control-group control-site-text' : 'control-group control-default';
        $controlGroup = '<div class="' . $groupClass . '">XXXX</div>';

        return str_replace('XXXX', $controlLabel . $controlInput, $controlGroup);
    }

    /**
     * Creates a label for an attribute
     *
     * @param   array  $attribute  the attribute to be labeled
     * @param   bool   $form       whether the label will be displayed in the profile edit form
     *
     * @return string the html for the label
     */
    public static function getLabel(array $attribute, bool $form = false): string
    {
        $html  = '';
        $label = $attribute['label'];

        if ($form) {
            $label .= empty($attribute['required']) ? '' : '*';
        }

        if ($form) {
            $html .= '<label id="jform_' . $attribute['id'] . '-lbl" for="jform_' . $attribute['id'] . '_value" aria-invalid="false">';
            $html .= $label . '</label >';
        }
        else {
            $showIcon = (!empty($attribute['icon']) and !empty($attribute['showIcon']));
            if ($showIcon) {
                $html .= '<div class="attribute-label">';
                $html .= '<span class="' . $attribute['icon'] . '" title="' . $label . '"></span>';
                $html .= '</div>';
            }
            elseif ($attribute['showLabel']) {
                if ($attribute['fieldID'] == HTML) {
                    $html .= '<h3>' . Text::_($attribute['label']) . '</h3>';
                }
                else {
                    $html .= '<div class="attribute-label">' . Text::_($attribute['label']) . '</div>';
                }
            }
        }

        return $html;
    }

    /**
     * Creates a checkbox for the published status of the attribute being iterated
     *
     * @param   array  $attribute  the attribute for which the published checkbox is to be displayed
     * @param   bool   $inline     whether the attribute is text based
     *
     * @return  string  the HTML checkbox output
     */
    private static function getPublishInput(array $attribute, bool $inline = false): string
    {
        $class = $inline ? 'published-container inline' : 'published-container wrap';
        $html  = '<div class="' . $class . '">';
        $label = '<span class="published-label">' . Text::_('PUBLISHED') . ':</span>';

        $type    = 'type="checkbox"';
        $id      = 'id="jform_' . $attribute['id'] . '_published" ';
        $name    = 'name="jform[' . $attribute['id'] . '][published]" ';
        $class   = 'class="hasTip" ';
        $title   = 'title="' . Text::_('PUBLISHED') . '" ';
        $checked = empty($attribute['published']) ? '' : 'checked="checked"';
        $input   = "<input $type $id $name $class $title $checked />";

        $html .= $label . $input . '</div>';

        return $html;
    }

    /**
     * Creates the container for the attribute value
     *
     * @param   array  $attribute  the profile attribute being iterated
     * @param   bool   $suppress   whether lengthy text should be initially hidden.
     *
     * @return string the HTML for the value container
     */
    private static function getValueDisplay(array $attribute, bool $suppress = true): string
    {
        $columns = (int) Input::parameters()->get('columns');

        // Advanced view or module
        if (!empty($columns)) {
            $maxLength = $columns === 2 ? 28 : 50;
        }
        else {
            $maxLength = 20;
        }
        $suppressionTemplate = '<div class="hasTooltip suppress" title="TIPVALUE">DISPLAYVALUE</div>';


        switch ($attribute['typeID']) {
            case EMAIL:

                $mailTo = JHTML::_('email.cloak', $attribute['value']);
                if ($suppress) {

                    $exceedsLength = strlen($attribute['value']) > $maxLength;

                    if ($exceedsLength) {
                        $emailDisplay = str_replace('TIPVALUE', $attribute['value'], $suppressionTemplate);

                        return str_replace('DISPLAYVALUE', $mailTo, $emailDisplay);
                    }
                }

                return $mailTo;


            case HTML:

                $value = Input::removeEmptyTags($attribute['value']);
                $value = trim(htmlspecialchars_decode($value));

                // The closing div for the toggled container is added later to ensure that the toggle label doesn't move
                if ($suppress and strlen(strip_tags($value)) > $maxLength) {
                    $html = '<span class="toggled-text-link">' . Text::_('ACTION_DISPLAY') . '</span>';
                    $html .= '</div>';
                    $html .= '<div class="toggled-text-container" style="display:none;">' . $value;
                }
                else {
                    $html = $value;
                }

                return $html;

            case IMAGE:

                $fileName     = strtolower(trim($attribute['value']));
                $relativePath = Helper::IMAGE_PATH . $fileName;
                $file         = JPATH_ROOT . "/$relativePath";

                if (file_exists($file)) {
                    $url        = JUri::root() . $relativePath;
                    $attributes = ['class' => 'thm_groups_profile_container_profile_image'];

                    return JHTML::image($url, Profiles::name($attribute['profileID']),
                        $attributes);
                }

                return '';

            case URL:
                $protocolled = str_contains($attribute['value'], '://');
                $URL         = $protocolled ? $attribute['value'] : "https://{$attribute['value']}";
                $link        = JHtml::link($URL, $attribute['value'], ['target' => '_blank']);

                if ($suppress and strlen(strip_tags($URL)) > $maxLength) {
                    $emailDisplay = str_replace('TIPVALUE', $URL, $suppressionTemplate);

                    return str_replace('DISPLAYVALUE', $link, $emailDisplay);
                }

                return $link;

            case TELEPHONE:
            case TEXT:
            default:
                $value = htmlspecialchars_decode($attribute['value']);
                if ($suppress and strlen($value) > $maxLength) {
                    $display = str_replace('TIPVALUE', $value, $suppressionTemplate);

                    return str_replace('DISPLAYVALUE', $value, $display);
                }

                return $value;
        }
    }

    /**
     * Creates the HTML for the display of the attribute view level
     *
     * @param   array  $attribute  the attribute for which to display the view level
     * @param   bool   $inline     whether the attribute is text based
     *
     * @return string the html of the view level display
     */
    private static function getViewLevelDisplay(array $attribute, bool $inline = false): string
    {
        $class = $inline ? 'view-level-container inline' : 'view-level-container wrap';
        $html  = '<div class="' . $class . '">';
        $html  .= Text::_('VIEW_LEVEL') . ': ';
        if (empty($attribute['viewLevel']) or $attribute['viewLevel'] == $attribute['defaultLevel']) {
            $html .= '<span class="public-access">' . $attribute['defaultLevel'] . '</span>';
        }
        else {
            $html .= '<span class="restricted-access">' . $attribute['viewLevel'] . '</span>';
        }
        $html .= '</div>';

        return $html;
    }
}
