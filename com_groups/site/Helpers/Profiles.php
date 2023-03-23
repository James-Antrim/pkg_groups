<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use THM\Groups\Tables\ProfileAttributes;
use THM\Groups\Tools\Cohesion;

class Profiles
{
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
            'tip' => 'GROUPS_TOGGLE_TIP_ACTIVATED'
        ],
        self::PENDING => [
            'class' => 'unpublish',
            'column' => 'activated',
            'task' => 'activate',
            'tip' => 'GROUPS_TOGGLE_TIP_PENDING'
        ]];

    // Display semantic is reversed
    public const blockedStates = [
        self::BLOCKED => [
            'class' => 'unpublish',
            'column' => 'block',
            'task' => 'unblock',
            'tip' => 'GROUPS_TOGGLE_TIP_BLOCKED'
        ],
        self::UNBLOCKED => [
            'class' => 'publish',
            'column' => 'block',
            'task' => 'block',
            'tip' => 'GROUPS_TOGGLE_TIP_UNBLOCKED'
        ]];

    public const contentStates = [
        self::ENABLED => [
            'class' => 'publish',
            'column' => 'contentEnabled',
            'task' => 'disableContent',
            'tip' => 'GROUPS_TOGGLE_TIP_CONTENT_ENABLED'
        ],
        self::DISABLED => [
            'class' => 'unpublish',
            'column' => 'contentEnabled',
            'task' => 'allowContent',
            'tip' => 'GROUPS_TOGGLE_TIP_CONTENT_DISABLED'
        ]];

    public const editingStates = [
        self::ENABLED => [
            'class' => 'publish',
            'column' => 'canEdit',
            'task' => 'disableEditing',
            'tip' => 'GROUPS_TOGGLE_TIP_EDITING_ENABLED'
        ],
        self::DISABLED => [
            'class' => 'unpublish',
            'column' => 'canEdit',
            'task' => 'allowEditing',
            'tip' => 'GROUPS_TOGGLE_TIP_EDITING_DISABLED'
        ]];

    public const publishedStates = [
        self::PUBLISHED => [
            'class' => 'publish',
            'column' => 'published',
            'task' => 'unpublish',
            'tip' => 'GROUPS_TOGGLE_TIP_PUBLISHED'
        ],
        self::UNPUBLISHED => [
            'class' => 'unpublish',
            'column' => 'published',
            'task' => 'publish',
            'tip' => 'GROUPS_TOGGLE_TIP_UNPUBLISHED'
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
     * Gets the profile's name.
     *
     * @param int $profileID
     *
     * @return string
     *
     */
    public static function getFirstName(int $profileID): string
    {
        $name = new ProfileAttributes();
        $name->load(['attributeID' => Attributes::FIRST_NAME, 'profileID' => $profileID]);

        return $name->value ?? '';
    }

    /**
     * Gets the profile's name.
     *
     * @param int $profileID
     *
     * @return string
     *
     */
    public static function getSurname(int $profileID): string
    {
        $name = new ProfileAttributes();
        $name->load(['attributeID' => Attributes::NAME, 'profileID' => $profileID]);

        return $name->value ?? '';
    }

    /**
     * Gets the name attributes associated with the profile.
     *
     * @param int $profileID the id of the profile
     *
     * @return array empty if no surname could be found
     */
    public static function getNames(int $profileID): array
    {
        if (!$surname = self::getSurname($profileID))
        {
            Cohesion::createBasicAttributes($profileID);
            $surname = self::getSurname($profileID);
        }

        return ['surname' => $surname, 'firstName' => self::getFirstName($profileID)];
    }
}