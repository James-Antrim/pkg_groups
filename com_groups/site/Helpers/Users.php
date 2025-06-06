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

use THM\Groups\Adapters\{Application, Database as DB, Input, Text, User as Account};
use THM\Groups\Tables\{Categories, Users as Table};

/**
 * Accessor class for user and profile data.
 */
class Users
{
    use Persistent;
    use Published;

    public const ACTIVATED = true, PENDING = false;
    public const ENABLED = 1, DISABLED = 0;
    public const BLOCKED = 1, UNBLOCKED = 0;

    // Pending state comes into effect through user interaction with the login component.
    public const activatedStates = [
        self::ACTIVATED => [
            'class'  => 'publish',
            'column' => 'activated',
            'task'   => '',
            'tip'    => 'GROUPS_TOGGLE_TIP_USER_ACTIVATED'
        ],
        self::PENDING   => [
            'class'  => 'unpublish',
            'column' => 'activated',
            'task'   => 'activate',
            'tip'    => 'GROUPS_TOGGLE_TIP_USER_PENDING'
        ]
    ];

    // Display semantic is reversed
    public const blockedStates = [
        self::BLOCKED   => [
            'class'  => 'unpublish',
            'column' => 'block',
            'task'   => 'unblock',
            'tip'    => 'GROUPS_TOGGLE_TIP_USER_BLOCKED'
        ],
        self::UNBLOCKED => [
            'class'  => 'publish',
            'column' => 'block',
            'task'   => 'block',
            'tip'    => 'GROUPS_TOGGLE_TIP_USER_UNBLOCKED'
        ]
    ];

    // Attributes protected because of their special display in various templates
    /*public const PROTECTED = [
        self::EMAIL,
        self::IMAGE,
        self::SUPPLEMENT_POST,
        self::SUPPLEMENT_PRE
    ];*/

    public const contentStates = [
        self::ENABLED  => [
            'class'  => 'publish',
            'column' => 'content',
            'task'   => 'disableContent',
            'tip'    => 'GROUPS_TOGGLE_TIP_CONTENTS_ENABLED'
        ],
        self::DISABLED => [
            'class'  => 'unpublish',
            'column' => 'content',
            'task'   => 'enableContent',
            'tip'    => 'GROUPS_TOGGLE_TIP_CONTENTS_DISABLED'
        ]
    ];

    public const editingStates = [
        self::ENABLED  => [
            'class'  => 'publish',
            'column' => 'editing',
            'task'   => 'disableEditing',
            'tip'    => 'GROUPS_TOGGLE_TIP_EDITING_ENABLED'
        ],
        self::DISABLED => [
            'class'  => 'unpublish',
            'column' => 'editing',
            'task'   => 'enableEditing',
            'tip'    => 'GROUPS_TOGGLE_TIP_EDITING_DISABLED'
        ]
    ];

    public const publishedStates = [
        self::PUBLISHED   => [
            'class'  => 'publish',
            'column' => 'published',
            'task'   => 'unpublish',
            'tip'    => 'GROUPS_TOGGLE_TIP_PROFILE_PUBLISHED'
        ],
        self::UNPUBLISHED => [
            'class'  => 'unpublish',
            'column' => 'published',
            'task'   => 'publish',
            'tip'    => 'GROUPS_TOGGLE_TIP_PROFILE_UNPUBLISHED'
        ]
    ];

    /**
     * Returns the user's alias.
     *
     * @param   int  $userID
     *
     * @return string
     */
    public static function alias(int $userID): string
    {
        return (string) self::get($userID, 'alias');
    }

    /**
     * Returns the id of the category associated with the user.
     *
     * @param   int  $userID
     *
     * @return int
     */
    public static function categoryID(int $userID): int
    {
        $table = new Categories();
        if ($table->load(['userID' => $userID])) {
            return $table->categoryID;
        }

        return 0;
    }

    /**
     * Returns the user's content status.
     *
     * @param   int  $userID
     *
     * @return bool
     */
    public static function content(int $userID): bool
    {
        if (!$accountID = Account::id()) {
            return false;
        }

        if (Can::manage('com_content')) {
            return true;
        }

        // Global restriction or content to be edited does not belong to the user.
        if (!Input::getParams()->get('content') or $accountID != $userID) {
            return false;
        }

        return (bool) self::get($userID, 'content');
    }

