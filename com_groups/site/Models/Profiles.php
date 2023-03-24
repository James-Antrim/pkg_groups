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

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers;
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available attribute types data.
 */
class Profiles extends ListModel
{
    protected string $defaultOrdering = 'surnames, forenames';

    /**
     * @inheritDoc
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = [
                'activation', 'block', 'content', 'editing', 'published', 'registered', 'visited'
            ];
        }

        parent::__construct($config, $factory);
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        if (!Helpers\Can::manage())
        {
            Application::error(403);
        }

        Application::message(Text::_('GROUPS_503'));
    }

    /**
     * Gets the groups & roles associated with a given profile id
     * @param int $itemID
     *
     * @return array
     */
    private function getAssocs(int $itemID): array
    {
        $db     = $this->getDatabase();
        $groups = [];
        $query  = $db->getQuery(true);
        $tag    = Application::getTag();

        $assocID   = $db->quoteName('pa.assocID');
        $groupID   = $db->quoteName('g.id');
        $mGroupID  = $db->quoteName('map.group_id');
        $profileID = $db->quoteName('pa.profileID');
        $raGroupID = $db->quoteName('ra.groupID');
        $raID      = $db->quoteName('ra.id');
        $raRoleID  = $db->quoteName('ra.roleID');
        $roleID    = $db->quoteName('r.id');
        $userID    = $db->quoteName('map.user_id');

        $jCondition1 = "$mGroupID = $groupID";
        $jCondition2 = "$raGroupID = $groupID";
        $jCondition3 = "$roleID = $raRoleID";
        $jCondition4 = "$assocID = $raID";

        $wCondition1 = "($profileID = $itemID AND $userID = $itemID)";
        $wCondition2 = "($profileID = $itemID AND $userID IS NULL)";
        $wCondition3 = "($profileID IS NULL AND $userID = $itemID)";

        $select = [
            $db->quoteName('g.id', 'groupID'),
            $db->quoteName("g.name_$tag", 'group'),
            $db->quoteName('r.id', 'roleID'),
            $db->quoteName("r.name_$tag", 'role'),
            $db->quoteName('pa.profileID', 'profileID'),
            $db->quoteName('map.user_id', 'userID')
        ];
        $query->select($select)
            ->from($db->quoteName('#__groups_groups', 'g'))
            ->join('left', $db->quoteName('#__user_usergroup_map', 'map'), $jCondition1)
            ->join('left', $db->quoteName('#__groups_role_associations', 'ra'), $jCondition2)
            ->join('left', $db->quoteName('#__groups_roles', 'r'), $jCondition3)
            ->join('left', $db->quoteName('#__groups_profile_associations', 'pa'), $jCondition4)
            ->where("($wCondition1 OR $wCondition2 OR $wCondition3)");

        $db->setQuery($query);

        foreach ($db->loadAssocList() as $result)
        {
            $group   = $result['group'];
            $groupID = $result['groupID'];
            $role    = $result['role'];
            $roleID  = $result['roleID'];

            if (empty($result['userID']))
            {
                // TODO: create map entry
            }

            if (empty($result['profileID']) and !in_array($groupID, Helpers\Groups::DEFAULT))
            {
                // TODO: create profile and role associations as necessary
            }

            if (empty($groups[$groupID]))
            {
                $groups[$groupID] = ['name' => $group, 'roles' => []];
            }

            if ($roleID and empty($groups[$groupID]['roles'][$roleID]))
            {
                $groups[$groupID]['roles'][$roleID] = $role;
            }
        }

        return $groups;
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        $items = parent::getItems();

        foreach ($items as $item)
        {
            // Management access is a prerequisite of accessing this view at all.
            $item->access    = true;
            $item->activated = empty($item->activation);
            $item->editLink  = Route::_('index.php?option=com_groups&view=Profile&id=' . $item->id);
            $item->groups    = $this->getAssocs($item->id);
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
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $nameColumns = [$db->quoteName('p.forenames'), $db->quoteName('p.surnames')];
        $query->select([
            $db->quoteName('p') . '.*',
            $db->quoteName('u') . '.*',
            $query->concatenate($nameColumns, ' ') . ' AS ' . $db->quoteName('name')
        ]);

        $profileID  = $db->quoteName('p.id');
        $uCondition = $db->quoteName('u.id') . " = $profileID";

        $query->from($db->quoteName('#__groups_profiles', 'p'))
            ->join('inner', $db->quoteName('#__users', 'u'), $uCondition);

        if ($search = $this->getState('filter.search'))
        {
            if (is_numeric($search))
            {
                $query->where($db->quoteName('u.id') . ' = :id')
                    ->bind(':id', $search, ParameterType::INTEGER);
            }
            else
            {
                $search  = '%' . trim($search) . '%';
                $wherray = [
                    $db->quoteName('email') . ' LIKE :email',
                    $db->quoteName('name') . ' LIKE :name',
                    $db->quoteName('username') . ' LIKE :username',
                ];
                $query->where('(' . implode(' OR ', $wherray) . ')')
                    ->bind(':email', $search)
                    ->bind(':name', $search)
                    ->bind(':username', $search);
            }
        }

        $this->binaryFilter($query, 'filter.block');
        $this->binaryFilter($query, 'filter.content');
        $this->binaryFilter($query, 'filter.editing');
        $this->binaryFilter($query, 'filter.published');

        $activation = $this->state->get('filter.activation');

        if ($this->isBinary($activation))
        {
            $column = $db->quoteName('activation');

            if ((int)$activation)
            {
                $query->where("($column = '' OR $column = '0')");
            }
            else
            {
                $query->where("$column != '0'")
                    ->where($query->length($column) . ' > 0');
            }
        }

        $registered = $this->state->get('filter.registered');
        $visited    = $this->state->get('filter.visited');

        if ($registered or $visited)
        {
            $now        = date('\'Y-m-d H:i:s\'');
            $today      = date('\'Y-m-d 00:00:00\'');
            $weekAgo    = date('\'Y-m-d 00:00:00\'', strtotime('-1 week'));
            $monthsAgo1 = date('\'Y-m-d 00:00:00\'', strtotime('-1 month'));
            $monthsAgo3 = date('\'Y-m-d 00:00:00\'', strtotime('-3 month'));
            $monthsAgo6 = date('\'Y-m-d 00:00:00\'', strtotime('-6 month'));
            $yearAgo    = date('\'Y-m-d 00:00:00\'', strtotime('-1 year'));

            if ($registered)
            {
                $rColumn = $db->quoteName('registerDate');
                switch ($registered)
                {
                    case 'today':
                        $query->where("$rColumn BETWEEN $today AND $now");
                        break;
                    case 'past_week':
                        $query->where("$rColumn BETWEEN $weekAgo AND $now");
                        break;
                    case 'past_1month':
                        $query->where("$rColumn BETWEEN $monthsAgo1 AND $now");
                        break;
                    case 'past_3month':
                        $query->where("$rColumn BETWEEN $monthsAgo3 AND $now");
                        break;
                    case 'past_6month':
                        $query->where("$rColumn BETWEEN $monthsAgo6 AND $now");
                        break;
                    case 'past_year':
                        $query->where("$rColumn BETWEEN $yearAgo AND $now");
                        break;
                    case 'post_year':
                        $query->where("$rColumn < $yearAgo");
                        break;
                }
            }

            if ($visited)
            {
                $vColumn = $db->quoteName('lastvisitDate');
                switch ($visited)
                {
                    case 'today':
                        echo 'check?';
                        $query->where("$vColumn BETWEEN $today AND $now");
                        break;
                    case 'past_week':
                        $query->where("$vColumn BETWEEN $weekAgo AND $now");
                        break;
                    case 'past_1month':
                        $query->where("$vColumn BETWEEN $monthsAgo1 AND $now");
                        break;
                    case 'past_3month':
                        $query->where("$vColumn BETWEEN $monthsAgo3 AND $now");
                        break;
                    case 'past_6month':
                        $query->where("$vColumn BETWEEN $monthsAgo6 AND $now");
                        break;
                    case 'past_year':
                        $query->where("$vColumn BETWEEN $yearAgo AND $now");
                        break;
                    case 'post_year':
                        $query->where("$vColumn < $yearAgo");
                        break;
                    case 'never':
                        $query->where("$vColumn IS NULL");
                        break;
                }
            }
        }

        $this->orderBy($query);

        return $query;
    }
}