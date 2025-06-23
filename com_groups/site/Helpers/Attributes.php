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

use THM\Groups\Adapters\{Application, Database as DB, Text};
use THM\Groups\Tables\Attributes as Table;

class Attributes extends Selectable
{
    use Persistent;

    public const PUBLISHED = 1, UNPUBLISHED = 0;

    public const PUBLIC = 1;

    public const BOTH_CONTEXTS = 0, GROUPS_CONTEXT = 2, PERSONS_CONTEXT = 1;

    public const CONTEXTS = [self::BOTH_CONTEXTS, self::GROUPS_CONTEXT, self::PERSONS_CONTEXT];

    public const BANNER = 4, IMAGE = 3, SUPPLEMENT_POST = 1, SUPPLEMENT_PRE = 2;

    // Attributes protected because of their special role in template output
    public const PROTECTED = [
        self::BANNER,
        self::IMAGE,
        self::SUPPLEMENT_POST,
        self::SUPPLEMENT_PRE
    ];

    public const SHOWN = 1, HIDDEN = 0;

    public const showIconStates = [
        self::SHOWN  => [
            'class'  => 'publish',
            'column' => 'showIcon',
            'task'   => 'hideIcon',
            'tip'    => 'GROUPS_TOGGLE_TIP_SHOWN'
        ],
        self::HIDDEN => [
            'class'  => 'unpublish',
            'column' => 'showIcon',
            'task'   => 'showIcon',
            'tip'    => 'GROUPS_TOGGLE_TIP_HIDDEN'
        ]
    ];

    public const showLabelStates = [
        self::SHOWN  => [
            'class'  => 'publish',
            'column' => 'showLabel',
            'task'   => 'hideLabel',
            'tip'    => 'GROUPS_TOGGLE_TIP_SHOWN'
        ],
        self::HIDDEN => [
            'class'  => 'unpublish',
            'column' => 'showLabel',
            'task'   => 'showLabel',
            'tip'    => 'GROUPS_TOGGLE_TIP_HIDDEN'
        ]
    ];

    /** @inheritDoc */
    public static function options(): array
    {
        $label   = 'label_' . Application::tag();
        $options = [];

        foreach (self::resources() as $attributeID => $attribute) {

            $options[] = (object) [
                'text'  => $attribute->$label,
                'value' => $attributeID
            ];
        }

        return $options;
    }

    /**
     * Retrieve the parameters for the specified attribute
     *
     * @param   int  $attributeID
     *
     * @return array
     * @todo rename the database field appropriately
     */
    public static function parameters(int $attributeID): array
    {
        $attribute = new Table();

        return $attribute->load($attributeID) ? json_decode($attribute->options, true) : [];
    }

    /** @inheritDoc */
    public static function resources(): array
    {
        $query = DB::query();
        $query->select('*')->from(DB::qn('#__groups_attributes'))->order(DB::qn('label_' . Application::tag()));
        DB::set($query);

        foreach ($attributes = DB::objects('id') as $attribute) {
            $attribute->type = Text::_(Types::TYPES[$attribute->typeID]['name']);
        }

        return $attributes;
    }

    /**
     * Retrieves the ids of attributes which are of unlabeled types.
     * @return array
     */
    public static function unlabeledIDs(): array
    {
        $query = DB::query();
        $query->select(DB::qn('id'))
            ->from(DB::qn('#__groups_attributes'))
            ->whereIn(DB::qn('typeID'), [Types::IMAGE, Types::SUPPLEMENT]);
        DB::set($query);

        return DB::integers();
    }
}