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
use THM\Groups\Helpers\Icons as Helper;

/**
 * Provides a list of context relevant groups.
 */
class Icons extends ListField
{
    protected $type = 'Icons';

    /**
     * @inheritDoc
     */
    public function getInput(): string
    {
        Helper::checkIcons();

        return parent::getInput();
    }

    /**
     * @inheritDoc
     */
    protected function getOptions(): array
    {
        $options = Helper::getOptions();

        foreach ($options as $option)
        {
            echo "<span class=\"$option->value\"> $option->text</span>";
        }

        return $options;
    }
}