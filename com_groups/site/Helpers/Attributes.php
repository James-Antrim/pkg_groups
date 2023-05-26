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
    public const BOTH_CONTEXTS = 0, GROUPS_CONTEXT = 2, PERSONS_CONTEXT = 1;

    public const CONTEXTS = [self::BOTH_CONTEXTS, self::GROUPS_CONTEXT, self::PERSONS_CONTEXT];

    public const IMAGE = 3, SUPPLEMENT_POST = 1, SUPPLEMENT_PRE = 2;

    // Attributes protected because of their special display in various templates
    public const PROTECTED = [
        self::IMAGE,
        self::SUPPLEMENT_POST,
        self::SUPPLEMENT_PRE
    ];

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