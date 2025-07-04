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

use THM\Groups\Tables\ProfileAttributes as Table;

class Profiles
{
    public const CENTRALIZED = 0, DECENTRALIZED = 1, DISABLED = 0, ENABLED = 1;

    /**
     * Creates a last name first styled name based on user attributes and optionally title attributes.
     *
     * @param   int   $userID      the id of the profile user
     * @param   bool  $withTitles  whether to include titles as part of the result set
     * @param   bool  $withSpan    whether to surround the data with spans with a corresponding css class
     *
     * @return string
     */
    public static function lnfName(int $userID, bool $withTitles = false, bool $withSpan = false): string
    {
        $results = self::namesAndTitles($userID, $withTitles, $withSpan);

        $result = empty($results['forenames']) ? $results['surnames'] : "{$results['surnames']}, {$results['forenames']}";

        if ($withTitles) {
            $result = empty($results['pre']) ? $result : $result . " {$results['pre']}";
            $result = empty($results['post']) ? $result : $result . " {$results['post']}";
        }

        return $result;
    }

    /**
     * Creates a name based on user attributes and optionally title attributes.
     *
     * @param   int   $userID      the id of the profile user
     * @param   bool  $withTitles  whether to include titles as part of the result set
     * @param   bool  $withSpan    whether to surround the data with spans with a corresponding css class
     *
     * @return string
     */
    public static function name(int $userID, bool $withTitles = false, bool $withSpan = false): string
    {
        $results = self::namesAndTitles($userID, $withTitles, $withSpan);
        $result  = empty($results['forenames']) ? $results['surnames'] : "{$results['forenames']} {$results['surnames']}";

        if ($withTitles) {
            $result = empty($results['pre']) ? $result : "{$results['pre']} $result";
            $result = empty($results['post']) ? $result : "$result, {$results['post']} ";
        }

        return $result;
    }

    /**
     * Retrieves username information as an array, optionally with titles and or spans with css classes.
     *
     * @param   int   $userID      the id of the profile user
     * @param   bool  $withTitles  whether to include titles as part of the result set
     * @param   bool  $withSpan    whether to surround the data with spans with a corresponding css class
     *
     * @return array
     */
    public static function namesAndTitles(int $userID, bool $withTitles = false, bool $withSpan = false): array
    {
        $results = [
            'forenames' => Users::forenames($userID),
            'surnames'  => Users::surnames($userID)
        ];

        if ($withTitles) {
            $results['post'] = self::titles($userID, Attributes::SUPPLEMENT_POST);
            $results['pre']  = self::titles($userID, Attributes::SUPPLEMENT_PRE);

            // Special handling for deceased
            if (str_contains($results['post'], '†')) {
                $results['surnames'] .= ' †';
                $results['post']     = trim(str_replace('†', '', $results['post']));
            }
        }

        if ($withSpan) {
            $results['surnames']  = empty($results['surnames']) ? '' : '<span class="name-value">' . $results['surnames'] . '</span>';
            $results['forenames'] = empty($results['forenames']) ? '' : '<span class="name-value">' . $results['forenames'] . '</span>';

            if ($withTitles) {
                $results['pre']  = empty($results['pre']) ? '' : '<span class="title-value">' . $results['pre'] . '</span>';
                $results['post'] = empty($results['post']) ? '' : '<span class="title-value">' . $results['post'] . '</span>';
            }
        }

        return $results;
    }

    /**
     * Retrieves the profile attribute value with the given parameters.
     *
     * @param   int  $userID       the id of the user resource
     * @param   int  $attributeID  the id of the attribute resource
     *
     * @return string
     */
    public static function titles(int $userID, int $attributeID): string
    {
        $table = new Table();
        if ($table->load(['attributeID' => $attributeID, 'published' => Attributes::PUBLISHED, 'userID' => $userID])) {
            return $table->value;
        }

        return '';
    }
}