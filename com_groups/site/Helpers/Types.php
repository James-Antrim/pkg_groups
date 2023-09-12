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

/**
 * @todo Add type conversion routines for migration of values between attributes of differing types.
 */
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
            'icon' => 'location',
            'input' => 'GROUPS_FORM_TEMPLATE',
            'name' => 'GROUPS_ADDRESS',
            'output' => 'GROUPS_FORMATTED_TEXT'
        ],
        self::BUTTON => [
            'icon' => '',
            'input' => 'GROUPS_URL_FIELD',
            'name' => 'GROUPS_MEDIA_BUTTON',
            'output' => 'GROUPS_MEDIA_BUTTON'
        ],
        self::DATE => [
            'icon' => 'calendar',
            'input' => 'GROUPS_DATE_FIELD',
            'name' => 'GROUPS_DATE',
            'output' => 'GROUPS_DATE'
        ],
        self::EMAIL => [
            'hint' => 'maxine.mustermann@fb.thm.de',
            'icon' => 'envelope',
            'input' => 'GROUPS_EMAIL_FIELD',
            'name' => 'GROUPS_EMAIL',
            'output' => 'GROUPS_LINKED_EMAIL'
        ],
        self::HOURS => [
            'icon' => 'comment',
            'input' => 'GROUPS_FORM_TEMPLATE',
            'name' => 'GROUPS_HOURS_TYPE',
            'output' => 'GROUPS_FORMATTED_TEXT'
        ],
        self::HTML => [
            'buttons' => 0,
            'icon' => '',
            'input' => 'GROUPS_EDITOR_FIELD',
            'name' => 'GROUPS_HTML_TYPE',
            'output' => 'GROUPS_HTML_TYPE'
        ],
        self::IMAGE => [
            'icon' => '',
            'input' => 'GROUPS_IMAGE_FIELD',
            'name' => 'GROUPS_IMAGE',
            'output' => 'GROUPS_IMAGE'
        ],
        self::LIST => [
            'icon' => '',
            'input' => 'GROUPS_LIST_FORM',
            'name' => 'GROUPS_LINK_LIST',
            'output' => 'GROUPS_LINK_LIST'
        ],
        self::PHONE => [
            'icon' => 'phone',
            'input' => 'GROUPS_PHONE_FIELD',
            'name' => 'GROUPS_PHONE_NUMBER',
            'hint' => 'TODO',
            'pattern' => '^(\+[\d]+ ?)?( ?((\(0?[\d]*\))|(0?[\d]+(\/| \/)?)))?(([ \-]|[\d]+)+)$',
            'output' => 'GROUPS_LINKED_TELEPHONE'
        ],
        self::SUPPLEMENT => [
            'icon' => '',
            'hint' => '',
            'input' => 'GROUPS_TEXT_FIELD',
            'name' => 'GROUPS_SUPPLEMENT',

            /**
             * DE: Der Namenszusatz/akademische Grad ist ungültig. Namenszusätze dürfen nur aus Buchstaben, Leerzeichen, Kommata, Punkte, Runde Klammer, Minus Zeichen und &dagger; bestehen.
             * EN: The name supplement / title is invalid. Name supplements may only consist of letters, spaces, commas, periods, round braces, minus signs and &dagger;.
             */

            'message' => 'GROUPS_SUPPLEMENT_MESSAGE',
            'output' => 'GROUPS_SUPPLEMENT',
            'pattern' => '^[A-ZÀ-ÖØ-Þa-zß-ÿ ,.\\\\-()†]+$'
        ],
        self::TEXT => [
            'icon' => '',
            'input' => 'GROUPS_FORM_TEMPLATE',
            'name' => 'GROUPS_LINK_TEXT',
            'output' => 'GROUPS_LINK_TEXT'
        ]
    ];

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