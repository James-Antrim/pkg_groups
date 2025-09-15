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
use THM\Groups\Helpers\{Can, Categories};
use THM\Groups\Tables\{Categories as CaTable, Content as CoTable};

/** @inheritDoc */
class Contents extends Contented
{
    use Paged;

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
        if (!$rootID = Categories::root()) {
            Application::message('NO_ROOT', Application::WARNING);
            return;
        }

        $query = DB::query()
            ->select(DB::qn(
                ['ca.id', 'ca.created_user_id', 'co.id', 'co.created_by', 'p.userID'],
                ['caID', 'caUID', 'coID', 'coUID', 'pUID']
            ))
            ->from(DB::qn('#__content', 'co'))
            ->innerJoin(DB::qn('#__categories', 'ca'), DB::qc('ca.id', 'co.catid'))
            ->leftJoin(DB::qn('#__groups_pages', 'p'), DB::qc('p.contentID', 'co.id'))
            ->where(DB::qc('ca.parent_id', $rootID));
        DB::set($query);

        $deprecatedCategories = [];
        foreach (DB::arrays('coID') as $result) {
            $categoryID  = $result['caID'];
            $categoryUID = $result['caUID'];
            $contentID   = $result['coID'];
            $contentUID  = $result['coUID'];
            $pageUID     = $result['pUID'];

            $capConsistent  = ($categoryUID === $pageUID);
            $cocaConsistent = ($contentUID === $categoryUID);

            if ($cocaConsistent and $capConsistent) {
                // Category, content and page reference the same user.
                if ($contentUID) {
                    continue;
                }

                // Both category and content do not reference a user => deleted user with deprecated personal content
                $table = new CoTable();
                $table->load($contentID);
                $table->delete();

                // Delete category when content has been removed
                $deprecatedCategories[$categoryID] = $categoryID;
                continue;
            }

            if ($cocaConsistent) {
                // Category and content reference the same user => update the page user
                if ($contentUID) {
                    self::page($contentID, $contentUID);
                    continue;
                }

                // Category and content reference no user => set to page user
                $table = new CoTable();
                $table->load($contentID);
                $table->created_by = $pageUID;
                $table->store();
                $table = new CaTable();
                $table->load($categoryID);
                $table->created_user_id = $pageUID;
                $table->store();
                continue;
            }

            // Content created for user category by a content manager
            if ($categoryUID and $categoryUID === Categories::userID($categoryID)) {
                $table = new CoTable();
                $table->load($contentID);
                $table->created_by = $categoryUID;
                $table->store();

                self::page($contentID, $categoryUID);
            }
        }

        // This allows joomla to run table related consistency functions such as those related to nesting.
        foreach ($deprecatedCategories as $categoryID) {
            $table = new CaTable();
            $table->load($categoryID);
            $table->delete();
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