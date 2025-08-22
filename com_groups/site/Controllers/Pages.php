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

use THM\Groups\Adapters\Database as DB;
use THM\Groups\Tables\Content as CTable;

/** @inheritDoc */
class Pages extends ListController
{
    /** @inheritDoc */
    protected string $item = 'Page';

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
}