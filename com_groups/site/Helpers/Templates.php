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

use Joomla\CMS\Language\Text;
use THM\Groups\Adapters\Application;

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Templates implements Selectable
{
    use Named, Persistent;

    // Toggle values
    public const DEFAULT = 1, NOT = 0;

    public const CARDS = [
        self::DEFAULT => [
            'class'  => 'publish',
            'column' => 'cards',
            'task'   => '',
            'tip'    => 'GROUPS_TOGGLE_TIP_DEFAULT_CARDS_CONTEXT'
        ],
        self::NOT     => [
            'class'  => 'unpublish',
            'column' => 'cards',
            'task'   => 'defaultCard',
            'tip'    => 'GROUPS_TOGGLE_TIP_NOT_DEFAULT_CARDS_CONTEXT'
        ]
    ];

    public const ROLES = [
        self::DEFAULT => [
            'class'  => 'publish',
            'column' => 'roles',
            'task'   => 'hideRoles',
            'tip'    => 'GROUPS_TOGGLE_TIP_ROLES_SHOWN'
        ],
        self::NOT     => [
            'class'  => 'unpublish',
            'column' => 'roles',
            'task'   => 'showRoles',
            'tip'    => 'GROUPS_TOGGLE_TIP_ROLES_HIDDEN'
        ]
    ];

    public const VCARDS = [
        self::DEFAULT => [
            'class'  => 'publish',
            'column' => 'vcard',
            'task'   => '',
            'tip'    => 'GROUPS_TOGGLE_TIP_DEFAULT_VCARDS_CONTEXT'
        ],
        self::NOT     => [
            'class'  => 'unpublish',
            'column' => 'vcard',
            'task'   => 'defaultVCard',
            'tip'    => 'GROUPS_TOGGLE_TIP_NOT_DEFAULT_VCARDS_CONTEXT'
        ]
    ];

    /**
     * @inheritDoc
     *
     * @param   bool  $bound  whether the role must already be associated
     */
    public static function options(bool $associated = true): array
    {
        return [];
    }

    /** @inheritDoc */
    public static function resources(): array
    {
        return [];
    }
}