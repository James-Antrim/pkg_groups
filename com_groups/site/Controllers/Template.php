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
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Tables\Incremented;

class Template extends FormController
{
    /**
     * @inheritdoc
     */
    protected string $list = 'Templates';

    /**
     * @inheritDoc
     */
    protected function prepareData(): array
    {
        return [
            'name_de' => Input::getString('name_de'),
            'name_en' => Input::getString('name_en'),
            'cards'   => (int) Input::getBool('cards'),
            'roles'   => (int) Input::getBool('roles'),
            'vcards'  => (int) Input::getBool('vcards')
        ];
    }

    /**
     * Reusable function to store data in an Incremented table.
     *
     * @param   Table  $table  an Incremented table
     * @param   array  $data   the data to store
     * @param   int    $id     the id of the row in which to store the data
     *
     * @return int the id of the table row on success, otherwise the id parameter
     * @uses Incremented
     */
    protected function store(Table $table, array $data, int $id = 0): int
    {
        if ($id and !$table->load($id)) {
            Application::message('GROUPS_412', Application::ERROR);

            return $id;
        }

        if ($data['cards']) {
            $this->zeroColumn('templates', 'cards');
        }

        if ($data['vcards']) {
            $this->zeroColumn('templates', 'vcards');
        }

        if ($table->save($data)) {
            /** @var Incremented $table */
            return $table->id;
        }

        Application::message($table->getError(), Application::ERROR);

        return $id;
    }

    /**
     * Zeros out the values of the given column
     *
     * @param   string  $table   the table where the column is located
     * @param   string  $column  the column to be zeroed
     *
     * @return bool true on success, otherwise, false
     */
    protected function zeroColumn(string $table, string $column): bool
    {
        $db = Application::database();

        // Perform one query to set the column values to 0 instead of two for search and replace
        $query = $db->getQuery(true)
            ->update($db->quoteName("#__groups_$table"))
            ->set($db->quoteName($column) . " = 0");
        $db->setQuery($query);

        return $db->execute();
    }
}