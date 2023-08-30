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

use THM\Groups\Adapters\Text;

class Types implements Selectable
{
    /**
     * Types determines the data management of attribute values.
     */
    public const
        ADDRESS = 1,
        BUTTON = 2,
        DATE = 3,
        EMAIL = 4,
        HOURS = 5,
        HTML = 6,
        IMAGE = 7,
        TEXT = 8,
        LIST = 9,
        PHONE = 10,
        SUPPLEMENT = 11;

    public const TYPES = [
        self::ADDRESS => [
            'fields' => [
                //SF: 1, 2, ZIP, CITY & placement check (before or after)
                'value' => 'Address'
            ],
            'icon' => 'location',
            'input' => 'GROUPS_FORM_TEMPLATE',
            'name' => 'GROUPS_ADDRESS'
        ],
        self::BUTTON => [
            'fields' => [
                'text_de' => 'Text',
                'text_en' => 'Text',
                'value' => 'URL'
            ],
            'icon' => '',
            'input' => 'GROUPS_FORM_TEMPLATE',
            'name' => 'GROUPS_MEDIA_BUTTON'
        ],
        self::DATE => [
            'fields' => [
                'value' => 'Date'
            ],
            'icon' => 'calendar',
            'input' => 'GROUPS_DATE_FIELD',
            'name' => 'GROUPS_DATE'
        ],
        self::EMAIL => [
            'fields' => [
                'value' => 'EMail'
            ],
            'hint' => 'maxine.mustermann@fb.thm.de',
            'icon' => 'envelope',
            'input' => 'GROUPS_EMAIL_FIELD',
            'name' => 'GROUPS_EMAIL'
        ],
        self::HOURS => [
            'fields' => [
                //SF: weekdays, times + checkbox for by appointment
                'value' => 'Hours'
            ],
            'icon' => 'comment',
            'input' => 'GROUPS_FORM_TEMPLATE',
            'name' => 'GROUPS_HOURS_TYPE'
        ],
        self::HTML => [
            'buttons' => 0,
            'fields' => [
                'buttons' => 'Toggle',
                //showon yes
                'hide' => 'ButtonSelect',
                'value' => 'Editor'
            ],
            'icon' => '',
            'input' => 'GROUPS_EDITOR_FIELD',
            'name' => 'GROUPS_HTML_TYPE'
        ],
        self::IMAGE => [
            'fields' => [
                'source_de' => 'Text',
                'source_en' => 'Text',
                'source_url' => 'URL',
                'value' => 'Image'
            ],
            'icon' => '',
            'input' => 'GROUPS_IMAGE_FIELD',
            'name' => 'GROUPS_IMAGE'
        ],
        self::LIST => [
            'fields' => [
                //SF: Linked Text
                'value' => 'LinkedList'
            ],
            'icon' => '',
            'input' => 'GROUPS_LIST_FORM',
            'name' => 'GROUPS_LINK_LIST'
        ],
        self::PHONE => [
            'fields' => [
                'value' => 'Phone'
            ],
            'icon' => 'phone',
            'input' => 'GROUPS_PHONE_FIELD',
            'name' => 'GROUPS_PHONE_NUMBER',
            'hint' => 'TODO',
            'pattern' => '^(\+[\d]+ ?)?( ?((\(0?[\d]*\))|(0?[\d]+(\/| \/)?)))?(([ \-]|[\d]+)+)$'
        ],
        self::SUPPLEMENT => [
            'fields' => [
                'value' => 'Text'
            ],
            'icon' => '',
            'hint' => '',
            'input' => 'GROUPS_TEXT_FIELD',
            'name' => 'GROUPS_SUPPLEMENT',

            /**
             * DE: Der Namenszusatz/akademische Grad ist ungültig. Namenszusätze dürfen nur aus Buchstaben, Leerzeichen, Kommata, Punkte, Runde Klammer, Minus Zeichen und &dagger; bestehen.
             * EN: The name supplement / title is invalid. Name supplements may only consist of letters, spaces, commas, periods, round braces, minus signs and &dagger;.
             */

            'message' => 'GROUPS_SUPPLEMENT_MESSAGE',
            'pattern' => '^[A-ZÀ-ÖØ-Þa-zß-ÿ ,.\\\\-()†]+$'
        ],
        self::TEXT => [
            'fields' => [
                'text_de' => 'Text',
                'text_en' => 'Text',
                'value' => 'URL'
            ],
            'icon' => '',
            'input' => 'GROUPS_FORM_TEMPLATE',
            'name' => 'GROUPS_LINK_TEXT'
        ]
    ];

    //TODO add type conversion for compatible types

    public const INLINE = [self::SUPPLEMENT];
    public const FULL_WIDTH = [self::HTML];

    /**
     * URL
     * BUTTON
     * ROOM
     * LIST
     * URL LIST
     * NESTED LIST
     */

    /**
     * @inheritDoc
     */
    public static function getAll(): array
    {
        $return = [];

        foreach (self::TYPES as $typeID => $type) {

            $type = (object) $type;
            $name = Text::_($type->name);

            $type->id   = $typeID;
            $type->name = $name;

            $return[] = $type;
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public static function getOptions(): array
    {
        $options = [];

        foreach (self::getAll() as $type) {
            $options[$type->name] = (object) [
                'text' => $type->name,
                'value' => $type->id
            ];
        }

        ksort($options);

        return $options;
    }
}