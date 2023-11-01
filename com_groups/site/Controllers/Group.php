<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Tables\{Incremented, ViewLevels, UserGroups};

class Group extends FormController
{
    protected string $list = 'Groups';

    /**
     * @inheritDoc
     */
    protected function prepareData(): array
    {
        return [
            'name_de'    => Input::getString('name_de'),
            'name_en'    => Input::getString('name_en'),
            'title'      => Input::getString('title'),
            'parent_id'  => Input::getInt('parent_id'),
            'viewLevels' => Input::getIntCollection('viewLevels')
        ];

    }

    /**
     * @inheritDoc
     */
    protected function store(Table $table, array $data, int $id = 0): int
    {
        $groups = new UserGroups();

        if ($id and (!$groups->load($id) or !$table->load($id))) {
            Application::message('GROUPS_412', Application::ERROR);

            return $id;
        }

        // Save the group first in case it is new / as copy
        $groupData = ['parent_id' => $data['parent_id'], 'title' => $data['title']];

        if (!$groups->save($groupData)) {
            Application::message($groups->getError(), Application::ERROR);

            return $id;
        }

        $id         = $groups->id;
        $data['id'] = $id;

        if (!$table->save($data)) {
            Application::message($table->getError(), Application::ERROR);

            return $id;
        }

        /**
         * The group => level association is saved as a JSON string in rules. As a consequence every level has to be
         * iterated; deprecated associations have to be sought and removed, requested associations have to be added.
         */
        $db    = Application::getDB();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('id'))->from($db->quoteName('#__viewlevels'));
        $db->setQuery($query);
        $levelIDs = ArrayHelper::toInteger($db->loadColumn());

        foreach ($levelIDs as $levelID) {

            $levels = new ViewLevels();
            $levels->load($levelID);

            $groups      = json_decode($levels->rules);
            $existingKey = array_search($id, $groups);
            $existent    = $existingKey !== false;
            $requested   = in_array($levelID, $data['viewLevels']);

            if (($existent and $requested) or (!$existent and !$requested)) {
                continue;
            }

            // Add
            if ($requested) {
                $groups[] = $id;
                $groups   = array_unique($groups);
            }

            // Remove
            if ($existent) {
                unset($groups[$existingKey]);
            }

            $update        = json_encode($groups);
            $levels->rules = $update;
            $levels->store();

        }

        /** @var Incremented $table */
        return $table->id;
    }

}