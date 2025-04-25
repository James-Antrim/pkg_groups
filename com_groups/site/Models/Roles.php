<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
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
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available roles data.
 */
class Roles extends ListModel
{
    use Ordered;

    protected string $defaultOrdering = 'ordering';

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'assigned',
                'groupID',
            ];
        }

        parent::__construct($config, $factory);
    }

    /** @inheritDoc */
    public function getItems(): array
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            // Management access is a prerequisite of accessing this view at all.
            $item->access   = true;
            $item->editLink = Route::_('index.php?option=com_groups&view=Role&id=' . $item->id);

            if ($item->groups === 0) {
                $item->groups = Text::_('GROUPS_NO_GROUPS');
            }
            elseif ($item->groups === 1) {
                $item->groups = $item->group;
            }
            else {
                //TODO: link to groups view with role filter set to this one
                $item->groups = $item->group;
            }
        }

        return $items;
    }

    /** @inheritDoc */
    protected function getListQuery(): QueryInterface
    {
        // Create a new query object.
        $db     = $this->getDatabase();
        $groups = $db->quoteName('g.id', 'groups');
        $groups = 'COUNT(' . implode(') AS ', explode(' AS ', $groups));
        $query  = $db->getQuery(true);
        $tag    = Application::tag();

        //'COUNT(' . $db->quoteName('id') . ')'

        // Select the required fields from the table.
        $query->select([
            $db->quoteName('r.id'),
            $db->quoteName('r.ordering'),
            $db->quoteName("r.name_$tag", 'name'),
            $db->quoteName("r.plural_$tag", 'plural'),
            $db->quoteName("g.name_$tag", 'group'),
            $groups
        ]);

        $assocTable  = $db->quoteName('#__groups_role_associations', 'ra');
        $assocMapID  = $db->quoteName('ra.mapID');
        $assocRoleID = $db->quoteName('ra.roleID');
        $groupsID    = $db->quoteName('g.id');
        $groupsTable = $db->quoteName('#__groups_groups', 'g');
        $mapGroupID  = $db->quoteName('uugm.group_id');
        $mapID       = $db->quoteName('uugm.id');
        $mapTable    = $db->quoteName('#__user_usergroup_map', 'uugm');
        $roleID      = $db->quoteName('r.id');
        $rolesTable  = $db->quoteName('#__groups_roles', 'r');

        $query->from($rolesTable)->group($roleID);

        $groupID = $this->getState('filter.groupID');
        if (is_numeric($groupID) and intval($groupID) > 0) {
            $groupID = (int) $groupID;
            $query->join('inner', $assocTable, "$assocRoleID = $roleID")
                ->join('inner', $mapTable, "$mapID = $assocMapID")
                ->join('inner', $groupsTable, "$groupsID = $mapGroupID")
                ->where($groupsID . ' = :groupID')
                ->bind(':groupID', $groupID, ParameterType::INTEGER);
        }
        else {
            $query->join('left', $assocTable, "$assocRoleID = $roleID")
                ->join('left', $mapTable, "$mapID = $assocMapID")
                ->join('left', $groupsTable, "$groupsID = $mapGroupID");

            if (is_numeric($groupID) and intval($groupID) < 0) {
                $query->where($groupsID . ' IS NULL');
            }
        }

        $this->orderBy($query);

        return $query;
    }
}