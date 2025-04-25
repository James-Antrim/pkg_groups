<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\FormField;

class Date extends FormField
{
    use Profiled;

    protected $type = 'Date';

    /** @inheritDoc */
    protected function getInput(): string
    {
        $attributes = [
            $this->autofocus ? 'autofocus' : '',
            $this->class ? "class=\"$this->class\"" : '',
            $this->disabled ? 'disabled' : '',
            "id=\"$this->id\"",
            "name=\"$this->name\"",
            $this->onchange ? "onChange=\"$this->onchange\"" : '',
            $this->readonly ? 'readonly' : '',
            $this->required ? 'required aria-required="true"' : '',
            'type="date"',
            'value="' . $value . '"'
        ];

        return '<input ' . implode(' ', $attributes) . '/>';
    }
}
/*
 <div class="control-group">
            <div class="control-label"><label id="jform_name-lbl" for="jform_name" class="required">
    Name<span class="star" aria-hidden="true">&nbsp;*</span></label>
</div>
        <div class="controls has-success">



    <input type="text" name="jform[name]" id="jform_name" value="Alexander Dworschak" class="form-control required valid form-control-success" required="" aria-invalid="false">



            </div>
</div>
 */