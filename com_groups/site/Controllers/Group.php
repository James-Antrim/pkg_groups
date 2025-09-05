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

use THM\Groups\Adapters\{Application, Database as DB, Input};
use THM\Groups\Tables\{Table, ViewLevels, UserGroups};

class Group extends FormController
{
    protected string $list = 'Groups';

    /** @inheritDoc */
    protected function prepareData(): array
    {
        return [
            'name_de'    => Input::string('name_de'),
            'name_en'    => Input::string('name_en'),
            'title'      => Input::string('title'),
            'parent_id'  => Input::integer('parent_id'),
            'viewLevels' => Input::resourceIDs('viewLevels')
        ];

    }

    /** @inheritDoc */
    protected function store(Table $table, array $data, int $id = 0): int
    {
        $groups = new UserGroups();

        if ($id and (!$groups->load($id) or !$table->load($id))) {
            Application::message('GROUPS_412', Application::ERROR);

            return $id;
        }

        $title = empty($data['title']) ? $data['name_de'] : $data['title'];

        // Save the group first in case it is new / as copy
        if (!$groups->save(['title' => $title, 'parent_id' => $data['parent_id']])) {
            Application::message($groups->getError(), Application::ERROR);

            return $id;
        }

        // Joomla can't handle a primary key that is also a foreign key.
        if ($id) {
            if (!$table->save($data)) {
                Application::message($table->getError(), Application::ERROR);

                return $id;
            }
        }
        else {
            $query = DB::query();
            $query->insert(DB::qn('#__groups_groups'))
                ->columns(DB::qn(['id', 'name_de', 'name_en']))
                ->values("$groups->id, " . DB::quote($data['name_de']) . ', ' . DB::quote($data['name_en']));
            DB::set($query);

            if (!DB::execute()) {
                Application::message('500', Application::ERROR);

                return $groups->id;
            }
        }

        // The id is now set regardless of whether the entries are new.
        $id = $groups->id;

        /**
         * The group => level association is saved as a JSON string in rules. As a consequence every level has to be iterated;
         * deprecated associations have to be sought and removed, requested associations have to be added.
         */
        $query = DB::query();
        $query->select(DB::qn('id'))->from(DB::qn('#__viewlevels'));
        DB::set($query);

        foreach (DB::integers() as $levelID) {

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

        return $id;
    }

}