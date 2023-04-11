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

use THM\Groups\Tables\Users;
use THM\Groups\Tools\Cohesion;

class Persons
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

    // Attributes protected because of their special display in various templates
    /*public const PROTECTED = [
        self::EMAIL,
        self::FIRST_NAME,
        self::IMAGE,
        self::NAME,
        self::SUPPLEMENT_POST,
        self::SUPPLEMENT_PRE
    ];*/

    /**
     * Gets the value of a table state property.
     * @param int $personID
     * @param string $property
     *
     * @return bool
     */
    public static function get(int $personID, string $property): bool
    {
        $person = self::getTable();
        $person->load($personID);

        if (property_exists($person, $property) and is_bool($person->$$property))
        {
            return $person->$$property;
        }

        $user = new Users();
        $user->load($personID);

        if (property_exists($user, $property) and is_bool($user->$$property))
        {
            return $user->$$property;
        }

        return false;
    }

    /**
     * Gets the person's forenames.
     *
     * @param int $personID
     *
     * @return string
     *
     */
    public static function getForenames(int $personID): string
    {
        return self::get($personID, 'forenames') ?? '';
    }

    /**
     * Gets the person's surnames.
     *
     * @param int $personID
     *
     * @return string
     *
     */
    public static function getSurnames(int $personID): string
    {
        return self::get($personID, 'surnames') ?? '';
    }

    /**
     * Gets the persons names.
     *
     * @param int $personID the id of the person
     *
     * @return array empty if no surnames could be found
     */
    public static function getNames(int $personID): array
    {
        if (!$surnames = self::getSurnames($personID))
        {
            Cohesion::createBasicAttributes($personID);
            $surnames = self::getSurnames($personID);
        }

        return ['surnames' => $surnames, 'forenames' => self::getForenames($personID)];
    }
}