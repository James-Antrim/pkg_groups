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
use THM\Groups\Adapters\Database as DB;

/**
 * Provides a list of view levels.
 */
class ViewLevels extends ListField
{
    /**
     * Method to get the group options.
     *
     * @return  array  the group option objects
     */
    protected function getOptions(): array
    {
        $defaultOptions = parent::getOptions();

        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $levels = $db->quoteName('#__viewlevels', 'vl');
        $rules  = $db->quoteName('vl.rules');
        $text   = $db->quoteName('vl.title', 'text');
        $title  = $db->quoteName('vl.title');
        $value  = 'DISTINCT ' . $db->quoteName('vl.id', 'value');

        $context = $this->form->getName();
        $query->select([$value, $text])->from($levels)->order($title);

        if ($context === 'com_groups.attributes.filter') {
            $attributes = $db->quoteName('#__groups_attributes', 'a');
            $condition  = $db->quoteName('a.viewLevelID') . ' = ' . $db->quoteName('vl.id');
            $query->join('inner', $attributes, $condition);
        }

        if ($context === 'com_groups.groups.filter') {
            $query->where("$rules != '[]'");
        }

        $db->setQuery($query);

        return array_merge($defaultOptions, DB::objects());
    }
}