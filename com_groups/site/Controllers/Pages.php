<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use THM\Groups\Adapters\{Application, Database as DB, Input, Text, User};
use THM\Groups\Helpers\{Can, Pages as Helper, Users};
use THM\Groups\Tables\{Content as CTable, Pages as PTable};

/** @inheritDoc */
class Pages extends ListController
{
    /** @inheritDoc */
    protected string $item = 'Page';

    /** @inheritDoc */
    protected function authorizeAJAX(): void
    {
        if (!$this->selectedIDs()) {
            echo Text::_('403');
            Application::close();
        }
    }

    /**
     * Corrects discrepancies in authorship / associations which can creep in through inconsistent handling of content
     * resources being saved by other extensions.
     * @return void
     */
    public static function clean(): void
    {
        $query = DB::query();
        $query->select(DB::qn(
            ['co.id', 'co.created_by', 'p.userID', 'categories.created_user_id'],
            ['contentID', 'coUserID', 'pUserID', 'caUserID']
        ))
            ->from(DB::qn('#__content', 'co'))
            ->innerJoin(DB::qn('#__categories', 'ca'), DB::qc('ca.id', 'co.catid'))
            ->leftJoin(DB::qn('#__groups_pages', 'p'), DB::qc('p.contentID', 'co.id'));
        DB::set($query);

        foreach (DB::arrays() as $result) {
            if ($result['coUserID'] !== $result['caUserID']) {
                $table = new CTable();
                $table->load($result['contentID']);
                $table->created_by = $result['caUserID'];
                $table->store();
            }

            if (empty($result['pUserID']) or $result['pUserID'] !== $result['caUserID']) {
                Page::associate($result['contentID'], $result['caUserID']);
            }
        }
    }

    /**
     * Adds the selected pages in the user's personal menu.
     * @return void
     */
    public function feature(): void
    {
        $this->toggle('featured', Helper::FEATURED);
    }

    /**
     * Sets the page related content to hidden.
     * @return void
     */
    public function hide(): void
    {
        $this->toggle('state', Helper::HIDDEN);
    }

    /**
     * Sets the page related content to hidden.
     * @return void
     */
    public function publish(): void
    {
        $this->toggle('state', Helper::PUBLISHED);
    }

    /**
     * Prefilters selected ids to those authorized for ordering / state change operations.
     * @return array
     */
    private function selectedIDs(): array
    {
        if (!$userID = User::id()) {
            Application::error(401);
        }

        $selectedIDs = Input::selectedIDs();

        if (Can::changeState('com_content')) {
            return $selectedIDs;
        }

        $categoryID = Users::categoryID($userID);

        foreach ($selectedIDs as $key => $pageID) {
            if (User::authorise('core.edit.state', "com_content.article.$pageID")) {
                continue;
            }
            if ($categoryID === Helper::categoryID($pageID)) {
                continue;
            }
            unset($selectedIDs[$key]);
        }

        return $selectedIDs;
    }

    /** @inheritDoc */
    protected function toggle(string $column, bool $value): void
    {
        $this->checkToken();

        $selected    = count(Input::selectedIDs());
        $selectedIDs = $this->selectedIDs();

        $updated = $column === 'featured' ?
            $this->updateFeatured($selectedIDs, $value) :
            $this->updateState($selectedIDs, $value);

        $this->farewell($selected, $updated);
    }

    /**
     * Removes the selected pages from the user's personal menu.
     * @return void
     */
    public function unfeature(): void
    {
        $this->toggle('featured', Helper::UNFEATURED);
    }

    /**
     * Updates the featured column for the pages table.
     *
     * @param   array  $selectedIDs  the ids of the resources whose properties will be updated
     * @param   bool   $value        the value to update to
     *
     * @return int
     */
    protected function updateFeatured(array $selectedIDs, bool $value): int
    {
        $total = 0;
        $value = (int) $value;

        foreach ($selectedIDs as $selectedID) {
            /** @var PTable $table */
            $table = $this->getTable();

            if ($table->load(['contentID' => $selectedID]) and $table->featured !== $value) {
                $table->featured = $value;

                if ($table->store()) {
                    $total++;
                }
            }
            elseif ($table->save(['contentID' => $selectedID, 'userID' => Helper::userID($selectedID), 'featured' => $value])) {
                $total++;
            }
        }

        return $total;
    }

    /**
     * Updates the state column for the content table.
     *
     * @param   array  $selectedIDs  the ids of the resources whose properties will be updated
     * @param   bool   $value        the value to update to
     *
     * @return int
     */
    protected function updateState(array $selectedIDs, bool $value): int
    {
        $total = 0;
        $value = (int) $value;

        foreach ($selectedIDs as $selectedID) {
            $table = new CTable();

            if ($table->load($selectedID) and $table->state !== $value) {
                $table->state = $value;

                if ($table->store()) {
                    $total++;
                }
            }
        }

        return $total;
    }
}