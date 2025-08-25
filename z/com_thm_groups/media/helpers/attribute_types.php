<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\Database as DB;
use THM\Groups\Adapters\Form;

require_once 'fields.php';

/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperAttribute_Types
{
    /**
     * Configures the form for the relevant attribute type
     *
     * @param   int   $typeID      the id of the attribute type to be configured to
     * @param   Form  $form        the form being modified
     * @param   bool  $inTypeForm  whether the function was called from the type form context
     *
     * @return void
     */
    public static function configureForm(int $typeID, Form $form, bool $inTypeForm = false): void
    {
        $fieldID = self::getFieldID($typeID);
        THM_GroupsHelperFields::configureForm($fieldID, $form);

        if ($inTypeForm) {
            $options = self::getOptions($typeID);
            foreach ($options as $option => $value) {
                $form->setValue($option, null, $value);
            }

            // Predefined types
            if (in_array($typeID, [TEXT, EDITOR, URL, IMAGE, DATE_EU, EMAIL, TELEPHONE, NAME, SUPPLEMENT])) {

                // The name
                $form->setFieldAttribute('type', 'readonly', 'true');
                foreach ($options as $option => $value) {
                    $form->setFieldAttribute($option, 'readonly', 'true');
                }
            }

            // Not editable once set
            if ($typeID) {
                $form->setFieldAttribute('fieldID', 'readonly', 'true');
            }
        }

        if ($typeID == IMAGE) {
            $form->setFieldAttribute('showIcon', 'readonly', 'true');
            $form->setFieldAttribute('showLabel', 'readonly', 'true');
            $form->setFieldAttribute('accept', 'readonly', 'true');
        }

    }

    /**
     * Retrieves the ID of the field type associated with the abstract attribute
     *
     * @param   int  $typeID  the id of the abstract attribute
     *
     * @return int
     */
    public static function getFieldID(int $typeID): int
    {
        $query = DB::query()->select(DB::qn('fieldID'))->from(DB::qn('#__groups_attribute_types'))->where(DB::qc('id', $typeID));
        DB::set($query);
        return DB::integer();
    }

    /**
     * Returns specific field type options mapped with attribute type data and optionally mapped with form data
     *
     * @param   int  $typeID
     *
     * @return  array
     */
    public static function getOptions(int $typeID): array
    {
        $query = DB::query()->select(DB::qn('options'))->from(DB::qn('#__groups_attribute_types'))->where(DB::qc('id', $typeID));
        DB::set($query);

        if (!$options = DB::string()) {
            return [];
        }

        $options = json_decode($options, true);

        if (!is_array($options)) {
            return [];
        }

        foreach ($options as $property => $value) {
            if ($value !== '') {
                $options[$property] = $value;
            }
        }

        return THM_GroupsHelperFields::getOptions(self::getFieldID($typeID), $options);
    }
}
