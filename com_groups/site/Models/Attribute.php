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

use Joomla\CMS\Form\Form;
use stdClass;

class Attribute extends EditModel
{
    protected string $tableClass = 'Attributes';

    /** @inheritDoc */
    public function getForm($data = [], $loadData = true): ?Form
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

    /** @inheritDoc */
    protected function loadFormData(): ?stdClass
    {
        $item    = $this->getItem();
        $options = empty($item->options) ? [] : json_decode($item->options, true);

        foreach ($options as $property => $value) {
            $property        .= is_array($value) ? '[]' : '';
            $item->$property = $value;
        }

        return $item;
    }
}