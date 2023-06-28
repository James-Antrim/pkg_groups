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

class Persons extends Entities
{
    public const
        CARDS = 3124,
        ENDED = 4,
        ENTITY = 'Person',
        ID = 66,
        IMPORTED = 2,
        MANUAL = 3,
        NAME_DE = 'Person',
        NAME_EN = 'Person',
        PLURAL_DE = 'Personen',
        PLURAL_EN = 'Persons';

    public const QUERIES = [
        'ALL' => 'data/entities/Person',
        'ONE' => 'data/entities/Person/%d',
        /**
         * No idea what this means, but smells relevant for now...
         * [L:Nie]
         * [Q:ITSUM]
         * [R:FIS-RV]
         * [X] Verknüpfung zur Karte
         * [Z:Darstellung, Verknüpfung, Auswertung von Organisationseinheiten, Statistiken]
         */
        self::CARDS => 'data/entities/Card/linked/CARD_by_PERSON/%d',
    ];

    protected const ALL_ATTRIBUTES = [
        'academicTitle',
        'cfCityTown',
        'cfFamilyNames',
        'cfFedId',
        'cfFirstNames',
        'cfGender',
        'cfPostCode',
        'cfURI',
        'email',
        'fachgebiet',
        'phone',
        'reportingName',
        'salutation',
        'streetAndNo',
        'thmLogin',
        'typeOfCreation',
        'typeOfPerson',
        'webmail'
    ];

    /**
     * Attributes to verify the person's identity.
     */
    protected const SELECTED_ATTRIBUTES = [
        'fFamilyNames',
        'cfFirstNames',
        'fachgebiet',
        'thmLogin',
        'typeOfPerson'
    ];

    protected const STATUSES = [self::ENDED, self::IMPORTED, self::MANUAL];
}