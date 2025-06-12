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

use THM\Groups\Adapters\{Application, Database as DB, Text};

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Roles implements Selectable
{
    use Named, Persistent;

    /**
     * Retrieves a list
     *
     * @param   int  $userID
     * @param   int  $groupID
     *
     * @return string[]
     */
    public static function mapped(int $userID, int $groupID): array
    {
        $role = 'name_' . Application::tag();

        $query = DB::query();
        $query->select(DB::qn($role))
            ->from(DB::qn('#__groups_roles', 'r'))
            ->innerJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.roleID', 'r.id'))
            ->innerJoin(DB::qn('#__user_usergroups_map', 'uugm'), DB::qc('uugm.id', 'ra.mapID'))
            ->where(DB::qcs([['uugm.group_id', $groupID], ['uugm.user_id', $userID]]));
        DB::set($query);

        return DB::column();
        //'<div class="attribute-wrap attribute-roles">' . implode(', ', $roles) . '</div>' : '';
    }

    /**
     * @inheritDoc
     *
     * @param   bool  $bound  whether the role must already be associated
     */
    public static function options(bool $associated = true): array
    {
        $plural    = 'plural_' . Application::tag();
        $options   = [];
        $options[] = (object) ['text' => Text::_('NONE_MEMBER'), 'value' => ''];

        foreach (self::resources() as $roleID => $role) {
            if ($associated and empty($role->groups)) {
                continue;
            }

            $options[] = (object) ['text' => $role->$plural, 'value' => $roleID];
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