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

use JetBrains\PhpStorm\NoReturn;
use THM\Groups\Adapters\{Application, Database as DB, Input, Text};
use THM\Groups\Helpers\{Categories as CaHelper, Pages as Helper, Users as UHelper};
use THM\Groups\Tables\{Content as CTable, Pages as PTable};

abstract class Contented extends ListController
{
    /**
     * Sets the page related content to archived.
     * @return void
     */
    public function archive(): void
    {
        $this->update('state', Helper::ARCHIVED);
    }

    /**
     * Sets the page related content to archived.
     * @return void
     */
    public function checkin(): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedID = Input::selectedID();
        $table      = new CTable();
        $updated    = $table->checkIn($selectedID) ? 1 : 0;

        $this->farewell(1, $updated);
    }

    /** @inheritDoc */
    public function delete(): void
    {
        $this->checkToken();
        $this->authorize();

        $myCategoryID = 0;

        if ($profileID = Input::integer('profileID')) {
            $myCategoryID = UHelper::categoryID($profileID);
        }

        if (!$selectedIDs = Input::selectedIDs()) {
            $root  = CaHelper::root();
            $query = DB::query();
            $query->select(DB::qn('co.id'))
                ->from(DB::qn('#__content', 'co'))
                ->innerJoin(DB::qn('#__categories', 'ca'), DB::qc('ca.id', 'co.catid'))
                ->where(DB::qcs([['ca.parent_id', $root], ['co.state', Helper::TRASHED]]));

            if ($myCategoryID) {
                $query->where(DB::qn('co.catid', $myCategoryID));
            }

            DB::set($query);
            $selectedIDs = DB::integers();
        }

        $deleted  = 0;
        $selected = count($selectedIDs);

        foreach ($selectedIDs as $selectedID) {
            $table = new CTable();

            if (!$table->load($selectedID) or $table->state !== Helper::TRASHED) {
                Application::message('412');
                continue;
            }

            if ($myCategoryID and $table->catid !== $myCategoryID) {
                Application::message('403');
                continue;
            }

            if ($table->delete($selectedID)) {
                $deleted++;
            }
        }

        $this->farewell($selected, $deleted, true);
    }

    /**
     * Adds the selected pages in the user's personal menu.
     * @return void
     */
    public function feature(): void
    {
        $this->update('featured', Helper::FEATURED);
    }

    /**
     * Sets the page related content to hidden.
     * @return void
     */
    public function hide(): void
    {
        $this->update('state', Helper::HIDDEN);
    }

    /**
     * Sets the page related content to published.
     * @return void
     */
    public function publish(): void
    {
        $this->update('state', Helper::PUBLISHED);
    }

    /** @inheritDoc */
    #[NoReturn] public function saveOrderAjax(): void
    {
        $this->checkToken();
        $this->authorizeAJAX();

        $ordering    = 0;
        $resourceIDs = Input::array('cid');

        foreach ($resourceIDs as $resourceID) {
            $table = new PTable();
            $table->load(['contentID' => $resourceID]);
            $table->ordering = $ordering;
            $table->store();
            $ordering++;
        }

        echo Text::_('Request performed successfully.');

        Application::close();
    }

    /**
     * Removes the selected pages from the user's personal menu.
     * @return void
     */
    public function unfeature(): void
    {
        $this->update('featured', Helper::UNFEATURED);
    }

    /**
     * Updates a series of column values.
     *
     * @param   string  $column  the column in which the values are stored
     * @param   bool    $value   the target value
     *
     * @return void
     */
    protected function update(string $column, bool|int $value): void
    {
        $this->checkToken();

        $selectedIDs = Input::selectedIDs();
        $selected    = count($selectedIDs);

        $updated = $column === 'featured' ?
            $this->updateFeatured($selectedIDs, $value) :
            $this->updateState($selectedIDs, $value);

        $this->farewell($selected, $updated);
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
            $table = new PTable();

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
     * @param   int    $value        the value to update to
     *
     * @return int
     */
    protected function updateState(array $selectedIDs, int $value): int
    {
        $total = 0;

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

    /**
     * Sets the page related content to trashed.
     * @return void
     */
    public function trash(): void
    {
        $this->update('state', Helper::TRASHED);
    }
}