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

use THM\Groups\Adapters\{Application, Input, Text, User};
use THM\Groups\Helpers\{Can, Pages as Helper, Users};

/** @inheritDoc */
class Pages extends Contented
{
    /** @inheritDoc */
    protected string $item = 'Page';

    /** @inheritDoc */
    protected function authorize(): void
    {
        if (Can::manage('com_content')) {
            return;
        }

        if ($profileID = Input::integer('profileID') and $profileID === User::id()) {
            return;
        }

        Application::error(403);
    }

    /** @inheritDoc */
    protected function authorizeAJAX(): void
    {
        if (Can::manage('com_content')) {
            return;
        }

        if ($profileID = Input::integer('profileID') and $profileID === User::id()) {
            return;
        }

        echo Text::_('403');
        Application::close();
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
    protected function update(string $column, bool|int $value): void
    {
        $this->checkToken();

        $selected    = count(Input::selectedIDs());
        $selectedIDs = $this->selectedIDs();

        $updated = $column === 'featured' ?
            $this->updateFeatured($selectedIDs, $value) :
            $this->updateState($selectedIDs, $value);

        $this->farewell($selected, $updated);
    }
}