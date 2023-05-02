<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Tools;

class Cohesion
{
    /**
     * Parses the user account name to try and derive fore- and surnames from it.
     *
     * @param string $accountName the name column of the users table entry
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
}