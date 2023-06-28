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

/**
 * [X] The card describes the relation between a person and an internal organisation. It includes the contact
 * dates of the person in the organisation.
 */
class Cards extends Entities
{
    public const
        ENTITY = 'Card',
        ID = 1938,
        NAME_DE = 'Visitenkarte',
        NAME_EN = 'Business Card',
        ORGANIZATIONS = 3101,
        PLURAL_DE = 'Visitenkarten',
        PLURAL_EN = 'Business Cards',
        ROLES = 2208588;

    public const QUERIES = [
        'ALL' => 'data/entities/Card',
        'ONE' => 'data/entities/Card/%d',
        self::ORGANIZATIONS => 'data/entities/Card/linked/CARD_has_ORGA/%d',
        self::ROLES => 'data/entities/Card/linked/CARD_has_FUNC/%d'
    ];

    protected const ALL_ATTRIBUTES = [
        'academicTitle',
        'cfEndDate',
        'cfStartDate',
        'cfURI',
        'email',
        'fedId',
        'firstName',
        'function',
        'lastName',
        'phone',
        'reportingName',
        'staffCategory',
        'typeOfCard',
        'typeOfCreation',
        'webmail'
    ];

    /**
     * Attributes to related to the person in an organizational context.
     */
    protected const SELECTED_ATTRIBUTES = [
        'fedId',
        'function',
        'reportingName',
        'staffCategory',
        'typeOfCard'
    ];
}