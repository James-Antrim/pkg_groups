<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Text;

class Users
{
    public static function createAlias(int $accountID, string $identifier): string
    {
        $alias = Text::trim($identifier);
        $alias = Text::transliterate($alias);
        $alias = Text::filter($alias);
        $alias = str_replace(' ', '-', $alias);

        $db    = Application::getDB();
        $id    = $db->quoteName('id');
        $query = $db->getQuery(true);
        $query->select($id)
            ->from($db->quoteName('#__users'))
            ->where("$id != $accountID")
            ->where($db->quoteName('alias') . " = :alias")
            ->bind(':alias', $currentAlias);

        // Check for an existing alias which matches the base alias for the profile and react. (duplicate names)
        $initial = true;
        $number  = 1;

        while (true) {
            $currentAlias = $initial ? $alias : "$alias-$number";
            $db->setQuery($query);

            if (!$db->loadResult()) {
                return $currentAlias;
            }

            $initial = false;
            $number++;
        }
    }
}