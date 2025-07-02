<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use Joomla\CMS\Helper\UserGroupsHelper as UGHelper;
use Joomla\CMS\MVC\{Factory\MVCFactoryInterface, Model\BaseDatabaseModel};
use THM\Groups\Adapters\{Database as DB, Input, Text};
use THM\Groups\Helpers\{Groups, Profiles as Helper, Roles};
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available attribute types data.
 */
class Profiles extends BaseDatabaseModel
{
    use Ordered;

    public const ADVANCED = 'advanced', DEFAULT = 'default', OVERVIEW = 'overview';
    public const ALPHASORT = 1, ROLESORT = 0;

    public const ALPHA_LAYOUTS = [self::ADVANCED, self::DEFAULT, self::OVERVIEW];
    public const LAYOUTS = [self::ADVANCED, self::DEFAULT, self::OVERVIEW];
    public const SORTS = [self::ALPHASORT, self::ROLESORT];

    private array $groups = [];

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        // Profiles were are selected by approximation with user sur- and forenames, the groups are not relevant.
        if (!Input::getString('search')) {
            $this->groups();
        }

        parent::__construct($config, $factory);
    }

    /**
     * Formats grouped profiles into an alphabetically sorted array with their group/role association in context as a property.
     *
     * @param   array  $groupedProfiles  the profiles aggregated by group and role
     *
     * @return array
     */
    private function byName(array $groupedProfiles): array
    {
        $addContext = count($groupedProfiles) > 1;
        $showTitles = (bool) Input::getParams()->get('showTitles', Input::YES);
        $profiles   = [];

        foreach ($groupedProfiles as $groupID => $rolledProfiles) {
            $gName = Groups::name($groupID);
            foreach ($rolledProfiles as $roleID => $profileID) {
                if (!$profileName = Helper::lnfName($profileID, $showTitles)) {
                    continue;
                }

                if (empty($profiles[$profileID])) {
                    $profiles[$profileID] = ['id' => $profileID, 'name' => $profileName];
                }

                if (empty($profiles[$profileID]['roles'])) {
                    $profiles[$profileID]['roles'] = [];
                }

                $rName                                 = Roles::name($roleID);
                $rName                                 = $addContext ? "$rName ($gName)" : $rName;
                $profiles[$profileID]['roles'][$rName] = [$rName];
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
     *
     * @return array
     */
    private function byRole(array $groupedProfiles): array
    {
        $addContext = count($groupedProfiles) > 1;
        $showTitles = (bool) Input::getParams()->get('showTitles', Input::YES);

        foreach ($groupedProfiles as $groupID => $associations) {
            $gName = Groups::name($groupID);
            foreach ($associations as $roleID => $profileIDs) {

                $profiles = [];
                foreach ($profileIDs as $profileID) {
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
     * Retrieves profiles that ~match the given search terms.
     *
     * @param   string  $terms
     *
     * @return array
     */
    private function bySearch(string $terms): array
    {
        $terms      = Text::filter(Text::transliterate($terms));
        $conditions = explode('-', $terms);

        foreach ($conditions as $key => $term) {
            $conditions[$key] = DB::qc('alias', "%$term%", 'LIKE', true);
        }
        $conditions[] = DB::qc('published', 1);

        $query = DB::query();
        $query->select(DB::qn('id'))
            ->from('#__users')
            ->where($conditions)
            ->order(implode(',', DB::qn(['surnames', 'forenames'])));
        DB::set($query);

        $profiles   = [];
        $showTitles = (bool) Input::getParams()->get('showTitles', Input::YES);

        foreach (DB::integers('id') as $profileID) {
            if (!$profileName = Helper::lnfName($profileID, $showTitles)) {
                continue;
            }

            $profiles[$profileID] = ['id' => $profileID, 'name' => $profileName];
        }

        return $profiles;
    }

    /**
     * Sets the groups relevant to the output of profiles.
     * @return void
     */
    private function groups(): void
    {
        $params   = Input::getParams();
        $ugHelper = UGHelper::getInstance();
        if (!$parent = $ugHelper->get($params->get('groupID'))) {
            return;
        }

        $subs = $params->get('subgroups');
        $subs = in_array($subs, Input::BINARY) ? $subs : Input::YES;
        if ($subs === Input::NO) {
            $this->groups[$parent->id] = $parent;
            return;
        }

        foreach ($ugHelper->getAll() as $groupID => $group) {
            if (in_array($groupID, Groups::STANDARD_GROUPS)) {
                continue;
            }

            if ($group->lft >= $parent->lft and $group->rgt <= $parent->rgt) {
                $this->groups[$group->id] = $group;
            }
        }

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

    /**
     * Retrieves the relevant profiles.
     *
     * @return array
     */
    public function profiles(): array
    {
        $params = Input::getParams();

        // Groups and roles are irrelevant in a search context.
        if ($terms = Input::getString('search') and $terms = Text::trim($terms)) {
            return $this->bySearch($terms);
        }

        $groupedProfiles = [];
        foreach ($this->groups as $group) {
            $groupAssocs                 = Groups::associations($group->id);
            $groupedProfiles[$group->id] = $groupAssocs;
        }

        $layout = (in_array($params->get('layout'), self::LAYOUTS)) ? $params->get('layout') : self::DEFAULT;
        $sort   = (in_array($params->get('sort'), self::SORTS)) ? $params->get('sort') : self::ALPHASORT;

        if (empty($this->groups) or in_array($layout, self::ALPHA_LAYOUTS) or $sort === self::ALPHASORT) {
            $namedProfiles = $this->byName($groupedProfiles);
        }
        else {
            return $this->byRole($groupedProfiles);
        }

        if ($layout !== self::OVERVIEW) {
            return $namedProfiles;
        }

        $profiles = [];
        foreach ($namedProfiles as $profile) {
            // Normal substring messes up special characters
            $letter = strtoupper(mb_substr($profile['name'], 0, 1));

            switch ($letter) {
                case 'Ä':
                    $letter = 'A';
                    break;
                case 'Ö':
                    $letter = 'O';
                    break;
                case 'Ü':
                    $letter = 'U';
                    break;
                default:
                    break;
            }

            if (empty($profiles[$letter])) {
                $profiles[$letter] = [];
            }

            $profiles[$letter][] = $profile;
        }

        return $profiles;
    }
}