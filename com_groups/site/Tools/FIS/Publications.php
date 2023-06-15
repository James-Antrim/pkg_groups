<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Tools\FIS;

class Publications
{
    public const DELETED = 8, VALIDATED = 7;

    public const FILTERS = [
        self::VALIDATED => [
            'filter_de' => 'validiert',
            'filter_en' => 'Validated'
        ]
    ];
}