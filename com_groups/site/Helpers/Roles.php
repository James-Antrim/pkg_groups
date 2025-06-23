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
class Roles extends Selectable
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
        $query = DB::query();
        $query->select(DB::qn('name_' . Application::tag()))
            ->from(DB::qn('#__groups_roles', 'r'))
            ->innerJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.roleID', 'r.id'))
            ->innerJoin(DB::qn('#__user_usergroups_map', 'uugm'), DB::qc('uugm.id', 'ra.mapID'))
            ->where(DB::qcs([['uugm.group_id', $groupID], ['uugm.user_id', $userID]]));
        DB::set($query);

        return DB::column();
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
        $query = DB::query();
        $query->select('*')->from(DB::qn('#__groups_roles'))->order(DB::qn('plural_' . Application::tag()));
        DB::set($query);

        foreach ($roles = DB::objects('id') as $roleID => $role) {
            $role->groups = RoleAssociations::byRoleID($roleID);
        }

        return $roles;
    }
}