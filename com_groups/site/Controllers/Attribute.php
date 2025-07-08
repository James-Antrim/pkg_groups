<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Attributes as Helper;
use THM\Groups\Helpers\Icons;
use THM\Groups\Helpers\Types;

class Attribute extends FormController
{
    protected string $list = 'Attributes';

    /** @inheritDoc */
    protected function prepareData(): array
    {
        $context = Input::integer('context');
        $icon    = Input::string('icon');

        $data = [
            'context'     => in_array($context, Helper::CONTEXTS) ? $context : Helper::BOTH_CONTEXTS,
            'icon'        => Icons::supported($icon),
            'label_de'    => Input::string('label_de'),
            'label_en'    => Input::string('label_en'),
            'showIcon'    => (int) Input::bool('showIcon'),
            'showLabel'   => (int) Input::bool('showLabel'),
            'viewLevelID' => Input::integer('viewLevelID', Helper::PUBLIC)
        ];

        $typeID         = Input::integer('typeID');
        $typeID         = array_key_exists($typeID, Types::TYPES) ? $typeID : Types::TEXT;
        $data['typeID'] = $typeID;

        $options = [];

        self::setArrayOption($options, 'accept');
        self::setBoolOption($options, 'buttons');
        self::setBoolOption($options, 'codeFirst');
        self::setBoolOption($options, 'countryNext');
        self::setBoolOption($options, 'linked');
        self::setIntOption($options, 'linkType');
        self::setArrayOption($options, 'hide');
        self::setStringOption($options, 'hint_de');
        self::setStringOption($options, 'hint_en');
        self::setIntOption($options, 'maxLength');
        self::setIntOption($options, 'maxRows');
        self::setBoolOption($options, 'showCountry');

        // Multiple select with all option => if all then only all
        if (isset($options['accept']) and in_array('image/*,.pdf', $options['accept'])) {
            $options['accept'] = ['image/*,.pdf'];
        }

        $data['options'] = json_encode($options);

        return $data;
    }

    /**
     * Checks the form for the data for a given field with string value.
     *
     * @param   array   $options  the array where the options are stored
     * @param   string  $option   the name of the option to check for
     *
     * @return void modifies $options as necessary
     */
    private function setArrayOption(array &$options, string $option): void
    {
        if ($values = Input::array($option)) {
            $options[$option] = $values;
        }
    }

    /**
     * Checks the form for the data for a given field with string value.
     *
     * @param   array   $options  the array where the options are stored
     * @param   string  $option   the name of the option to check for
     *
     * @return void modifies $options as necessary
     */
    private function setBoolOption(array &$options, string $option): void
    {
        if (Input::bool($option)) {
            $options[$option] = 1;
        }
    }

    /**
     * Checks the form for the data for a given field with string value.
     *
     * @param   array   $options  the array where the options are stored
     * @param   string  $option   the name of the option to check for
     *
     * @return void modifies $options as necessary
     */
    private function setIntOption(array &$options, string $option): void
    {
        if ($value = Input::integer($option)) {
            $options[$option] = $value;
        }
    }

    /**
     * Checks the form for the data for a given field with string value.
     *
     * @param   array   $options  the array where the options are stored
     * @param   string  $option   the name of the option to check for
     *
     * @return void modifies $options as necessary
     */
    private function setStringOption(array &$options, string $option): void
    {
        if ($value = Input::string($option)) {
            $options[$option] = $value;
        }
    }
}