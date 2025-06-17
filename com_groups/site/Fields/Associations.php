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
use THM\Groups\Helpers\Groups;
use THM\Groups\Helpers\Roles;

/**
 * Provides a list of context relevant groups.
 */
class Associations extends ListField
{
    protected $type = 'Associations';

    /**
     * Method to get the group options.
     *
     * @return  array  the group option objects
     */
    protected function getOptions(): array
    {
        $defaultOptions = parent::getOptions();
        $options        = [];

        foreach (Groups::resources() as $groupID => $group) {
            $prefix    = strip_tags(Groups::prefix($group->level));
            $options[] = (object) [
                'text'  => $prefix . $group->title,
                'value' => "groupID-$groupID"
            ];

            // No role associations (Joomla core groups) or only member role available
            if (empty($group->roles) or count($group->roles) === 1) {
                continue;
            }

            foreach ($group->roles as $assocID => $roleID) {
                $prefix = strip_tags(Groups::prefix($group->level));

                // Remove &ndash;&nbsp;
                $prefix = substr($prefix, 0, strlen($prefix) - 13);

                // Add "® "
                $prefix .= '&#8942;&nbsp;&nbsp;&nbsp;&reg;&nbsp;';

                $options[] = (object) [
                    'text'  => $prefix . Roles::getName($roleID) . " ($group->title)",
                    'value' => "assocID-$assocID"
                ];
            }
        }

        return array_merge($defaultOptions, $options);
    }
}