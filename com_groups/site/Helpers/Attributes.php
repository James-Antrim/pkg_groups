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
use THM\Groups\Adapters\Text;

class Attributes implements Selectable
{
    use Persistent;

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

    /**
     * Retrieves the ids of attributes which are of unlabeled types.
     * @return array
     */
    public static function getUnlabeled(): array
    {
        $unlabeled = [Types::IMAGE, Types::SUPPLEMENT];

        $db    = Application::database();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('id'))
            ->from($db->quoteName('#__groups_attributes'))
            ->whereIn($db->quoteName('typeID'), $unlabeled);
        $db->setQuery($query);

        return $db->loadColumn();
    }

    /**
     * @inheritDoc
     */
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
     * @inheritDoc
     */
    public static function resources(): array
    {
        $db         = Application::database();
        $query      = $db->getQuery(true);
        $label      = $db->quoteName('label_' . Application::tag());
        $attributes = $db->quoteName('#__groups_attributes');
        $query->select('*')->from($attributes)->order($label);
        $db->setQuery($query);

        if (!$attributes = $db->loadObjectList('id')) {
            return [];
        }

        foreach ($attributes as $attribute) {
            $attribute->type = Text::_(Types::TYPES[$attribute->typeID]['name']);
        }

        return $attributes;
    }
}