<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      Ilja Michajlow, <ilja.michajlow@mni.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\Editor\Editor;
use THM\Groups\Adapters\{Application, Database as DB, Form, Text};

/**
 * Class providing options
 */
class THM_GroupsHelperFields
{
    /**
     * Configures the form for the relevant field
     *
     * @param   int   $fieldID  the id of the field to be configured to
     * @param   Form  $form     the form being modified
     *
     * @return void
     */
    public static function configureForm(int $fieldID, Form $form): void
    {
        // Remove unique irrelevant field property fields
        if ($fieldID != CALENDAR) {
            $form->removeField('calendarformat');
            $form->removeField('showtime');
            $form->removeField('timeformat');
        }
        if ($fieldID != EDITOR) {
            $form->removeField('buttons');
            $form->removeField('hide');
        }
        if ($fieldID != FILE) {
            $form->removeField('accept');
            $form->removeField('mode');
        }

        $textBased = ($fieldID == EMAIL or $fieldID == TELEPHONE or $fieldID == TEXT or $fieldID == URL);
        if (!$textBased) {
            $form->removeField('maxlength');
            $form->removeField('hint');
        }

        $html5Based = ($fieldID == EMAIL or $fieldID == TELEPHONE or $fieldID == URL);
        if (!$html5Based) {
            $form->removeField('validate');
        }
    }

    /**
     * Creates an upload/  cropper field for images
     *
     * @param   int    $profileID  the id of the profile
     * @param   array  $attribute  the image attribute
     *
     * @return string
     */
    public static function getCropper(int $profileID, array $attribute): string
    {
        $attributeID = $attribute['id'];
        $mode        = $attribute['mode'];
        $value       = strtolower(trim($attribute['value']));
        $hasPicture  = !empty($value);

        $html = '<div id="image-' . $attributeID . '" class="image-container">';

        if ($hasPicture) {
            $html .= THM_GroupsHelperAttributes::getImage($attribute, $profileID);
        }

        $html .= '</div>';
        $html .= '<input id="jform_' . $attributeID . '_value" name="jform[' . $attributeID . '][value]" type="hidden" value="' . $value . '" />';

        $html .= '<div class="image-button-container">';

        // Upload / Change
        $button        = '<button type="button" id="' . $attributeID . '_upload" class="btn image-button" ';
        $cropperParams = "'$attributeID', '$profileID',  '$mode'";
        $button        .= 'onclick="bindImageCropper(' . $cropperParams . ');" ';
        $button        .= 'data-toggle="modal" data-target="#modal-' . $attributeID . '">';
        if ($hasPicture) {
            $button .= '<span class="icon-edit"></span>';
            $button .= Text::_('IMAGE_BUTTON_CHANGE');
        }
        else {
            $button .= '<span class="icon-upload"></span>';
            $button .= Text::_('IMAGE_BUTTON_UPLOAD');
        }
        $button .= '</button>';
        $html   .= $button;

        // Delete
        if ($hasPicture) {
            $button = '<button id="' . $attributeID . '_del" class="btn image-button" ';
            $button .= 'onclick="deletePic(\'' . $attributeID . '\', \'' . $profileID . '\');" ';
            $button .= 'type="button">';
            $button .= '<span class="icon-delete"></span>' . Text::_('IMAGE_BUTTON_DELETE');
            $button .= '</button>';
            $html   .= $button;
        }

        $html .= '</div>';

        require_once JPATH_ROOT . '/media/com_thm_groups/layouts/cropper.php';
        $html .= THM_GroupsLayoutCropper::getCropper($attribute);

        return $html;
    }

    /**
     * Creates an input for the given attribute value
     *
     * @param   int    $profileID  the id of the profile with which the attribute is associated
     * @param   array  $attribute  the attribute for which to render the input field
     *
     * @return string
     */
    public static function getInput(int $profileID, array $attribute): string
    {
        $attributeID = $attribute['id'];
        $fieldID     = $attribute['fieldID'];
        $formID      = "jform_{$attribute['id']}_value";
        $name        = "jform[$attributeID][value]";
        $typeID      = $attribute['typeID'];
        $value       = $attribute['value'];

        if ($fieldID === CALENDAR) {
            $attribs = [];
            if (!empty($attribute['regex'])) {
                $attribs['class'] = "validate-{$attribute['regex']}";
            }
            if (!empty($attribute['showtime'])) {
                $attribs['showtime']   = true;
                $attribs['timeformat'] = empty($attribute['timeformat']) ? '24' : $attribute['timeformat'];
            }
            if ($typeID === DATE_EU) {
                $attribs['message'] = Text::_('INVALID_DATE_EU');
            }

            return JHtml::calendar($value, $name, $formID, $attribute['calendarformat'], $attribs);
        }

        if ($fieldID === EDITOR) {
            $editorName = Application::configuration()->get('editor');
            $editor     = Editor::getInstance($editorName);
            $buttons    = $attribute['buttons'] !== '0';
            if ($buttons and !empty($attribute['hide'])) {
                $buttons = explode(',', $attribute['hide']);
            }

            // name, value, width, height, col, row, $buttons = true, $id = null, $asset = null, $author = null, $params = array())
            return $editor->display($name, $value, '', '', '', '', $buttons, $formID);
        }

        if ($fieldID == FILE and $typeID == IMAGE) {
            return self::getCropper($profileID, $attribute);
        }

        $type = match ($attribute['fieldID']) {
            EMAIL => 'email',
            TELEPHONE => 'tel',
            URL => 'url',
            default => 'text',
        };

        $class       = empty($attribute['regex']) ? '' : 'class="validate-' . $attribute['regex'] . '" ';
        $formID      = 'id="' . $formID . '" ';
        $maxLength   = empty($attribute['maxlength']) ? '' : 'maxlength="' . $attribute['maxlength'] . '" ';
        $message     = empty($attribute['message']) ? '' : 'message="' . $attribute['message'] . '" ';
        $name        = 'name="' . $name . '" ';
        $placeHolder = empty($attribute['hint']) ? '' : 'placeholder="' . $attribute['hint'] . '" ';
        $required    = empty($attribute['required']) ? '' : 'required';
        $type        = 'type="' . $type . '"';
        $value       = 'value="' . $value . '" ';

        return "<input $class $formID $maxLength $message $name $placeHolder $required $type $value >";
    }

    /**
     * Returns specific field type options optionally mapped with form data
     *
     * @param   int    $fieldID  the field type id
     * @param   array  $options  the input data to be mapped to configured properties
     *
     * @return  array
     */
    public static function getOptions(int $fieldID, array $options = []): array
    {
        $query = DB::query();
        $query->select('options')->from('#__thm_groups_fields')->where("id = $fieldID");
        DB::set($query);
        $fieldOptions = DB::string();

        $fieldOptions = json_decode($fieldOptions, true);
        if (empty($fieldOptions) or !is_array($fieldOptions)) {
            return [];
        }

        // Only configured field options will be saved to the options column of the resource
        if ($options) {
            foreach ($options as $property => $value) {
                if (isset($fieldOptions[$property]) and $value !== '') {
                    $fieldOptions[$property] = $value;
                }
            }
        }

        return $fieldOptions;
    }
}
