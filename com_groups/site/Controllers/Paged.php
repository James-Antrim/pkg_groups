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

use THM\Groups\Helpers\Pages as Helper;
use THM\Groups\Tables\Pages as Table;

trait Paged
{
    /**
     * Associates content with a given user:
     *
     * @param   int  $contentID  the id of the content
     * @param   int  $userID     the id of the user to be associated with the content
     *
     * @return  bool
     */
    public static function page(int $contentID, int $userID): bool
    {
        $table = new Table();
        if ($table->load(['contentID' => $contentID])) {
            $table->userID = $userID;
            return $table->store();
        }

        return $table->save(['contentID' => $contentID, 'userID' => $userID, 'ordering' => Helper::next(['userID' => $userID])]);
    }

    /**
     * Removed the groups association for the given content.
     *
     * @param   int  $contentID
     *
     * @return bool
     */
    public static function unpage(int $contentID): bool
    {
        $table = new Table();
        if ($table->load(['contentID' => $contentID])) {
            return $table->delete();
        }

        return true;
    }
}