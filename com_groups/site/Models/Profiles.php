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
                // TBD
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
            $item->noteCount = $this->getNoteCount($item->id);
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

        $this->orderBy($query);

        return $query;
    }

    /**
     * Gets the number of notes associated with the profile.
     *
     * @param int $profileID the id of the profile
     *
     * @return int
     */
    private function getNoteCount(int $profileID): int
    {
        $db     = $this->getDatabase();
        $noteID = $db->quoteName('id');
        $state  = $db->quoteName('state');
        $userID = $db->quoteName('user_id');
        $query  = $db->getQuery(true);
        $query->select("$userID, COUNT($noteID) AS notes")
            ->from($db->quoteName('#__user_notes'))
            ->where("$userID = $profileID")
            ->where("$state >= 0")
            ->group($userID);
        $db->setQuery($query);

        // Load the counts into an array indexed on the aro.value field (the user id).
        try
        {
            return (int)$db->loadResult();
        }
        catch (Exception $exception)
        {
            $this->setError($exception->getMessage());

            return 0;
        }
    }
}