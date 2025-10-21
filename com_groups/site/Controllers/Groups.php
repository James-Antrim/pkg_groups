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
use THM\Groups\Tables\{UserGroups, ViewLevels};

class Groups extends ListController
{
    private const ADD = '1', NO_ACTION = '', REMOVE = '0';
    private const BATCH_ACTIONS = [self::ADD, self::REMOVE];

    /** @inheritDoc */
    protected string $item = 'Group';

    /**
     * Batch processing allows assignment or removal of multiple access levels to/from multiple groups.
     *
     * @return void
     */
    public function batch(): void
    {
        $this->checkToken();
        $this->authorize();

        $groupIDs = Input::selectedIDs();
        $selected = count($groupIDs);

        $action = Input::batches()->get('action');
        if ($action === self::NO_ACTION or !in_array($action, self::BATCH_ACTIONS)) {
            $this->farewell($selected);
        }

        $levels   = Input::batches()->get('levels');
        $levelIDs = array_filter(array_map('intval', (array) $levels));
        $updated  = 0;

        foreach ($groupIDs as $groupID) {
            $altered = false;
            foreach ($levelIDs as $levelID) {

                $levels = new ViewLevels();
                if (!$levels->load($levelID)) {
                    continue;
                }

                $groups      = empty($levels->rules) ? [] : json_decode($levels->rules, true);
                $existingKey = array_search($groupID, $groups);
                $existent    = $existingKey !== false;

                if (($existent and $action === self::ADD) or (!$existent and $action === self::REMOVE)) {
                    continue;
                }

                if ($action === self::ADD) {
                    $groups[] = $groupID;
                    $groups   = array_unique($groups);
                }

                if ($action === self::REMOVE) {
                    unset($groups[$existingKey]);
                }

                $levels->rules = json_encode(array_values($groups));
                $levels->store();
                $altered = true;
            }

            if ($altered) {
                $updated++;
            }
        }

        $this->farewell($selected, $updated);
    }

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