    /**
     * Creates an alias based on the user's fore- and surnames.
     *
     * @param   int     $userID  the user's id
     * @param   string  $names   the user's names
     *
     * @return string
     */
    public static function createAlias(int $userID, string $names): string
    {
        $alias = Text::trim($names);
        $alias = Text::transliterate($alias);
        $alias = Text::filter($alias);
        $alias = str_replace(' ', '-', $alias);

        $db    = Application::database();
        $id    = $db->quoteName('id');
        $query = $db->getQuery(true);
        $query->select($id)
            ->from($db->quoteName('#__users'))
            ->where("$id != $userID")
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
     * Gets the value of a table property.
     *
     * @param   int     $personID
     * @param   string  $property
     *
     * @return mixed
     */
    public static function get(int $personID, string $property): mixed
    {
        /** @var Table $table */
        $table = self::getTable();
        if (!$table->load($personID)) {
            return null;
        }

        // Forenames are allowed to be empty
        if (in_array($property, ['alias', 'surnames']) and empty($table->$property)) {

            if (empty($table->username)) {
                Application::error(500);
            }

            [$table->surnames, $table->forenames] = self::parseNames($table->name);
            $table->alias = self::createAlias($table->id, "$table->forenames $table->surnames");
            $table->store();
        }

        if (property_exists($table, $property)) {
            return $table->$property;
        }

        return null;
    }

    /**
     * Returns the user's editing status.
     *
     * @param   int  $userID
     *
     * @return bool
     */
    public static function editing(int $userID): bool
    {
        if (!$accountID = Account::id()) {
            return false;
        }

        if (Can::manage()) {
            return true;
        }

        // Global restriction or account to be edited does not belong to the user.
        if (!Input::getParams()->get('profile-management') or $accountID != $userID) {
            return false;
        }

        // User specific restriction
        return (bool) self::get($userID, 'editing');
    }

    /**
     * Returns the user's forenames.
     *
     * @param   int  $userID
     *
     * @return string
     */
    public static function forenames(int $userID): string
    {
        return (string) self::get($userID, 'forenames');
    }

    /**
     * Returns the id of the user assigned the given alias.
     *
     * @param   string  $alias
     *
     * @return int
     */
    public static function idByAlias(string $alias): int
    {
        /** @var Table $table */
        $table = self::getTable();

        if ($table->load(['alias' => $alias])) {
            return $table->id;
        }

        return self::idByNames($alias);
    }

    /**
     * Returns the id of the user approximated by the given names. If multiple users approximate the terms an 'alias' is returned
     * for disambiguation generation.
     *
     * @param   string  $qParts
     *
     * @return int|string
     */
    public static function idByNames(string $qParts): int|string
    {
        $query = DB::query();
        $query->select(DB::qn('alias'))
            ->from(DB::qn('#__users'))
            ->where(DB::qcs([['published', 1], ['alias', '', '!=', true]]))
            ->where(DB::qn('alias') . ' IS NOT NULL');
        DB::set($query);

        if (!$users = DB::arrays()) {
            return 0;
        }

        $qParts = Text::transliterate($qParts);
        $qParts = Text::filter($qParts);
        $qParts = explode(' ', $qParts);

        $userIDs = [];
        foreach ($users as $user) {
            $found  = true;
            $aParts = explode('-', $user['alias']);
            foreach ($qParts as $qPart) {
                $found = ($found and in_array($qPart, $aParts));
            }
            if ($found) {
                $userIDs[] = $user['id'];
            }
        }

        if (empty($userIDs)) {
            return 0;
        }

        return count($userIDs) > 1 ? implode('-', $qParts) : reset($userIDs);
    }

    /**
     * Parses the user account name to try and derive fore- and surnames from it.
     *
     * @param   string  $accountName  the name column of the users table entry
     *
     * @return array [surnames, forenames]
     */
    public static function parseNames(string $accountName): array
    {
        // Replace non-alphabetical characters
        $name = preg_replace('/[^A-ZÀ-ÖØ-Þa-zß-ÿ\p{N}_.\-\']/', ' ', $accountName);

        // Replace superfluous whitespace
        $name = preg_replace('/ +/', ' ', $name);
        $name = trim($name);

        $fragments = array_filter(explode(" ", $name));

        $surnames = array_pop($fragments);

        // Resolve any supplemental prefix to the surnames
        $prefix = '';

        // The next fragment consists solely of lower case letters indicating a preposition
        while (preg_match('/^[a-zß-ÿ]+$/', end($fragments))) {
            $prefix = array_pop($fragments);

            // Prepend positive results
            $surnames = "$prefix $surnames";
        }

        // These prepositions indicate that the previous fragments were a locality and a further surname exists
        if (in_array($prefix, ['zu', 'zum'])) {
            $surnames = array_pop($fragments) . " $surnames";

            // Check for further prepositions
            while (preg_match('/^[a-zß-ÿ]+$/', end($fragments))) {
                $surnames = array_pop($fragments) . " $surnames";
            }
        }

        // Anything left is evaluated as collection of forenames
        $forenames = $fragments ? implode(" ", $fragments) : '';

        return [$surnames, $forenames];
    }

    /**
     * Returns the user's published status.
     *
     * @param   int  $userID
     *
     * @return bool
     */
    public static function published(int $userID): bool
    {
        return (bool) self::get($userID, 'published');
    }

    /**
     * Returns the user's surnames.
     *
     * @param   int  $userID
     *
     * @return string
     */
    public static function surnames(int $userID): string
    {
        return (string) self::get($userID, 'forenames');
    }
}