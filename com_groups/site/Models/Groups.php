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

use Joomla\CMS\Helper\UserGroupsHelper as UGH;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;
use stdClass;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available groups data.
 */
class Groups extends ListModel
{
    protected string $defaultOrdering = 'ug.lft';

    /**
     * @inheritDoc
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = ['levelID', 'roleID'];
        }

        parent::__construct($config, $factory);
    }

    /**
     * @inheritDoc
     */
    public function delete(): void
    {
        Application::message(Text::_('GROUPS_503'));
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        $items    = parent::getItems() ?: [];
        $ugHelper = UGH::getInstance();

        foreach ($items as $item)
        {
            $ugHelper->populateGroupData($item);
            $this->getUserCounts($item);
        }


        return $items;
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  QueryInterface
     */
    protected function getListQuery(): QueryInterface
    {
        // Create a new query object.
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);
        $tag   = Application::getTag();

        $id       = 'DISTINCT ' . $db->quoteName('g.id', 'id');
        $groupID  = $db->quoteName('g.id');
        $name     = $db->quoteName("g.name_$tag", 'name');
        $parentID = $db->quoteName('ug.parent_id');
        // Select the required fields from the table.
        $query->select([$id, $name, $parentID]);

        $condition  = $db->quoteName('ug.id') . " = $groupID";
        $groups     = $db->quoteName('#__groups_groups', 'g');
        $userGroups = $db->quoteName('#__usergroups', 'ug');
        $query->from($groups)->join('inner', $userGroups, $condition);

        $gLeft     = $db->quoteName('ug.lft');
        $gRight    = $db->quoteName('ug.rgt');
        $parent    = $db->quoteName('#__usergroups', 'p');
        $pID       = $db->quoteName('p.id');
        $pLeft     = $db->quoteName('p.lft');
        $pRight    = $db->quoteName('p.rgt');
        $condition = "$pLeft < $gLeft AND $gRight < $pRight";
        $query->join('left', $parent, $condition);

        if ($filterRoleID = (int)$this->getState('filter.roleID'))
        {
            $condition = $db->quoteName('ra.groupID') . " = $groupID";
            $ra        = $db->quoteName('#__groups_role_associations', 'ra');
            $roleID    = $db->quoteName('ra.roleID');

            if ($filterRoleID >= 1)
            {
                $query->join('inner', $ra, $condition)
                    ->where("$roleID = :roleID")
                    ->bind(':roleID', $filterRoleID, ParameterType::INTEGER);
            }
            else
            {
                $query->join('left', $ra, $condition)
                    ->where("$roleID IS NULL");
            }
        }

        if ($filterLevelID = (int)$this->getState('filter.levelID'))
        {
            $levelID = $db->quoteName('vl.id');
            $levels  = $db->quoteName('#__viewlevels', 'vl');
            $regex1  = $query->concatenate(["'[,\\\\[]'", $groupID, "'[,\\\\]]'"]);
            $regex2  = $query->concatenate(["'[,\\\\[]'", $pID, "'[,\\\\]]'"]);
            $rules   = $db->quoteName('vl.rules');
            $query->join('inner', $levels, "$rules REGEXP $regex1 OR $rules REGEXP $regex2")
                ->where("$levelID = :levelID")
                ->bind(':levelID', $filterLevelID, ParameterType::INTEGER);
        }

        // Filter the comments over the search string if set.
        $search = $this->getState('filter.search');

        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $ids = (int)substr($search, 3);
                $query->where($db->quoteName('g.id') . ' = :id');
                $query->bind(':id', $ids, ParameterType::INTEGER);
            }
            else
            {
                $nameDE = $db->quoteName('g.name_de');
                $nameEN = $db->quoteName('g.name_en');
                $search = '%' . trim($search) . '%';
                $query->where("($nameDE LIKE :title1 OR $nameEN LIKE :title2)")
                    ->bind(':title1', $search)
                    ->bind(':title2', $search);
            }
        }

        // Add the list ordering clause.
        $this->orderBy($query);

        return $query;
    }

    /**
     * Adds associated user counts to the group
     *
     * @param stdClass $group the group to set values for
     *
     * @return void
     */
    private function getUserCounts(stdClass $group)
    {
        $db = $this->getDatabase();

        $block      = $db->quoteName('u.block');
        $map        = $db->quoteName('#__user_usergroup_map', 'map');
        $mapGroupID = $db->quoteName('map.group_id');
        $mapUserID  = $db->quoteName('map.user_id');
        $query      = $db->getQuery(true);
        $userID     = $db->quoteName('u.id');
        $users      = $db->quoteName('#__users', 'u');

        $blocked   = 0;
        $condition = "$userID = $mapUserID";
        $select    = "COUNT(DISTINCT $mapUserID)";

        // Count the objects in the user group.
        $query->select($select)->from($map)->join('LEFT', $users, $condition)
            ->where("$mapGroupID = $group->id")
            ->where("$block = :blocked")
            ->group($mapGroupID)
            ->bind(':blocked', $blocked, ParameterType::INTEGER);
        $db->setQuery($query);

        $group->enabled = (int)$db->loadResult();

        $blocked        = 1;
        $group->blocked = (int)$db->loadResult();
    }

    /**
     * @inheritDoc
     */
    protected function populateState($ordering = 'ug.lft', $direction = 'asc')
    {
        // Load the parameters.
        $params = Application::getParams('com_users')->merge(Application::getParams());
        $this->setState('params', $params);

        // List state information.
        parent::populateState($ordering, $direction);
    }
}