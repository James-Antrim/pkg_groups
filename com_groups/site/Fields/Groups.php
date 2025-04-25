<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\Field\ListField;
use THM\Groups\Helpers\Groups as Helper;

/**
 * Provides a list of context relevant groups.
 */
class Groups extends ListField
{
    protected $type = 'Groups';

    /**
     * Method to get the group options.
     *
     * @return  array  the group option objects
     */
    protected function getOptions(): array
    {
        $defaultOptions = parent::getOptions();
        $allowDefault   = (bool) $this->getAttribute('allowDefault');
        $options        = Helper::options($allowDefault);

        return array_merge($defaultOptions, $options);
    }
}