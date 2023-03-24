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

use Exception;
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
                'block', 'content', 'editing', 'published', 'registered', 'visited'
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