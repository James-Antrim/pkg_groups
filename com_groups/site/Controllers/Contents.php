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

use THM\Groups\Adapters\{Application, Database as DB, Input, Text};
use THM\Groups\Helpers\Can;
use THM\Groups\Tables\{Categories as CaTable, Content as CoTable};

/** @inheritDoc */
class Contents extends Contented
{
    /** @inheritDoc */
    protected string $item = 'Content';

    /** @inheritDoc */
    protected function authorizeAJAX(): void
    {
        if (!Can::manage('com_content')) {
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
        return;
        $query = DB::query();
        $query->select(DB::qn(
            ['co.id', 'co.created_by', 'p.userID', 'ca.id', 'ca.created_user_id', 'u.id'],
            ['contentID', 'coUserID', 'pUserID', 'categoryID', 'caUserID', 'userID']
        ))
            ->from(DB::qn('#__content', 'co'))
            ->innerJoin(DB::qn('#__categories', 'ca'), DB::qc('ca.id', 'co.catid'))
            ->leftJoin(DB::qn('#__users', 'u'), DB::qc('u.alias', 'ca.alias'))
            ->leftJoin(DB::qn('#__groups_pages', 'p'), DB::qc('p.contentID', 'co.id'));
        DB::set($query);

        foreach (DB::arrays() as $result) {
            if (empty($result['caUserID'])) {
                // Authorship cannot be resolved here
                if (empty($result['userID'])) {
                    continue;
                }

                $category = new CaTable();
                // A seemingly bigger problem which can also not be resolved here
                if (!$category->load($result['categoryID'])) {
                    continue;
                }

                $category->created_user_id = $result['userID'];
                $category->store();
            }

            $syncID = empty($result['caUserID']) ? $result['userID'] : $result['caUserID'];

            // Sync category users with content authors
            if ($result['coUserID'] !== $result['caUserID']) {
                $table = new CoTable();
                $table->load($result['contentID']);
                $table->created_by = $syncID;
                $table->store();
            }

            // Sync category users with page users
            if (empty($result['pUserID']) or $result['pUserID'] !== $syncID) {
                Page::associate($result['contentID'], $syncID);
            }
        }
    }

    /** @inheritDoc */
    protected function toggle(string $column, bool $value): void
    {
        $this->checkToken();

        $selectedIDs = Input::selectedIDs();
        $selected    = count($selectedIDs);

        $updated = $column === 'featured' ?
            $this->updateFeatured($selectedIDs, $value) :
            $this->updateState($selectedIDs, $value);

        $this->farewell($selected, $updated);
    }
}