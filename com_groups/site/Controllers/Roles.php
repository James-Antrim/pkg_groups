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

use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Helpers\Can;
use THM\Groups\Tables\Roles as Table;

class Roles extends Controller
{
    /**
     * @inheritdoc
     */
    protected string $item = 'Role';

    /**
     * Calls the appropriate model delete function and redirects to the appropriate list. Authorization occurs in the
     * called model.
     */
    public function delete(): void
    {
        $this->checkToken();

        if (!Can::administrate()) {
            Application::error(403);
        }

        if (!$selectedIDs = Input::getSelectedIDs()) {
            Application::message('GROUPS_NO_SELECTION', Application::WARNING);

            return;
        }

        $deleted  = 0;
        $selected = count($selectedIDs);

        foreach ($selectedIDs as $selectedID) {
            $table = $this->getTable();

            if (!$table->load($selectedID)) {
                continue;
            }

            if ($table->delete()) {
                $deleted++;
            }
        }

        $db    = Application::getDB();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('id'))
            ->from($db->quoteName('#__groups_roles'))
            ->order($db->quoteName('ordering'));
        $db->setQuery($query);

        $results  = $db->loadColumn();
        $results  = Input::formatIDValues($results);
        $ordering = 1;

        foreach ($results as $roleID) {
            /** @var Table $table */
            $table = $this->getTable();
            $table->load($roleID);
            $table->ordering = $ordering;
            $table->store();
            $ordering++;
        }

        $this->farewell($selected, $deleted, true);
    }
}