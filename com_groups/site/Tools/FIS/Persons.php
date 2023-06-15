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

class Persons
{
    public const CITIZEN = 1, RESIDENT = 2, NATIVE_SPEAKER = 3, SPEAKS = 4;

    public const FILTERS = [
        self::CITIZEN => [
            'filter_de' => 'Staatsbürger',
            'filter_en' => 'citizen',
        ],
        self::RESIDENT => [
            'filter_de' => 'wohnhaft',
            'filter_en' => 'resident',
        ],
        self::NATIVE_SPEAKER => [
            'filter_de' => 'Muttersprachler',
            'filter_en' => 'native speaker',
        ],
        self::SPEAKS => [
            'filter_de' => 'spricht',
            'filter_en' => 'speaks',
        ],
    ];
}