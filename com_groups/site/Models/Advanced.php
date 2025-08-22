<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

defined('_JEXEC') or die;

jimport('joomla.filesystem.path');

use Joomla\CMS\MVC\{Factory\MVCFactoryInterface, Model\BaseDatabaseModel};
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Groups;
use THM\Groups\Helpers\Profiles as Helper;
use THM\Groups\Helpers\Roles;
use THM\Groups\Tools\Migration;

/**
 * Advanced model class of component com_thm_groups
 *
 * Model for advanced context
 */
class Advanced extends BaseDatabaseModel
{
    use Grouped;

    public const ALPHASORT = 1, ROLESORT = 0;

    public const SORTS = [self::ALPHASORT, self::ROLESORT];

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        // Profiles were are selected by approximation with user sur- and forenames, the groups are not relevant.
        $this->groups();

        parent::__construct($config, $factory);
    }

    /**
     * Formats grouped profiles into an alphabetically sorted array with their group/role association in context as a property.
     *
     * @param   array  $groupedProfiles  the profiles aggregated by group and role
     * @param   bool   $addContext       whether the group context needs to be added to role names
     * @param   bool   $showTitles       whether to display pre and/or post titles
     *
     * @return array
     */
    private function byName(array $groupedProfiles, bool $addContext, bool $showTitles): array
    {
        foreach ($groupedProfiles as $groupID => $associations) {
            $gName = Groups::name($groupID);
            foreach ($associations as $roleID => $userIDs) {
                if (!$profileName = Helper::lnfName($userIDs, $showTitles)) {
                    continue;
                }

                if (empty($profiles[$userIDs])) {
                    $profiles[$userIDs] = ['id' => $userIDs, 'name' => $profileName];
                }

                if (empty($profiles[$userIDs]['roles'])) {
                    $profiles[$userIDs]['roles'] = [];
                }

                $rName                               = Roles::name($roleID);
                $rName                               = $addContext ? "$rName ($gName)" : $rName;
                $profiles[$userIDs]['roles'][$rName] = [$rName];
            }
        }

        uasort($profiles, function ($profile1, $profile2) {
            return $profile1['name'] > $profile2['name'];
        });

        return $profiles;
    }

    /**
     * Adds supplementary information about groups and roles to a multidimensional array of aggregated profiles.
     *
     * @param   array  $groupedProfiles  the profiles aggregated by group and role
     * @param   bool   $addContext       whether the group context needs to be added to role names
     * @param   bool   $showTitles       whether to display pre and/or post titles
     *
     * @return array
     */
    private function byRole(array $groupedProfiles, bool $addContext, bool $showTitles): array
    {
        foreach ($groupedProfiles as $groupID => $associations) {
            $gName = Groups::name($groupID);
            foreach ($associations as $roleID => $userIDs) {

                $profiles = [];
                foreach ($userIDs as $profileID) {
                    if (!$profileName = Helper::lnfName($profileID, $showTitles)) {
                        continue;
                    }

                    $profiles[$profileID] = ['id' => $profileID, 'name' => $profileName];
                }

                $rName                              = Roles::plural($roleID);
                $rName                              = $addContext ? "$gName: $rName" : $rName;
                $groupedProfiles[$groupID][$roleID] = ['name' => $rName, 'profiles' => $profiles];
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
     */
    public function profiles(): array
    {
        $params = Input::parameters();

        $groupedProfiles = [];
        foreach ($this->groups as $group) {
            $groupAssocs                 = Groups::associations($group->id);
            $groupedProfiles[$group->id] = $groupAssocs;
        }

        $sort = (in_array($params->get('sort'), self::SORTS)) ? $params->get('sort') : self::ALPHASORT;

        $addContext = count($groupedProfiles) > 1;
        $showTitles = (bool) Input::parameters()->get('showTitles', Input::YES);

        if (empty($this->groups) or $sort === self::ALPHASORT) {
            return $this->byName($groupedProfiles, $addContext, $showTitles);
        }
        else {
            return $this->byRole($groupedProfiles, $addContext, $showTitles);
        }
    }

}
