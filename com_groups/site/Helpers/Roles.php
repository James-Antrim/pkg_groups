<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use Joomla\CMS\Language\Text;
use THM\Groups\Adapters\Application;

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Roles implements Selectable
{
    use Named, Persistent;

    /**
     * @inheritDoc
     *
     * @param   bool  $bound  whether the role must already be associated
     */
    public static function options(bool $associated = true): array
    {
        $plural    = 'plural_' . Application::tag();
        $options   = [];
        $options[] = (object) [
            'text'  => Text::_('GROUPS_NONE_MEMBER'),
            'value' => ''
        ];

        foreach (self::resources() as $roleID => $role) {
            if ($associated and empty($role->groups)) {
                continue;
            }

            $options[] = (object) [
                'text'  => $role->$plural,
                'value' => $roleID
            ];
        }

        return $options;
    }

    /** @inheritDoc */
    public static function resources(): array
    {
        $db     = Application::database();
        $query  = $db->getQuery(true);
        $plural = $db->quoteName('plural_' . Application::tag());
        $roles  = $db->quoteName('#__groups_roles');
        $query->select('*')->from($roles)->order($plural);
        $db->setQuery($query);

        if (!$roles = $db->loadObjectList('id')) {
            return [];
        }

        foreach ($roles as $roleID => $role) {
            $role->groups = RoleAssociations::byRoleID($roleID);
        }

        return $roles;
    }
}