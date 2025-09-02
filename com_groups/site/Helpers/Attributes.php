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

use THM\Groups\Adapters\{Application, Database as DB, Input, Text};
use THM\Groups\Tables\Attributes as Table;

class Attributes extends Selectable
{
    use Persistent;

    public const HIDDEN = 0, PUBLISHED = 1;

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

    // todo create folder and migrate files
    public const IMAGE_PATH = JPATH_ROOT . '/images/com_groups/';

    public const showIconStates = [
        self::PUBLISHED => [
            'class'  => 'publish',
            'column' => 'showIcon',
            'task'   => 'hideIcon',
            'tip'    => 'TOGGLE_TIP_PUBLISHED'
        ],
        self::HIDDEN    => [
            'class'  => 'unpublish',
            'column' => 'showIcon',
            'task'   => 'showIcon',
            'tip'    => 'TOGGLE_TIP_HIDDEN'
        ]
    ];

    public const showLabelStates = [
        self::PUBLISHED => [
            'class'  => 'publish',
            'column' => 'showLabel',
            'task'   => 'hideLabel',
            'tip'    => 'TOGGLE_TIP_PUBLISHED'
        ],
        self::HIDDEN    => [
            'class'  => 'unpublish',
            'column' => 'showLabel',
            'task'   => 'showLabel',
            'tip'    => 'TOGGLE_TIP_HIDDEN'
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

    /**
     * Gets the base properties of an attribute, if a user is specified and valid, the user specific properties are
     * supplemented.
     *
     * @param   int   $attributeID
     * @param   int   $userID
     * @param   bool  $published  whether to filter to published information
     *
     * @return array
     */
    public static function raw(int $attributeID, int $userID, bool $published = true): array
    {
        $tag      = Application::tag();
        $aliased  = DB::qn(
            ['a.id', "a,label_$tag", 'a.viewLevelID', 'vl.title'],
            ['attributeID', 'label', 'properties', 'levelID', 'level']
        );
        $selected = DB::qn([
            'a.icon',
            'a.options',
            'a.showIcon',
            'a.showLabel',
            'a.typeID',
            'pa.published',
            'pa.userID',
            'pa.value'
        ]);
        $query    = DB::query();
        $query->select(array_merge($aliased, $selected))
            ->from(DB::qn('#__groups_attributes', 'a'))
            ->innerJoin(DB::qn('#__groups_profile_attributes', 'pa'), DB::qc('pa.attributeID', 'a.id'))
            ->leftJoin(DB::qn('#__viewlevels', 'vl'), DB::qc('vl.id', 'a.viewLevelID'))
            ->where(DB::qcs([['a.id', $attributeID], ['pa.userID', $userID]]));

        if ($published) {
            $query->where(DB::qc('pa.published', Input::YES));
        }

        $subQuery = DB::query();
        $subQuery->select(DB::qn('title'))->from(DB::qn('#__viewlevels', 'vl2'))->where(DB::qc('vl2.id', self::PUBLIC));
        $query->select('(' . $subQuery . ') AS ' . DB::qn('defaultLevel'));

        DB::set($query);

        if (!$attribute = DB::array()) {
            return [];
        }

        return array_merge($attribute, json_decode($attribute['options']), Types::TYPES[$attribute['typeID']]);
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