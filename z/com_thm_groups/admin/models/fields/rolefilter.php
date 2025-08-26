<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\Form\Field\ListField;
use THM\Groups\Adapters\{Database as DB, Input};

class JFormFieldRoleFilter extends ListField
{

    protected $type = 'rolefilter';

    /** @inheritDoc */
    protected function getOptions(): array
    {
        $defaultOptions = parent::getOptions();

        $query = DB::query();
        $query->select(['DISTINCT ' . DB::qn('r.id'), DB::qn('r.name')])
            ->from(DB::qn('#__groups_roles', 'r'))
            ->innerJoin(DB::qn('#__thm_groups_role_associations', 'ra'), DB::qc('r.id', 'ra.roleID'))
            ->innerJoin(DB::qn('#__thm_groups_profile_associations', 'pa'), DB::qc('ra.id', 'pa.role_associationID'))
            ->order(DB::qn('r.name'));

        if ($groupID = Input::integer('groupID')) {
            $query->where(DB::qc('ra.groupID', $groupID));
        }

        DB::set($query);

        $options = [];
        foreach (DB::arrays() as $role) {
            $options[] = JHTML::_('select.option', $role['id'], $role['name']);
        }

        return $defaultOptions + $options;

    }
}
