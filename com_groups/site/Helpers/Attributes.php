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

class Attributes
{
    public const BOTH_CONTEXTS = 0, GROUPS_CONTEXT = 2, PERSONS_CONTEXT = 1;

    public const CONTEXTS = [self::BOTH_CONTEXTS, self::GROUPS_CONTEXT, self::PERSONS_CONTEXT];

    public const IMAGE = 3, SUPPLEMENT_POST = 1, SUPPLEMENT_PRE = 2;

    // Attributes protected because of their special display in various templates
    public const PROTECTED = [
        self::SUPPLEMENT_POST,
        self::SUPPLEMENT_PRE
    ];

    // Attributes whose labelling is always suppressed
    public const UNLABELED = [
        self::IMAGE,
        self::SUPPLEMENT_POST,
        self::SUPPLEMENT_PRE
    ];

    public const SHOWN = 1, HIDDEN = 0;

    public const showIconStates = [
        self::SHOWN => [
            'class' => 'publish',
            'column' => 'showIcon',
            'task' => 'hideIcon',
            'tip' => 'GROUPS_TOGGLE_TIP_SHOWN'
        ],
        self::HIDDEN => [
            'class' => 'unpublish',
            'column' => 'showIcon',
            'task' => 'showIcon',
            'tip' => 'GROUPS_TOGGLE_TIP_HIDDEN'
        ]];

    public const showLabelStates = [
        self::SHOWN => [
            'class' => 'publish',
            'column' => 'showLabel',
            'task' => 'hideLabel',
            'tip' => 'GROUPS_TOGGLE_TIP_SHOWN'
        ],
        self::HIDDEN => [
            'class' => 'unpublish',
            'column' => 'showLabel',
            'task' => 'showLabel',
            'tip' => 'GROUPS_TOGGLE_TIP_HIDDEN'
        ]];

    /**
     * Retrieves the ids of attributes which are of unlabeled types.
     * @return array
     */
    public static function getUnlabeled(): array
    {
        $unlabeled = [Types::IMAGE, Types::SUPPLEMENT];

        $db    = Application::getDB();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('id'))
            ->from($db->quoteName('#__groups_attributes'))
            ->whereIn($db->quoteName('typeID'), $unlabeled);
        $db->setQuery($query);

        return $db->loadColumn();
    }

    /**
     * Name attributes
     * message_de: Namen dürfen nur aus Buchstaben und einzelne Apostrophen, Leer- und Minuszeichen und Punkten bestehen.
     * message_en: Names may only consist of letters and singular apostrophes, hyphens, periods, and spaces.
     * pattern: ^([a-zß-ÿ]+ )*([a-zß-ÿ]+\'\')?[A-ZÀ-ÖØ-Þ](\\\\.|[a-zß-ÿ]+)([ |-]([a-zß-ÿ]+ )?([a-zß-ÿ]+\'\')?[A-ZÀ-ÖØ-Þ](\\\\.|[a-zß-ÿ]+))*$
     */

    /**
     * Aktuell,
     * Weitere Informationen,
     * Zur Person => HTML (Label & Full width output) => Editor, VL/PUB
     *
     * Email
     */
}