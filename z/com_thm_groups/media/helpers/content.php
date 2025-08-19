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
use THM\Groups\Helpers\{Can, Pages, Users as UHelper};
use THM\Groups\Tables\Content as Table;


/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperContent
{
    /**
     * Associates content with a given user:
     *
     * @param   int  $contentID  the id of the content
     * @param   int  $userID     the id of the user to be associated with the content
     *
     * @return  bool
     */
    public static function associate(int $contentID, int $userID): bool
    {
        $query = DB::query();

        if (self::isAssociated($contentID)) {
            $query->update(DB::qn('#__groups_content'))->set(DB::qc('userID', $userID))->where(DB::qc('id', $contentID));
        }
        else {
            $query->insert(DB::qn('#__groups_content'))->columns(DB::qn(['id', 'userID']))->values([$contentID, $userID]);
        }

        DB::set($query);
        return DB::execute();
    }

    /**
     * Checks whether the user is authorized to edit the given content
     *
     * @param   int  $contentID  the id of the content
     *
     * @return bool
     */
    public static function canEdit(int $contentID): bool
    {
        if (Can::manage()) {
            return true;
        }

        $canEdit    = UAdapter::authorise('core.edit', "com_content.article.$contentID");
        $canEditOwn = UAdapter::authorise('core.edit.own', "com_content.article.$contentID");
        $profileID  = Pages::authorID($contentID);
        $isOwn      = $profileID === UAdapter::id();

        // Regardless of configuration only administrators and content owners should be able to edit
        $editEnabled    = (($canEdit or $canEditOwn) and $isOwn);
        $isPublished    = UHelper::published($profileID);
        $contentEnabled = UHelper::content($profileID);
        $profileEnabled = ($isPublished and $contentEnabled);

        return ($editEnabled and $profileEnabled);
    }

    /**
     * Method which checks user edit state permissions for content.
     *
     * @param   int  $contentID  the id of the content
     *
     * @return  bool
     */
    public static function canEditState(int $contentID): bool
    {
        if (self::canEdit($contentID)) {
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
            ['content.id', 'content.created_by', 'gc.userID', 'categories.created_user_id'],
            ['contentID', 'authorID', 'gAuthorID', 'catUserID']
        ))
            ->from(DB::qn('#__content', 'content'))
            ->innerJoin(DB::qn('#__categories', 'categories'), DB::qc('categories.id', 'content.catid'))
            ->leftJoin(DB::qn('#__thm_groups_content', 'groupsContent'), DB::qc('gc.id', 'content.id'));
        DB::set($query);

        foreach (DB::arrays() as $association) {
            // The content author isn't set as the user associated with its parent category.
            if ($association['authorID'] !== $association['catUserID']) {
                $table = new Table();
                if ($table->load($association['contentID'])) {
                    $table->created_by = $association['catUserID'];
                    $table->store();
                }
            }

            if (empty($association['gAuthorID']) or $association['gAuthorID'] !== $association['authorID']) {
                self::associate($association['contentID'], $association['catUserID']);
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
     * Retrieves the alias for the given content id
     *
     * @param   int  $contentID  the id of the content
     *
     * @return  string the alias of the content
     */
    public static function getAlias(int $contentID): string
    {
        $query = DB::query();
        $query->select(DB::qn('cc.alias'))
            ->from(DB::qn('#__content', 'cc'))
            ->innerJoin(DB::qn('#__thm_groups_content', 'gc'), DB::qc('gc.id', 'cc.id'))
            ->where(DB::qc('cc.id', $contentID));
        DB::set($query);
        return DB::string();
    }

    /**
     * Retrieves the id for the given content by its associated alias
     *
     * @param   string  $alias      the alias associated with the content
     * @param   int     $profileID  the id of the profile which is associated with this content
     *
     * @return  int the id of the content
     */
    public static function getIDByAlias(string $alias, int $profileID): int
    {
        $query = DB::query();
        $query->select(DB::qn('cc.id'))
            ->from(DB::qn('#__content AS cc'))
            ->innerJoin(DB::qn('#__thm_groups_content', 'gc'), DB::qc('gc.id', 'cc.id'))
            ->where(DB::qcs([['alias', $alias, '=', true], ['profileID', $profileID]]));
        DB::set($query);
        return DB::integer();
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
        $canChange = THM_GroupsHelperContent::canEditState($item->id);

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
     * Retrieves the title for the given content id
     *
     * @param   int  $contentID  the id of the content
     *
     * @return  string the alias of the content
     */
    public static function getTitle(int $contentID): string
    {
        $query = DB::query();
        $query->select(DB::qn('cc.title'))
            ->from(DB::qn('#__content', 'cc'))
            ->innerJoin(DB::qn('#__thm_groups_content', 'gc'), DB::qc('gc.id', 'cc.id'))
            ->where(DB::qc('cc.id', $contentID));
        DB::set($query);
        return DB::string();
    }

    /**
     * Checks if the content is already associated with THM_Groups
     *
     * @param   int  $contentID  the id of the content
     * @param   int  $profileID  the id of the profile associated with the content
     *
     * @return  int  the profileID of the associated profile if associated, otherwise 0
     */
    public static function isAssociated(int $contentID, int $profileID = 0): int
    {
        $query = DB::query();
        $query->select(DB::qn('profileID'))->from(DB::qn('#__thm_groups_content'))->where(DB::qc('id', $contentID));

        if ($profileID) {
            $query->where(DB::qc('profileID', $profileID));
        }

        DB::set($query);
        return DB::integer();
    }

    /**
     * Method to check whether the content is published
     *
     * @param   int  $contentID  the id of the content
     *
     * @return  bool  true on success, otherwise false
     */
    public static function isPublished(int $contentID): bool
    {
        $table = new Table();
        $table->load($contentID);

        return $table->state === 1;
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

        $table = new Table();
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
     * @param   int     $profileID         the ID of the profile with which this content should be associated
     *
     * @return int the id of the associated content if existent, otherwise 0
     */
    public static function resolve(string $potentialContent, int $profileID = 0): int
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

        $profileID = self::isAssociated($contentID, $profileID);

        return self::isAssociated($contentID, $profileID) ? $contentID : 0;
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

        $table      = new Table();
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

        if (self::isAssociated($contentID)) {
            $query->update(DB::qn('#__thm_groups_content'))->set(DB::qc('featured', $value))->where(DB::qc('id', $contentID));
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
