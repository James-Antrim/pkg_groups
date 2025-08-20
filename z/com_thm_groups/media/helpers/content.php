<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{Application, Database as DB, Input, Text, User as UAdapter};
use THM\Groups\Controllers\Page as Controller;
use THM\Groups\Helpers\{Can, Pages};
use THM\Groups\Tables\Content as CTable;

/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperContent
{
    /**
     * Method which checks user edit state permissions for content.
     *
     * @param   int  $contentID  the id of the content
     *
     * @return  bool
     */
    public static function canEditState(int $contentID): bool
    {
        if (Can::edit('com_content.article', $contentID)) {
            return true;
        }

        return UAdapter::authorise('core.edit.state', "com_content.article.$contentID");
    }

    /**
     * Checks whether the user has permission to edit the content associated with the ids provided.
     *
     * @param   array  $contentIDs  the content ids submitted by the form
     *
     * @return bool
     */
    private static function canReorder(array $contentIDs): bool
    {
        foreach ($contentIDs as $contentID) {
            if (!self::canEditState($contentID)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Corrects invalid content => author associations which occur because Joomla does not call events from batch
     * processing.
     */
    public static function correctContent(): void
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
                if ($table->load($result['contentID'])) {
                    $table->created_by = $result['caUserID'];
                    $table->store();
                }
            }

            if (empty($result['pUserID']) or $result['pUserID'] !== $result['caUserID']) {
                Controller::associate($result['contentID'], $result['caUserID']);
            }
        }
    }

    /**
     * Disassociates content
     *
     * @param   int  $contentID  the id of the content
     *
     * @return  bool
     */
    public static function disassociate(int $contentID): bool
    {
        $query = DB::query();
        $query->delete(DB::qn('#__thm_groups_content'))->where(DB::qc('id', $contentID));
        DB::set($query);
        return DB::execute();
    }

    /**
     * Returns dropdown for changing content status
     *
     * @param   int|string  $index  the current row index
     * @param   stdClass    $item   the content item being iterated
     *
     * @return  string the HTML for the status selection dialog
     */
    public static function getStatusDropdown(int|string $index, stdClass $item): string
    {
        $status    = '';
        $canChange = self::canEditState($item->id);

        $task = 'content.publish';

        $status .= '<div class="btn-group">';
        $status .= JHtml::_('jgrid.published', $item->state, $index, "$task.", $canChange, 'cb', $item->publish_up,
            $item->publish_down);

        $archive = $item->state == 2 ? 'unarchive' : 'archive';
        $status  .= JHtml::_('actionsdropdown.' . $archive, 'cb' . $index, $task);

        $trash  = $item->state == -2 ? 'untrash' : 'trash';
        $status .= JHtml::_('actionsdropdown.' . $trash, 'cb' . $index, $task);

        $status .= JHtml::_('actionsdropdown.render', DB::escape($item->title));
        $status .= "</div>";

        return $status;
    }

    /**
     * Method to change the core published state of THM Groups articles.
     *
     * @return  bool
     */
    public static function publish(): bool
    {
        if (!$contentIDs = Input::selectedIDs() or empty($contentIDs[0])) {
            return false;
        }

        $contentID = $contentIDs[0];

        if (!self::canEditState($contentID)) {
            Application::message(Text::_('JLIB_RULES_NOT_ALLOWED'), Application::ERROR);

            return false;
        }

        $taskParts     = explode('.', Input::task());
        $status        = count($taskParts) == 3 ? $taskParts[2] : 'unpublish';
        $validStatuses = ['publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2, 'report' => -3];

        // Unarchive and untrash equate to unpublish.
        $statusValue = Joomla\Utilities\ArrayHelper::getValue($validStatuses, $status, 0, 'int');

        $table = new CTable();
        if ($table->publish($contentID, $statusValue, UAdapter::id())) {
            return true;
        }

        Application::message(Text::_('STATE_FAIL'), Application::ERROR);

        return false;
    }

    /**
     * Parses the given string to check for content associated with the component
     *
     * @param   string  $potentialContent  the segment being checked
     * @param   int     $userID            the ID of the profile with which this content should be associated
     *
     * @return int the id of the associated content if existent, otherwise 0
     */
    public static function resolve(string $potentialContent, int $userID = 0): int
    {
        $contentID = 0;
        if (is_numeric($potentialContent)) {
            $contentID = (int) $potentialContent;
        }
        elseif (preg_match('/^(\d+)\-[a-zA-Z\-]+$/', $potentialContent, $matches)) {
            $contentID = (int) $matches[1];
        }

        if (!$contentID) {
            return $contentID;
        }

        return Pages::userID($contentID, $userID) ? $contentID : 0;
    }

    /**
     * Saves drag & drop ordering changes.
     *
     * @param   array  $contentIDs  an array of primary content ids
     * @param   array  $order       the order for the content items
     *
     * @return  bool true on success, otherwise false
     */
    public static function saveorder(array $contentIDs, array $order): bool
    {
        if (empty($contentIDs) or !self::canReorder($contentIDs)) {
            return false;
        }

        $table      = new CTable();
        $conditions = [];

        // Update ordering values
        foreach ($contentIDs as $index => $contentID) {
            $table->load($contentID);

            if ($table->ordering != $order[$index]) {
                $table->ordering = $order[$index];

                if (!$table->store()) {
                    return false;
                }

                // Remember to reorder within position and client_id
                $condition   = [];
                $condition[] = 'catid = ' . $table->catid;

                $found = false;

                foreach ($conditions as $cond) {
                    if ($cond[1] == $condition) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $key          = $table->getKeyName();
                    $conditions[] = [$table->$key, $condition];
                }
            }
        }

        // Execute reorder for each category.
        foreach ($conditions as $cond) {
            $table->load($cond[0]);
            $table->reorder($cond[1]);
        }

        return true;
    }

    /**
     * Checks the THM Groups featured value for the chosen content
     *
     * @param   int  $contentID  the content id
     * @param   int  $value      the THM Groups featured flag for the given article
     *
     * @return  bool
     */
    private static function setFeatured(int $contentID, int $value): bool
    {
        $query = DB::query();

        if (Pages::userID($contentID)) {
            $query->update(DB::qn('#__groups_content'))->set(DB::qc('featured', $value))->where(DB::qc('id', $contentID));
        }
        else {
            $query->insert(DB::qn('#__thm_groups_content'))
                ->columns(DB::qn(['profileID', 'contentID', 'featured']))
                ->values([UAdapter::id(), $contentID, $value]);
        }

        DB::set($query);
        return DB::execute();
    }

    /**
     * Toggles the THM Groups 'featured' value for associated content.
     *
     * @return  bool  true on success, otherwise false
     */
    public static function toggle(): bool
    {
        $selectedContent = Input::selectedIDs();
        $toggleID        = Input::id();
        $value           = Input::bool('value');

        // Should never occur without request manipulation
        if (empty($selectedContent) and empty($toggleID)) {
            return false;
        } // The inline toggle was used.
        elseif (empty($selectedContent)) {
            $selectedContent = [$toggleID];

            // Toggled values reflect the current value not the desired value
            $value = !$value;
        }

        foreach ($selectedContent as $contentID) {
            $asset = "com_content.article.$contentID";
            if (UAdapter::authorise('core.edit.own', $asset) or UAdapter::authorise('core.edit.state', $asset)) {
                if (!self::setFeatured($contentID, $value)) {
                    return false;
                }
            }
            else {
                Application::message(Text::_('JLIB_RULES_NOT_ALLOWED'), Application::ERROR);

                return false;
            }
        }

        return true;
    }
}
