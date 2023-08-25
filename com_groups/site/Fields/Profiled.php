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

use THM\Groups\Adapters\Application;

/**
 * Class provides a standardized output format for fields whose data is used in profile displays.
 */
trait Profiled
{
    /**
     * Method to get a control group with label and input.
     *
     * @param array $options Options to be passed into the rendering of the field
     *
     * @return  string  A string containing the html for the control group
     *
     * @since   3.2
     */
    public function renderField($options = [])
    {
        /**
         * Layout variables
         * -----------------
         * @var   array $options Optional parameters
         * @var   string $id The id of the input this label is for
         * @var   string $name The name of the input this label is for
         * @var   string $label The html code for the label
         * @var   string $input The input field html code
         * @var   string $description An optional description to use as in–line help text
         * @var   string $descClass The class name to use for the description
         */

        if (!empty($options['showonEnabled'])) {
            $wa = Application::getDocument()->getWebAssetManager();
            $wa->useScript('showon');
        }

        $group      = '<div class="XCLASSX">XCONTENTSX</div>';
        $input      = '<div class="controls">XINPUTX</div>';
        $label      = '<div class="control-label">XLABELX</div>';
        $supplement = '<div class="control-supplement">XSUPPLEMENTX</div>';

        $groupClass = 'control-group';
        //TODO differentiate text based fields and add a supplemental class
        $group = str_replace('XCLASSX', $groupClass, $group);

        $label = $this->getLabel();
        $class = empty($options['class']) ? '' : ' ' . $options['class'];
        $id    = ($id ?? $name) . '-desc';

        if (!empty($parentclass)) {
            $class .= ' ' . $parentclass;
        }

        return str_replace('CONTENTS', $label . $input, $group);
    }

}