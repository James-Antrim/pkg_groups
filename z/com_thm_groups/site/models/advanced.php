<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      Dennis Priefer, <dennis.priefer@mni.thm.de>
 * @author      Alexander Boll, <alexander.boll@mni.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\{Groups, Profiles as Helper, Roles};
use THM\Groups\Models\Profiles as Model;

/**
 * Advanced model class of component com_thm_groups
 *
 * Model for advanced context
 */
class THM_GroupsModelAdvanced extends BaseDatabaseModel
{
    private array $groups = [];

    /** @inheritDoc */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->setGroups();
    }

    /**
     * Turns the multidimensional array (Group => Role => Profile)  into a flat array of alphabetically ordered profiles.
     *
     * @param   array  $groupedProfiles
     *
     * @return array
     */
    private function byName(array $groupedProfiles): array
    {
        $profiles = [];
        foreach ($groupedProfiles as $profileIDs) {
            foreach ($profileIDs as $profileID) {
                if (!$profileName = Helper::lnfName($profileID)) {
                    continue;
                }

                $profiles[$profileID] = ['id' => $profileID, 'name' => $profileName];
            }
        }

        uasort($profiles, function ($profile1, $profile2) {
            return $profile1['name'] > $profile2['name'];
        });

        return $profiles;
    }

    /**
     * Retrieves profiles as part of a multidimensional array. Group => Role => Profile
     *
     * @param   array  $groupedProfiles
     *
     * @return array
     */
    private function byRole(array $groupedProfiles): array
    {
        foreach ($groupedProfiles as $groupID => $associations) {
            foreach ($associations as $roleID => $profileIDs) {

                $profiles = [];
                foreach ($profileIDs as $profileID) {
                    if (!$profileName = Helper::lnfName($profileID)) {
                        continue;
                    }

                    $profiles[$profileID] = ['id' => $profileID, 'name' => $profileName];
                }

                $groupedProfiles[$groupID][$roleID] = ['name' => Roles::name($roleID), 'profiles' => $profiles];
            }

            // Move the unassigned profiles to the end of the group
            $members = $groupedProfiles[$groupID][Roles::MEMBERS];
            unset($groupedProfiles[$groupID][Roles::MEMBERS]);
            $groupedProfiles[$groupID][Roles::MEMBERS] = $members;

            $groupedProfiles[$groupID]['name'] = Groups::name($groupID);
        }

        return $groupedProfiles;
    }

    /**
     * Returns array with every group members and related attribute. The group is predefined as view parameter
     *
     * @return  array  array with group members and related user attributes
     * @throws Exception
     */
    public function getProfiles(): array
    {
        $groupedProfiles = [];
        foreach ($this->groups as $group) {
            $groupAssocs                 = Groups::associations($group->id);
            $groupedProfiles[$group->id] = $groupAssocs;
        }

        $sort = Input::getParams()->get('sort', ALPHASORT);
        $sort = in_array($sort, Model::SORTS) ? $sort : ALPHASORT;

        return $sort === Model::ROLESORT ? $this->byRole($groupedProfiles) : $this->byName($groupedProfiles);
    }

    /**
     * Sets the groups whose profiles are to be displayed. These are ordered so that nested groups are before parents and siblings are
     * ordered by actual order.
     *
     * @return void
     */
    private function setGroups(): void
    {
        $params   = Input::getParams();
        $ugHelper = UserGroupsHelper::getInstance();

        if (!$parentGroup = $ugHelper->get($params->get('groupID'))) {
            return;
        }

        $allGroups = $ugHelper->getAll();
        $subs      = $params->get('subgroups');
        $subs      = in_array($subs, Input::BINARY) ? $subs : Input::YES;

        if ($subs === Input::NO) {
            $this->groups[] = $parentGroup;
            return;
        }

        foreach ($allGroups as $group) {
            $relevant = ($group->lft >= $parentGroup->lft and $group->rgt <= $parentGroup->rgt);

            if ($relevant) {
                $this->groups[$group->id] = $group;
            }
        }

        unset($allGroups);

        uasort($this->groups, function ($groupOne, $groupTwo) {
            // First group is antecedent
            if ($groupTwo->lft > $groupOne->rgt) {
                return 1;
            }

            // Second group is antecedent
            if ($groupOne->lft > $groupTwo->rgt) {
                return -1;
            }

            // First group is nested
            if ($groupOne->lft > $groupTwo->lft and $groupOne->rgt < $groupTwo->rgt) {
                return 1;
            }

            // Second group is nested
            if ($groupTwo->lft > $groupOne->lft and $groupTwo->rgt < $groupOne->rgt) {
                return 1;
            }

            // This should not be able to take place due to the nested table structure
            return 0;
        });
    }

}
