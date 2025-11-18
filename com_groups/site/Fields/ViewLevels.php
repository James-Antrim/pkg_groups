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
use THM\Groups\Adapters\{Database as DB, Input, User};
use THM\Groups\Helpers\{Categories, Users};

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
        $query = DB::query();
        $query->select(['DISTINCT ' . DB::qn('vl.id', 'value'), DB::qn('vl.title', 'text')])
            ->from(DB::qn('#__viewlevels', 'vl'))
            ->order(DB::qn('text'));

        $context = $this->form->getName();

        // Batch will potentially apply unused levels or levels beyond the authorization of the profile user.
        if ($this->group !== 'batch') {
            if ($context === 'com_groups.attributes.filter') {
                $query->innerJoin(DB::qn('#__groups_attributes', 'a'), DB::qc('a.viewLevelID', 'vl.id'));
            }
            elseif (in_array($context,
                    ['com_groups.contents.filter', 'com_groups.pages.filter']) and $rootID = Categories::root()) {
                $query->innerJoin(DB::qn('#__content', 'co'), DB::qc('co.access', 'vl.id'))
                    ->innerJoin(DB::qn('#__categories', 'ca'), DB::qc('ca.id', 'co.catid'))
                    ->where(DB::qc('ca.parent_id', $rootID));
                if ($context === 'com_groups.pages.filter' and $categoryID = Users::categoryID(Input::integer('profileID'))) {
                    $query->where(DB::qc('ca.id', $categoryID));
                }
            }
            elseif ($context === 'com_groups.groups.filter') {
                $query->where(DB::qc('vl.rules', '[]', '!=', true));
            }
            elseif ($context === 'com_groups.content') {
                $userID = Categories::userID(Input::integer('catid'));
                $query->whereIn(DB::qn('vl.id'), User::levels($userID));
            }
        }

        DB::set($query);

        return array_merge(parent::getOptions(), DB::objects());
    }
}