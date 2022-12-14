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

class Attributes
{
    public const BOTH_CONTEXTS = 0, GROUPS_CONTEXT = 2, PROFILES_CONTEXT = 1;

    public const VALID_CONTEXTS = [self::BOTH_CONTEXTS, self::GROUPS_CONTEXT, self::PROFILES_CONTEXT];

    public const EMAIL = 2, FIRST_NAME = 3, IMAGE = 6, NAME = 1, SUPPLEMENT_POST = 4, SUPPLEMENT_PRE = 5;

    // Attributes protected because of their special display in various templates
    public const PROTECTED = [
        self::EMAIL,
        self::FIRST_NAME,
        self::IMAGE,
        self::NAME,
        self::SUPPLEMENT_POST,
        self::SUPPLEMENT_PRE
    ];

    // Attributes required for a valid entry
    public const REQUIRED = [
        self::EMAIL,
        self::NAME
    ];
}