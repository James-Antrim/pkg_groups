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
use SimpleXMLElement;
use THM\Groups\Adapters\Database as DB;

/**
 * Provides a list of context relevant groups.
 */
class ExcludedButtons extends ListField
{
    protected $type = 'ExcludedButtons';

    /**
     * Method to get the group options.
     *
     * @return  array  the group option objects
     */
    protected function getOptions(): array
    {
        $defaultOptions = parent::getOptions();

        $db         = $this->getDatabase();
        $extensions = $db->quoteName('#__extensions');
        $element    = $db->quoteName('element');
        $name       = $db->quoteName('name');
        $text       = $db->quoteName('element', 'text');
        $value      = 'DISTINCT ' . $db->quoteName('element', 'value');

        $query = $db->getQuery(true);
        $query->select([$value, $text])->from($extensions)->where("$name LIKE 'plg_editors-xtd%'")->order($element);
        $db->setQuery($query);

        $options = DB::objects();

        foreach ($options as $option) {
            $option->text = ucwords($option->text, " _-\t\r\n\f\v");
            $option->text = str_replace('_', ' ', $option->text);
        }

        return array_merge($defaultOptions, $options);
    }

    /** @inheritDoc */
    public function setup(SimpleXMLElement $element, $value, $group = null): bool
    {
        if (!parent::setup($element, $value, $group)) {
            return false;
        }

        $this->multiple = true;
        $this->size     = 10;

        return true;
    }
}