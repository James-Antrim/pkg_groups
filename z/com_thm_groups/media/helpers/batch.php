<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      Ilja Michajlow, <ilja.michajlow@mni.thm.de>
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{Database as DB, HTML};
use THM\Groups\Helpers\Groups;

/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperBatch
{
    /**
     * Return all existing roles as select field
     *
     * @return  array  an array of options for drop-down list
     */
    public static function roles(): array
    {
        $query = DB::query();
        $query->select(DB::qn(['id', 'name'], ['value', 'text']))->from(DB::qn('#__groups_roles'))->order(DB::qn('id'));
        DB::set($query);

        $options = [];

        foreach (DB::objects() as $role) {
            $options[] = HTML::option($role->value, $role->text);
        }

        return $options;
    }

    /**
     * Returns groups as a select field
     * It shows only groups with users in it, because this select field
     * will be used only for filtering in backend-user-manager
     *
     * @return array
     * @throws Exception
     */
    public static function groups(): array
    {
        $query = DB::query();

        $query->select([DB::qn('ug.id'), DB::qn('ug.title'), 'COUNT(DISTINCT ' . DB::qn('ugTemp.id') . ') AS ' . DB::qn('level')])
            ->from(DB::qn('#__usergroups', 'ug'))
            ->leftJoin(DB::qn('#__usergroups', 'ugTemp'), DB::qcs([['ug.lft', 'ugTemp.lft', '>'], ['ug.rgt', 'ugTemp.rgt', '<']]))
            ->group(DB::qn(['ug.id', 'ug.title', 'ug.lft', 'ug.rgt']))
            ->order('ug.lft ASC');
        DB::set($query);

        $options = [];
        foreach (DB::arrays() as $group) {
            $prefix    = str_repeat('- ', $group['level']);
            $options[] = HTML::option($group['id'], $prefix . $group['title'], in_array($group['id'], Groups::STANDARD_GROUPS));
        }

        return $options;
    }
}
