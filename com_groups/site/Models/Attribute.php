<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use THM\Groups\Adapters\{Application, Input};
use Joomla\CMS\Form\Form;
use Joomla\CMS\Object\CMSObject;
use THM\Groups\Helpers\Attributes as Helper;
use THM\Groups\Helpers\{Can, Icons, Types};
use THM\Groups\Tables\Attributes as Table;

class Attribute extends EditModel
{
    protected string $tableClass = 'Attributes';

    /**
     * @inheritDoc
     */
    public function getForm($data = array(), $loadData = true): ?Form
    {
        if (!$form = parent::getForm($data, $loadData)) {
            return $form;
        }

        // Types are immutable over this interface when previously set
        if ($form->getValue('typeID')) {
            // readonly breaks the showOn function of the form
            $form->setFieldAttribute('typeID', 'disabled', true);
        }

        return $form;
    }

    /**
     * @inheritDoc
     */
    protected function loadFormData(): ?CMSObject
    {
        $item    = $this->getItem();
        $options = empty($item->options) ? [] : json_decode($item->options, true);

        foreach ($options as $property => $value) {
            $property        .= is_array($value) ? '[]' : '';
            $item->$property = $value;
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function save(): int
    {
        if (!Can::administrate()) {
            Application::error(403);
        }

        $context = Input::getInt('context');
        $icon    = Input::getString('icon');

        $data['context']     = in_array($context, Helper::CONTEXTS) ? $context : Helper::BOTH_CONTEXTS;
        $data['icon']        = Icons::supported($icon);
        $data['label_de']    = Input::getString('label_de');
        $data['label_en']    = Input::getString('label_en');
        $data['showIcon']    = (int) Input::getBool('showIcon');
        $data['showLabel']   = (int) Input::getBool('showLabel');
        $data['viewLevelID'] = Input::getInt('viewLevelID', Helper::PUBLIC);

        $typeID         = Input::getInt('typeID');
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

        $id    = Input::getID();
        $table = new Table();

        if ($id and !$table->load($id)) {
            Application::message($table->getError(), Application::ERROR);
            return 0;
        }

        if (!$table->save($data)) {
            Application::message($table->getError(), Application::ERROR);
            return 0;
        }

        return $table->id;
    }

    /**
     * Checks the form for the data for a given field with string value.
     *
     * @param array  $options the array where the options are stored
     * @param string $option  the name of the option to check for
     *
     * @return void modifies $options as necessary
     */
    private function setArrayOption(array &$options, string $option): void
    {
        if ($values = Input::getArray($option)) {
            $options[$option] = $values;
        }
    }

    /**
     * Checks the form for the data for a given field with string value.
     *
     * @param array  $options the array where the options are stored
     * @param string $option  the name of the option to check for
     *
     * @return void modifies $options as necessary
     */
    private function setBoolOption(array &$options, string $option): void
    {
        if (Input::getBool($option)) {
            $options[$option] = 1;
        }
    }

    /**
     * Checks the form for the data for a given field with string value.
     *
     * @param array  $options the array where the options are stored
     * @param string $option  the name of the option to check for
     *
     * @return void modifies $options as necessary
     */
    private function setIntOption(array &$options, string $option): void
    {
        if ($value = Input::getInt($option)) {
            $options[$option] = $value;
        }
    }

    /**
     * Checks the form for the data for a given field with string value.
     *
     * @param array  $options the array where the options are stored
     * @param string $option  the name of the option to check for
     *
     * @return void modifies $options as necessary
     */
    private function setStringOption(array &$options, string $option): void
    {
        if ($value = Input::getString($option)) {
            $options[$option] = $value;
        }
    }
}