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

use THM\Groups\Adapters\{Application, Database as DB, User};

/**
 *  Constants and functions for dealing with groups from an external read context.
 */
class Templates extends Selectable
{
    use Named, Persistent;

    // Toggle values
    public const DEFAULT = 1, NOT = 0;

    public const CARDS = [
        self::DEFAULT => [
            'class'  => 'fa fa-check',
            'column' => 'cards',
            'task'   => '',
            'tip'    => 'GROUPS_TOGGLE_TIP_DEFAULT_CARDS_CONTEXT'
        ],
        self::NOT     => [
            'class'  => 'fa fa-times',
            'column' => 'cards',
            'task'   => 'defaultCard',
            'tip'    => 'GROUPS_TOGGLE_TIP_NOT_DEFAULT_CARDS_CONTEXT'
        ]
    ];

    public const ROLES = [
        self::DEFAULT => [
            'class'  => 'fa fa-check',
            'column' => 'roles',
            'task'   => 'hideRoles',
            'tip'    => 'GROUPS_TOGGLE_TIP_ROLES_PUBLISHED'
        ],
        self::NOT     => [
            'class'  => 'fa fa-times',
            'column' => 'roles',
            'task'   => 'showRoles',
            'tip'    => 'GROUPS_TOGGLE_TIP_ROLES_HIDDEN'
        ]
    ];

    public const VCARDS = [
        self::DEFAULT => [
            'class'  => 'fa fa-check',
            'column' => 'vcard',
            'task'   => '',
            'tip'    => 'GROUPS_TOGGLE_TIP_DEFAULT_VCARDS_CONTEXT'
        ],
        self::NOT     => [
            'class'  => 'fa fa-times',
            'column' => 'vcard',
            'task'   => 'defaultVCard',
            'tip'    => 'GROUPS_TOGGLE_TIP_NOT_DEFAULT_VCARDS_CONTEXT'
        ]
    ];

    /**
     * Retrieves the ids of attributes assigned to the template.
     *
     * @param   int  $templateID
     *
     * @return array
     */
    public static function attributeIDs(int $templateID): array
    {
        $query = DB::query();
        $query->select(DB::qn('attributeID'))
            ->from(DB::qn('#__groups_template_attributes', 'ta'))
            ->innerJoin(DB::qn('#__groups_attributes', 'a'), DB::qc('a.id', 'ta.attributeID'))
            ->whereIn(DB::qn('a.viewLevelID'), User::levels())
            ->where(DB::qcs([['ta.published', 1], ['a.published', 1], ['ta.templateID', $templateID]]))
            ->order(DB::qn('ta.ordering'));
        DB::set($query);

        return DB::objects('id');
    }

    /** @inheritDoc */
    public static function options(): array
    {
        $name    = 'name_' . Application::tag();
        $options = [];
        foreach (self::resources() as $templateID => $template) {

            $options[] = (object) ['text' => $template->$name, 'value' => $templateID];
        }
        return $options;
    }

    /** @inheritDoc */
    public static function resources(): array
    {
        $query = DB::query();
        $query->select('*')->from(DB::qn('#__groups_templates'))->order(DB::qn('name_' . Application::tag()));
        DB::set($query);

        return DB::objects('id');
    }
}