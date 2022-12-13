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

use THM\Groups\Adapters\Application;
use THM\Groups\Tables\ProfileAttributes;
use THM\Groups\Tools\Cohesion;

class Profiles
{
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
    public static function getNames($profileID): array
    {
        if (!$surname = self::getSurname($profileID))
        {
            Cohesion:createBasicAttributes($profileID);
            $surname = self::getSurname($profileID);
        }

        return ['surname' => $surname, 'firstName' => self::getFirstName($profileID)];
    }
}