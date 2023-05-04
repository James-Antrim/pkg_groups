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
    use Persistent;

    public const ACTIVATED = true, PENDING = false;
    public const ENABLED = 1, DISABLED = 0;
    public const BLOCKED = 1, UNBLOCKED = 0;
    public const PUBLISHED = 1, UNPUBLISHED = 0;

    // Pending state comes into effect through user interaction with the login component.
    public const activatedStates = [
        self::ACTIVATED => [
            'class' => 'publish',
            'column' => 'activated',
            'task' => '',
            'tip' => 'GROUPS_TOGGLE_TIP_USER_ACTIVATED'
        ],
        self::PENDING => [
            'class' => 'unpublish',
            'column' => 'activated',
            'task' => 'activate',
            'tip' => 'GROUPS_TOGGLE_TIP_USER_PENDING'
        ]];

    // Display semantic is reversed
    public const blockedStates = [
        self::BLOCKED => [
            'class' => 'unpublish',
            'column' => 'block',
            'task' => 'unblock',
            'tip' => 'GROUPS_TOGGLE_TIP_USER_BLOCKED'
        ],
        self::UNBLOCKED => [
            'class' => 'publish',
            'column' => 'block',
            'task' => 'block',
            'tip' => 'GROUPS_TOGGLE_TIP_USER_UNBLOCKED'
        ]];

    public const contentStates = [
        self::ENABLED => [
            'class' => 'publish',
            'column' => 'content',
            'task' => 'disableContent',
            'tip' => 'GROUPS_TOGGLE_TIP_CONTENTS_ENABLED'
        ],
        self::DISABLED => [
            'class' => 'unpublish',
            'column' => 'content',
            'task' => 'enableContent',
            'tip' => 'GROUPS_TOGGLE_TIP_CONTENTS_DISABLED'
        ]];

    public const editingStates = [
        self::ENABLED => [
            'class' => 'publish',
            'column' => 'editing',
            'task' => 'disableEditing',
            'tip' => 'GROUPS_TOGGLE_TIP_EDITING_ENABLED'
        ],
        self::DISABLED => [
            'class' => 'unpublish',
            'column' => 'editing',
            'task' => 'enableEditing',
            'tip' => 'GROUPS_TOGGLE_TIP_EDITING_DISABLED'
        ]];

    public const publishedStates = [
        self::PUBLISHED => [
            'class' => 'publish',
            'column' => 'published',
            'task' => 'unpublish',
            'tip' => 'GROUPS_TOGGLE_TIP_PROFILE_PUBLISHED'
        ],
        self::UNPUBLISHED => [
            'class' => 'unpublish',
            'column' => 'published',
            'task' => 'publish',
            'tip' => 'GROUPS_TOGGLE_TIP_PROFILE_UNPUBLISHED'
        ]];

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

    /**
     * Gets the value of a table state property.
     * @param int $personID
     * @param string $property
     *
     * @return bool
     */
    public static function get(int $personID, string $property): bool
    {
        $account = self::getTable();
        $account->load($personID);

        if (property_exists($account, $property) and is_bool($account->$$property)) {
            return $account->$$property;
        }

        return false;
    }
}