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
use THM\Groups\Tables\UserGroups;

class Groups extends ListController
{
    /** @inheritDoc */
    protected string $item = 'Group';

    /** @inheritDoc */
    public function delete(): void
    {
        $this->checkToken();
        $this->authorize();

        if (!$selectedIDs = Input::selectedIDs()) {
            Application::message('NO_SELECTION', Application::WARNING);

            return;
        }

        $selected = count($selectedIDs);

        $deleted = 0;

        foreach ($selectedIDs as $selectedID) {
            $table = new UserGroups();

            if ($table->delete($selectedID)) {
                $deleted++;
            }
        }

        $this->farewell($selected, $deleted, true);
    }
}