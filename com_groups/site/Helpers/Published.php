<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

/**
 * Trait for the publishing of content related resources.
 */
trait Published
{
    public const ARCHIVED = 2, PUBLISHED = 1, TRASHED = -2, UNPUBLISHED = 0;

    // Attributes protected because of their special role in template output
    public const STATES = [
        self::ARCHIVED,
        self::PUBLISHED,
        self::TRASHED,
        self::UNPUBLISHED
    ];
}