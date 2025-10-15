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
use THM\Groups\Adapters\{Application, Input, Text};
use THM\Groups\Helpers\Pages as Helper;
use THM\Groups\Tables\{Content as CTable, Pages as PTable, Pages as PaTable};

abstract class Contented extends ListController
{
    /**
     * Sets the page related content to archived.
     * @return void
     */
    public function archive(): void
    {
        $this->toggle('state', Helper::ARCHIVED);
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
     * Sets the page related content to published.
     * @return void
     */
    public function publish(): void
    {
        $this->toggle('state', Helper::PUBLISHED);
    }

    /** @inheritDoc */
    #[NoReturn] public function saveOrderAjax(): void
    {
        $this->checkToken();
        $this->authorizeAJAX();

        $ordering    = 0;
        $resourceIDs = Input::array('cid');

        foreach ($resourceIDs as $resourceID) {
            $table = new PaTable();
            $table->load(['contentID' => $resourceID]);
            $table->ordering = $ordering;
            $table->store();
            $ordering++;
        }

        echo Text::_('Request performed successfully.');

        Application::close();
    }

    /** @inheritDoc */
    protected function toggle(string $column, bool $value): void
    {
        // Inheriting classes must implement this themselves
    }

    /**
     * Sets the page related content to trashed.
     * @return void
     */
    public function trash(): void
    {
        $this->toggle('state', Helper::TRASHED);
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