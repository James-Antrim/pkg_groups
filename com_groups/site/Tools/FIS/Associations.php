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

use THM\Groups\Adapters\Application;

class Associations
{
    public const DEFAULT = -1;

    public const TARGETS = [

        Entities::AREAS => [
            Entities::AREAS => [
                'byChild' => [
                    'id' => 3009,
                    'name' => 'AREA_has_child_AREA',
                ],
                'byParent' => [
                    'id' => 3009,
                    'name' => 'AREA_has_child_AREA',
                ],
            ],
            Entities::FILES => [
                self::DEFAULT => [
                    'id' => 3331,
                    'name' => 'AREA_has_FILE',
                    'query' => 'data/entities/Area/linked/AREA_has_FILE/%d'
                ]
            ],
            Entities::PICTURES => [
                self::DEFAULT => [
                    'id' => 3354,
                    'name' => 'AREA_has_PICT',
                    'query' => 'data/entities/Area/linked/AREA_has_PICT/%d'
                ]
            ],
        ],

        Entities::CARDS => [
            Entities::ROLES => [
                self::DEFAULT => [
                    'id' => 2208588,
                    'name' => 'CARD_has_FUNC',
                    'query' => 'data/entities/Card/linked/CARD_has_FUNC/%d'
                ],
            ],
            Entities::ORGANIZATIONS => [
                self::DEFAULT => [
                    'id' => 3101,
                    'name' => 'CARD_has_ORGA',
                    'query' => 'data/entities/Card/linked/CARD_has_ORGA/%d'
                ],
            ],
        ],

        Entities::ORGANIZATIONS => [],

        Entities::PERSONS => [
            Entities::AREAS => [
                self::DEFAULT => [
                    'id' => 3377,
                    'name' => 'PERS_has_AREA',
                    'query' => 'data/entities/Person/linked/PERS_has_AREA/%d'
                ]
            ],
            /**
             * No idea what this means, but smells relevant for now...
             * [L:Nie]
             * [Q:ITSUM]
             * [R:FIS-RV]
             * [X] Verknüpfung zur Karte
             * [Z:Darstellung, Verknüpfung, Auswertung von Organisationseinheiten, Statistiken]
             */
            Entities::CARDS => [
                self::DEFAULT => [
                    'id' => 3124,
                    'name' => 'PERS_has_CARD',
                    'query' => 'data/entities/Person/linked/PERS_has_CARD/%d'
                ]
            ],
            Entities::CENTERS => [
                self::DEFAULT => [
                    'id' => 15084,
                    'name' => 'PERS_has_CENT',
                    'query' => 'data/entities/Person/linked/PERS_has_CENT/%d'
                ]
            ],
            Entities::COUNTRIES => [
                self::DEFAULT => [
                    'id' => 3699,
                    'name' => 'PERS_has_nation_COUN',
                    'query' => 'data/entities/Person/linked/PERS_has_nation_COUN/%d'
                ],
                Persons::CITIZEN => [
                    'id' => 3699,
                    'name' => 'PERS_has_nation_COUN',
                    'query' => 'data/entities/Person/linked/PERS_has_nation_COUN/%d'
                ],
                Persons::RESIDENT => [
                    'id' => 3722,
                    'name' => 'PERS_has_resid_COUN',
                    'query' => 'data/entities/Person/linked/PERS_has_resid_COUN/%d'
                ],
            ],
            Entities::DFG_FIELDS => [
                self::DEFAULT => [
                    'id' => 12186,
                    'name' => 'PERS_has_DFGA',
                    'query' => 'data/entities/Person/linked/PERS_has_DFGA/%d'
                ]
            ],
            Entities::EMPLOYMENTS => [
                self::DEFAULT => [
                    'id' => 11956,
                    'name' => 'PERS_has_EMPL',
                    'query' => 'data/entities/Person/linked/PERS_has_EMPL/%d'
                ]
            ],
            Entities::HABILITATION => [
                self::DEFAULT => [
                    'id' => 12025,
                    'name' => 'PERS_has_HABI',
                    'query' => 'data/entities/Person/linked/PERS_has_HABI/%d'
                ]
            ],
            Entities::HFD_AREAS => [
                self::DEFAULT => [
                    'id' => 14900,
                    'name' => 'PERS_has_FOSP',
                    'query' => 'data/entities/Person/linked/PERS_has_FOSP/%d'
                ]
            ],
            // [X] Forschungsfeld der Person
            Entities::KDSF_FIELDS => [
                self::DEFAULT => [
                    'id' => 2208429,
                    'name' => 'PERS_has_FOFE',
                    'query' => 'data/entities/Person/linked/PERS_has_FOFE/%d'
                ]
            ],
            Entities::LANGUAGE_COMPETENCES => [
                self::DEFAULT => [
                    'id' => 6597,
                    'name' => 'PERS_has_LASK',
                    'query' => 'data/entities/Person/linked/PERS_has_LASK/%d'
                ]
            ],
            Entities::LANGUAGES => [
                self::DEFAULT => [
                    'id' => 6620,
                    'name' => 'PERS_has_LANG',
                    'query' => 'data/entities/Person/linked/PERS_has_LANG/%d'
                ],
                Persons::NATIVE_SPEAKER => [
                    'id' => 6551,
                    'name' => 'PERS_has_moth_LANG',
                    'query' => 'data/entities/Person/linked/PERS_has_moth_LANG/%d'
                ],
                Persons::SPEAKS => [
                    'id' => 6620,
                    'name' => 'PERS_has_LANG',
                    'query' => 'data/entities/Person/linked/PERS_has_LANG/%d'
                ],
            ],
            Entities::ORGANIZATIONS => [
                self::DEFAULT => [
                    'id' => 3262,
                    'name' => 'PERS_has_ORGA',
                    'query' => 'data/entities/Person/linked/PERS_has_ORGA/%d'
                ]
            ],
            Entities::PARTICIPATION => [
                self::DEFAULT => [
                    'id' => 6367,
                    'name' => 'PERS_has_PART',
                    'query' => 'data/entities/Person/linked/PERS_has_PART/%d'
                ]
            ],
            // [X] BIld des Nutzers  [Z:Darstellung]
            Entities::PICTURES => [
                self::DEFAULT => [
                    'id' => 3308,
                    'name' => 'PERS_has_PICT',
                    'query' => 'data/entities/Person/linked/PERS_has_PICT/%d'
                ]
            ],
            Entities::RESEARCH_FOCUSES => [
                self::DEFAULT => [
                    'id' => 3170,
                    'name' => 'PERS_has_RESA',
                    'query' => 'data/entities/Person/linked/PERS_has_RESA/%d'
                ]
            ],
            Entities::RESEARCH_NETWORK => [
                self::DEFAULT => [
                    'id' => 14923,
                    'name' => 'PERS_has_RENE',
                    'query' => 'data/entities/Person/linked/PERS_has_RENE/%d'
                ]
            ],
            Entities::STATISTICS_AREAS => [
                self::DEFAULT => [
                    'id' => 12209,
                    'name' => 'PERS_has_STAT',
                    'query' => 'data/entities/Person/linked/PERS_has_STAT/%d'
                ]
            ],
            Entities::TAGS => [
                self::DEFAULT => [
                    'id' => 14946,
                    'name' => 'PERS_has_TAGS',
                    'query' => 'data/entities/Person/linked/PERS_has_TAGS/%d'
                ]
            ],
        ],

        Entities::PUBLICATIONS => [
            Entities::AREAS => [
                self::DEFAULT => [
                    'id' => 3239,
                    'name' => 'PUBL_has_AREA',
                    'query' => 'data/entities/Publication/linked/PUBL_has_AREA/%d'
                ],
            ],
            Entities::BRANCHES => [
                self::DEFAULT => [
                    'id' => 2301844,
                    'name' => 'PUBL_has_WIZW',
                    'query' => 'data/entities/Publication/linked/PUBL_has_WIZW/%d'
                ],
            ],
            Entities::CARDS => [

                [
                    'id' => 7195,
                    'name' => 'PUBL_has_cl_CARD',
                    'isTree' => false,
                    'description' => '[X] Anfragen von Autoren',
                    'select' => 'Publication',
                    'where' => 'Card',
                    'query' => 'data/entities/Publication/linked/PUBL_has_cl_CARD/%d'
                ],
                [
                    'id' => 3745,
                    'name' => 'PUBL_has_vali_CARD',
                    'isTree' => false,
                    'description' => '[X] Bearbeiter',
                    'select' => 'Publication',
                    'where' => 'Card',
                    'query' => 'data/entities/Publication/linked/PUBL_has_vali_CARD/%d'
                ],
            ],
            Entities::DFG_FIELDS => [
                self::DEFAULT => [
                    'id' => 14463,
                    'name' => 'PUBL_has_DFGA',
                    'query' => 'data/entities/Publication/linked/PUBL_has_DFGA/%d'
                ],
            ],
            Entities::JOURNALS => [
                self::DEFAULT => [
                    'id' => 3032,
                    'name' => 'PUBL_has_JOUR',
                    'query' => 'data/entities/Publication/linked/PUBL_has_JOUR/%d'
                ],
            ],
            Entities::PEER_REVIEWS => [
                self::DEFAULT => [
                    'id' => 4527,
                    'name' => 'PUBL_has_PEER',
                    'query' => 'data/entities/Publication/linked/PUBL_has_PEER/%d'
                ],
            ],
            Entities::PUBLICATION_APPLICATIONS => [
                self::DEFAULT => [
                    'id' => 3607,
                    'name' => 'PUBL_has_a_PUBA',
                    'query' => 'data/entities/Publication/linked/PUBL_has_a_PUBA/%d'
                ],
            ],
            [
                'id' => 3147,
                'name' => 'PUBL_has_CARD',
                'isTree' => false,
                'description' => '[X] Autoren [KDSF:Pu52]',
                'query' => 'data/entities/Publication/linked/PUBL_has_CARD/%d'
            ],
            [
                'id' => 5401,
                'name' => 'PUBL_has_EVEN',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'cfEvent',
                'query' => 'data/entities/Publication/linked/PUBL_has_EVEN/%d'
            ],
            [
                'id' => 6758,
                'name' => 'PUBL_has_LANG',
                'isTree' => false,
                'description' => '[X] Sprache [KDSF:Pu95]',
                'select' => 'Publication',
                'where' => 'cfLang',
                'query' => 'data/entities/Publication/linked/PUBL_has_LANG/%d'
            ],
            [
                'id' => 6896,
                'name' => 'PUBL_has_PUBL',
                'isTree' => false,
                'description' => 'Relation to depict the relation of a publication that is part of a book to the book itself',
                'select' => 'Publication',
                'where' => 'Publication',
                'query' => 'data/entities/Publication/linked/PUBL_has_PUBL/%d'
            ],
            [
                'id' => 6965,
                'name' => 'PUBL_has_DDC',
                'isTree' => false,
                'description' => 'PUBL_has_DDC',
                'select' => 'Publication',
                'where' => 'DDC',
                'query' => 'data/entities/Publication/linked/PUBL_has_DDC/%d'
            ],
            [
                'id' => 7149,
                'name' => 'PUBL_has_rej_CARD',
                'isTree' => false,
                'description' => '[X] Abgelehnte Anfragen',
                'select' => 'Publication',
                'where' => 'Card',
                'query' => 'data/entities/Publication/linked/PUBL_has_rej_CARD/%d'
            ],
            [
                'id' => 3446,
                'name' => 'PUBL_has_FILE',
                'isTree' => false,
                'description' => '[X] To handle full texts for publications (or similar files for different publication or output types).',
                'select' => 'Publication',
                'where' => 'File',
                'query' => 'data/entities/Publication/linked/PUBL_has_FILE/%d'
            ],
            [
                'id' => 7425,
                'name' => 'PUBL_has_COUN',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'Country',
                'query' => 'data/entities/Publication/linked/PUBL_has_COUN/%d'
            ],
            [
                'id' => 7448,
                'name' => 'PUBL_has_ISIA',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'ISISubjCat',
                'query' => 'data/entities/Publication/linked/PUBL_has_ISIA/%d'
            ],
            [
                'id' => 13474,
                'name' => 'PUBL_has_PAPL',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'Project application',
                'query' => 'data/entities/Publication/linked/PUBL_has_PAPL/%d'
            ],
            [
                'id' => 13566,
                'name' => 'PUBL_has_CENT',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'Centrum',
                'query' => 'data/entities/Publication/linked/PUBL_has_CENT/%d'
            ],
            [
                'id' => 13589,
                'name' => 'PUBL_has_RENE',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'researchNetwork',
                'query' => 'data/entities/Publication/linked/PUBL_has_RENE/%d'
            ],
            [
                'id' => 14440,
                'name' => 'PUBL_has_STAT',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'StatisticsArea',
                'query' => 'data/entities/Publication/linked/PUBL_has_STAT/%d'
            ],
            [
                'id' => 15291,
                'name' => 'PUBL_has_FOSP',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'Forschungsschwerpunkt',
                'query' => 'data/entities/Publication/linked/PUBL_has_FOSP/%d'
            ],
            [
                'id' => 15314,
                'name' => 'PUBL_has_PICT',
                'isTree' => false,
                'description' => 'Pictures related to publications',
                'select' => 'Publication',
                'where' => 'Picture',
                'query' => 'data/entities/Publication/linked/PUBL_has_PICT/%d'
            ],
            [
                'id' => 2245713,
                'name' => 'PUBL_has_EXTO',
                'isTree' => false,
                'description' => '[X] Externe Organisationen',
                'select' => 'Publication',
                'where' => 'externalOrganisation',
                'query' => 'data/entities/Publication/linked/PUBL_has_EXTO/%d'
            ],
            /*
            [
                'id' => 7287,
                'name' => 'PUBL_has_add_FILE',
                'isTree' => false,
                'description' => 'Additional documents',
                'select' => 'Publication',
                'where' => 'File',
                'query' => 'data/entities/Publication/linked/PUBL_has_add_FILE/%d'
            ],
            [
                'id' => 7310,
                'name' => 'PUBL_has_vers_FILE',
                'isTree' => false,
                'description' => 'Previous versions',
                'select' => 'Publication',
                'where' => 'File',
                'query' => 'data/entities/Publication/linked/PUBL_has_vers_FILE/%d'
            ],
            [
                'id' => 13543,
                'name' => 'PUBL_has_ext_ORGA',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'Organisation',
                'query' => 'data/entities/Publication/linked/PUBL_has_ext_ORGA/%d'
            ],
            [
                'id' => 7517,
                'name' => 'PUBL_has_fund_ORGA',
                'isTree' => false,
                'description' => 'organisation funding the publication',
                'select' => 'Publication',
                'where' => 'Organisation',
                'query' => 'data/entities/Publication/linked/PUBL_has_fund_ORGA/%d'
            ],
            [
                'id' => 2302639,
                'name' => 'PUBL_has_child_PUBL',
                'isTree' => false,
                'select' => 'Publication',
                'where' => 'Publication',
                'query' => 'data/entities/Publication/linked/PUBL_has_child_PUBL/%d'
            ],
            */
        ],

        Entities::TASKS => [
            Entities::FILES => [
                'name' => 'TASK_has_FILE',
                'id' => 3676,
                'query' => 'data/entities/Task/linked/TASK_has_FILE/%d'
            ],
            /*
            [
                'id' => 3630,
                'name' => 'TASK_has_resp_CARD',
                'isTree' => false,
                'select' => 'Task',
                'where' => 'Card',
                'query' => 'data/entities/Task/linked/TASK_has_resp_CARD/%d'
            ],
            [
                'id' => 3653,
                'name' => 'TASK_has_reg_CARD',
                'isTree' => false,
                'select' => 'Task',
                'where' => 'Card',
                'query' => 'data/entities/Task/linked/TASK_has_reg_CARD/%d'
            ],
            [
                'id' => 2986,
                'name' => 'TASK_has_com_ORGA',
                'isTree' => false,
                'select' => 'Task',
                'where' => 'Organisation',
                'query' => 'data/entities/Task/linked/TASK_has_com_ORGA/%d'
            ],
            [
                'id' => 14969,
                'name' => 'TASK_has_resp_ORGA',
                'isTree' => false,
                'description' => 'Verwaltungsorganisation, die für die Aufgabe verantwortlich ist\r\n',
                'select' => 'Task',
                'where' => 'Organisation',
                'query' => 'data/entities/Task/linked/TASK_has_resp_ORGA/%d'
            ],
            [
                'id' => 14992,
                'name' => 'TASK_has_reg_ORGA',
                'isTree' => false,
                'description' => 'Verwaltungsorganisation, die die Aufgabe registriert hat',
                'select' => 'Task',
                'where' => 'Organisation',
                'query' => 'data/entities/Task/linked/TASK_has_reg_ORGA/%d'
            ],
            [
                'id' => 2332410,
                'name' => 'TASK_has_resp_PERS',
                'isTree' => false,
                'select' => 'Task',
                'where' => 'Person',
                'query' => 'data/entities/Task/linked/TASK_has_resp_PERS/%d'
            ],
            [
                'id' => 2333705,
                'name' => 'TASK_has_reg_PERS',
                'isTree' => false,
                'select' => 'Task',
                'where' => 'Person',
                'query' => 'data/entities/Task/linked/TASK_has_reg_PERS/%d'
            ],
            */
        ],
    ];

    public static function getID(int $target, int $filter, int $extended = self::DEFAULT): string
    {
        return self::getValue($target, $filter, $extended, 'id');
    }

    public static function getName(int $target, int $filter, int $extended = self::DEFAULT): string
    {
        return self::getValue($target, $filter, $extended, 'name');
    }

    public static function getQuery(int $target, int $filter, int $extended = self::DEFAULT): string
    {
        return self::getValue($target, $filter, $extended, 'query');
    }

    private static function getValue(int $target, int $filter, int $extended, string $value): int|string
    {
        if (!in_array($value, ['id', 'name', 'query'])) {
            echo "<pre>The requested value is unsupported.</pre>";
            return '';
        }

        $default = $value === 'id' ? 0 : '';

        if (empty($target)) {
            echo "<pre>The target entity has not been specified.</pre>";
            return $default;
        } elseif (empty($filter)) {
            echo "<pre>The filter entity has not been specified.</pre>";
            return $default;
        }


        if (empty(Entities::ENTITIES[$target])) {
            echo "<pre>No entity has been registered for the target index $target.</pre>";
            return $default;
        } elseif (empty(Entities::ENTITIES[$filter])) {
            echo "<pre>No entity has been registered for the filter index $filter.</pre>";
            return $default;
        }

        $tag     = Application::getTag();
        $plural  = 'plural_' . $tag;
        $filters = Entities::ENTITIES[$filter][$plural];
        $targets = Entities::ENTITIES[$target][$plural];

        if (empty(self::TARGETS[$target])) {
            echo "<pre>No associations have been registered for $targets ($target).</pre>";
            return $default;
        } elseif (empty(self::TARGETS[$target][$filter])) {
            echo "<pre>$targets have no registered associations to $filters ($filter).</pre>";
            return $default;
        }

        if ($extended !== self::DEFAULT) {
            if (!class_exists($filters)) {
                echo "<pre>The $filters class does not exist within the current namespace.</pre>";
                return $default;
            } elseif (empty($filters::filters[$extended])) {
                echo "<pre>The $filters class has not registered an extended filter at the index $extended.</pre>";
                return $default;
            }

            $extFilter = 'filter_' . $tag;
            $extFilter = $filters::filters[$extended][$extFilter];
        } else {
            $extFilter = 'Default';
        }

        if (empty(self::TARGETS[$target][$filter][$extended])) {
            echo "<pre>The extended filter $extFilter ($extended) has not been registered in the $targets > $filters context.</pre>";
            return $default;
        } elseif (empty(self::TARGETS[$target][$filter][$extended][$value])) {
            echo "<pre>The extended filter $extFilter ($extended) in the $targets > $filters context is invalid.</pre>";
            return $default;
        }

        return self::TARGETS[$target][$filter][$extended][$value];
    }
}

/*
[
  [
    'id' => 3078,
    'name' => 'ISIA_has_child_ISIA',
    'isTree' => true,
    'select' => 'ISISubjCat',
    'where' => 'ISISubjCat',
    'query' => 'data/entities/ISISubjCat/linked/ISIA_has_child_ISIA/%d'
  ],
  [
    'id' => 3193,
    'name' => 'ORGA_has_COUN',
    'isTree' => false,
    'description' => 'Country in which the organisation is located.',
    'select' => 'Organisation',
    'where' => 'Country',
    'query' => 'data/entities/Organisation/linked/ORGA_has_COUN/%d'
  ],
  [
    'id' => 3216,
    'name' => 'ACTI_has_CARD',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Card',
    'query' => 'data/entities/Activity/linked/ACTI_has_CARD/%d'
  ],
  [
    'id' => 3285,
    'name' => 'ORGA_has_child_ORGA',
    'isTree' => true,
    'description' => '[X] Untergeordnete Organisationseinheiten',
    'select' => 'Organisation',
    'where' => 'Organisation',
    'query' => 'data/entities/Organisation/linked/ORGA_has_child_ORGA/%d'
  ],
  [
    'id' => 3400,
    'name' => 'MESS_has_sender_PERS',
    'isTree' => false,
    'select' => 'Message',
    'where' => 'Person',
    'query' => 'data/entities/Message/linked/MESS_has_sender_PERS/%d'
  ],
  [
    'id' => 3423,
    'name' => 'MESS_has_rec_PERS',
    'isTree' => false,
    'select' => 'Message',
    'where' => 'Person',
    'query' => 'data/entities/Message/linked/MESS_has_rec_PERS/%d'
  ],
  [
    'id' => 3469,
    'name' => 'EFIL_has_FILE',
    'isTree' => false,
    'select' => 'Embedded file',
    'where' => 'File',
    'query' => 'data/entities/Embedded file/linked/EFIL_has_FILE/%d'

  ],
  [
    'id' => 3492,
    'name' => 'ORGA_has_AREA',
    'isTree' => false,
    'description' => '[X] Zuordnung zu THM Schwerpunkten',
    'select' => 'Organisation',
    'where' => 'Area',
    'query' => 'data/entities/Organisation/linked/ORGA_has_AREA/%d'
  ],
  [
    'id' => 3515,
    'name' => 'PUBA_has_AREA',
    'isTree' => false,
    'select' => 'Publication application',
    'where' => 'Area',
    'query' => 'data/entities/Publication application/linked/PUBA_has_AREA/%d'
  ],
  [
    'id' => 3538,
    'name' => 'PUBA_has_CARD',
    'isTree' => false,
    'select' => 'Publication application',
    'where' => 'Card',
    'query' => 'data/entities/Publication application/linked/PUBA_has_CARD/%d'
  ],
  [
    'id' => 3561,
    'name' => 'PUBA_has_JOUR',
    'isTree' => false,
    'select' => 'Publication application',
    'where' => 'Journal',
    'query' => 'data/entities/Publication application/linked/PUBA_has_JOUR/%d'
  ],
  [
    'id' => 3584,
    'name' => 'PUBA_has_FILE',
    'isTree' => false,
    'select' => 'Publication application',
    'where' => 'File',
    'query' => 'data/entities/Publication application/linked/PUBA_has_FILE/%d'
  ],
  [
    'id' => 3768,
    'name' => 'MEET_has_FILE',
    'isTree' => false,
    'select' => 'Meeting',
    'where' => 'File',
    'query' => 'data/entities/Meeting/linked/MEET_has_FILE/%d'

  ],
  [
    'id' => 3791,
    'name' => 'MILE_has_FILE',
    'isTree' => false,
    'select' => 'Milestone',
    'where' => 'File',
    'query' => 'data/entities/Milestone/linked/MILE_has_FILE/%d'

  ],
  [
    'id' => 3814,
    'name' => 'PROG_has_FILE',
    'isTree' => false,
    'select' => 'Progress exception',
    'where' => 'File',
    'query' => 'data/entities/Progress exception/linked/PROG_has_FILE/%d'

  ],
  [
    'id' => 3837,
    'name' => 'GRDE_has_PERS',
    'isTree' => false,
    'select' => 'Grade',
    'where' => 'Person',
    'query' => 'data/entities/Grade/linked/GRDE_has_PERS/%d'
  ],
  [
    'id' => 3860,
    'name' => 'STUD_has_PUBL',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Publication',
    'query' => 'data/entities/Study plan/linked/STUD_has_PUBL/%d'

  ],
  [
    'id' => 3883,
    'name' => 'STUD_has_sup_PERS',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Person',
    'query' => 'data/entities/Study plan/linked/STUD_has_sup_PERS/%d'
  ],
  [
    'id' => 3906,
    'name' => 'STUD_has_plan_FILE',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'File',
    'query' => 'data/entities/Study plan/linked/STUD_has_plan_FILE/%d'
  ],
  [
    'id' => 3929,
    'name' => 'STUD_has_PROG',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Progress exception',
    'query' => 'data/entities/Study plan/linked/STUD_has_PROG/%d'
  ],
  [
    'id' => 3952,
    'name' => 'GRAD_has_ADMI',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Admission',
    'query' => 'data/entities/Graduation/linked/GRAD_has_ADMI/%d'
  ],
  [
    'id' => 3975,
    'name' => 'GRAD_has_cert_FILE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'File',
    'query' => 'data/entities/Graduation/linked/GRAD_has_cert_FILE/%d'

  ],
  [
    'id' => 3998,
    'name' => 'STUD_has_train_EVEN',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'cfEvent',
    'query' => 'data/entities/Study plan/linked/STUD_has_train_EVEN/%d'

  ],
  [
    'id' => 4021,
    'name' => 'GRAD_has_adm_FILE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'File',
    'query' => 'data/entities/Graduation/linked/GRAD_has_adm_FILE/%d'

  ],
  [
    'id' => 4044,
    'name' => 'STUD_has_GRDP',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Graduate Programs',
    'query' => 'data/entities/Study plan/linked/STUD_has_GRDP/%d'
  ],
  [
    'id' => 4067,
    'name' => 'STUD_has_lec_EVEN',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'cfEvent',
    'query' => 'data/entities/Study plan/linked/STUD_has_lec_EVEN/%d'

  ],
  [
    'id' => 4090,
    'name' => 'STUD_has_cour_EVEN',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'cfEvent',
    'query' => 'data/entities/Study plan/linked/STUD_has_cour_EVEN/%d'

  ],
  [
    'id' => 4113,
    'name' => 'STUD_has_form_FILE',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'File',
    'query' => 'data/entities/Study plan/linked/STUD_has_form_FILE/%d'
  ],
  [
    'id' => 4136,
    'name' => 'STUD_has_phd_PERS',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Person',
    'query' => 'data/entities/Study plan/linked/STUD_has_phd_PERS/%d'
  ],
  [
    'id' => 4159,
    'name' => 'STUD_has_add_FILE',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'File',
    'query' => 'data/entities/Study plan/linked/STUD_has_add_FILE/%d'
  ],
  [
    'id' => 4182,
    'name' => 'STUD_has_MEET',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Meeting',
    'query' => 'data/entities/Study plan/linked/STUD_has_MEET/%d'
  ],
  [
    'id' => 4205,
    'name' => 'STUD_has_MILE',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Milestone',
    'query' => 'data/entities/Study plan/linked/STUD_has_MILE/%d'
  ],
  [
    'id' => 4228,
    'name' => 'ADMI_has_GRDP',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'Graduate Programs',
    'query' => 'data/entities/Admission/linked/ADMI_has_GRDP/%d'
  ],
  [
    'id' => 4251,
    'name' => 'LASK_has_LANG',
    'isTree' => false,
    'select' => 'cfLangSkill',
    'where' => 'cfLang',
    'query' => 'data/entities/cfLangSkill/linked/LASK_has_LANG/%d'
  ],
  [
    'id' => 4274,
    'name' => 'ADMI_has_sup_PERS',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'Person',
    'query' => 'data/entities/Admission/linked/ADMI_has_sup_PERS/%d'

  ],
  [
    'id' => 4297,
    'name' => 'STUD_has_conf_EVEN',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'cfEvent',
    'query' => 'data/entities/Study plan/linked/STUD_has_conf_EVEN/%d'

  ],
  [
    'id' => 4320,
    'name' => 'STUD_has_adv_PUBL',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Publication',
    'query' => 'data/entities/Study plan/linked/STUD_has_adv_PUBL/%d'

  ],
  [
    'id' => 4343,
    'name' => 'STUD_has_ADMI',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Admission',
    'query' => 'data/entities/Study plan/linked/STUD_has_ADMI/%d'
  ],
  [
    'id' => 4366,
    'name' => 'EVEN_has_COUN',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'Country',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_COUN/%d'
  ],
  [
    'id' => 4389,
    'name' => 'ADMI_has_grad_PERS',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'Person',
    'query' => 'data/entities/Admission/linked/ADMI_has_grad_PERS/%d'
  ],
  [
    'id' => 4412,
    'name' => 'ADMI_has_dip_FILE',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'File',
    'query' => 'data/entities/Admission/linked/ADMI_has_dip_FILE/%d'

  ],
  [
    'id' => 4435,
    'name' => 'ADMI_has_cv_FILE',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'File',
    'query' => 'data/entities/Admission/linked/ADMI_has_cv_FILE/%d'

  ],
  [
    'id' => 4458,
    'name' => 'ADMI_has_pass_FILE',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'File',
    'query' => 'data/entities/Admission/linked/ADMI_has_pass_FILE/%d'

  ],
  [
    'id' => 4481,
    'name' => 'ADMI_has_ORGA',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'Organisation',
    'query' => 'data/entities/Admission/linked/ADMI_has_ORGA/%d'
  ],
  [
    'id' => 4504,
    'name' => 'ADMI_has_LASK',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'cfLangSkill',
    'query' => 'data/entities/Admission/linked/ADMI_has_LASK/%d'
  ],
  [
    'id' => 4550,
    'name' => 'REFC_has_PUBL',
    'isTree' => false,
    'select' => 'REFClaim',
    'where' => 'Publication',
    'query' => 'data/entities/REFClaim/linked/REFC_has_PUBL/%d'

  ],
  [
    'id' => 4573,
    'name' => 'RDFD_has_child_RDFD',
    'isTree' => true,
    'select' => 'RDFDomain',
    'where' => 'RDFDomain',
    'query' => 'data/entities/RDFDomain/linked/RDFD_has_child_RDFD/%d'
  ],
  [
    'id' => 4596,
    'name' => 'MILE_has_RDFD',
    'isTree' => false,
    'select' => 'Milestone',
    'where' => 'RDFDomain',
    'query' => 'data/entities/Milestone/linked/MILE_has_RDFD/%d'
  ],
  [
    'id' => 4619,
    'name' => 'STUD_has_ORGA',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Organisation',
    'query' => 'data/entities/Study plan/linked/STUD_has_ORGA/%d'
  ],
  [
    'id' => 4642,
    'name' => 'FMIL_has_FILE',
    'isTree' => false,
    'select' => 'FixedMilestone',
    'where' => 'File',
    'query' => 'data/entities/FixedMilestone/linked/FMIL_has_FILE/%d'

  ],
  [
    'id' => 4665,
    'name' => 'STUD_has_FMIL',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'FixedMilestone',
    'query' => 'data/entities/Study plan/linked/STUD_has_FMIL/%d'
  ],
  [
    'id' => 4688,
    'name' => 'GRAD_has_GRDP',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Graduate Programs',
    'query' => 'data/entities/Graduation/linked/GRAD_has_GRDP/%d'
  ],
  [
    'id' => 4711,
    'name' => 'GRAD_has_grad_PERS',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Person',
    'query' => 'data/entities/Graduation/linked/GRAD_has_grad_PERS/%d'
  ],
  [
    'id' => 4734,
    'name' => 'GRAD_has_kom_CARD',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Card',
    'query' => 'data/entities/Graduation/linked/GRAD_has_kom_CARD/%d'
  ],
  [
    'id' => 4757,
    'name' => 'GRAD_has_hd_FILE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'File',
    'query' => 'data/entities/Graduation/linked/GRAD_has_hd_FILE/%d'

  ],
  [
    'id' => 4780,
    'name' => 'GRAD_has_ORGA',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Organisation',
    'query' => 'data/entities/Graduation/linked/GRAD_has_ORGA/%d'
  ],
  [
    'id' => 4803,
    'name' => 'GRAD_has_sur_CARD',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Card',
    'query' => 'data/entities/Graduation/linked/GRAD_has_sur_CARD/%d'
  ],
  [
    'id' => 4826,
    'name' => 'GRAD_has_sup_PERS',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Person',
    'query' => 'data/entities/Graduation/linked/GRAD_has_sup_PERS/%d'
  ],
  [
    'id' => 4849,
    'name' => 'GRAD_has_disp_GRDE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Grade',
    'query' => 'data/entities/Graduation/linked/GRAD_has_disp_GRDE/%d'
  ],
  [
    'id' => 4872,
    'name' => 'GRAD_has_comp_ORGA',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Organisation',
    'query' => 'data/entities/Graduation/linked/GRAD_has_comp_ORGA/%d'
  ],
  [
    'id' => 4895,
    'name' => 'GRAD_has_diss_GRDE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Grade',
    'query' => 'data/entities/Graduation/linked/GRAD_has_diss_GRDE/%d'
  ],
  [
    'id' => 4918,
    'name' => 'GRAD_has_dispu_FILE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'File',
    'query' => 'data/entities/Graduation/linked/GRAD_has_dispu_FILE/%d'

  ],
  [
    'id' => 4941,
    'name' => 'GRAD_has_eval_FILE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'File',
    'query' => 'data/entities/Graduation/linked/GRAD_has_eval_FILE/%d'

  ],
  [
    'id' => 4964,
    'name' => 'GRAD_has_doc_FILE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'File',
    'query' => 'data/entities/Graduation/linked/GRAD_has_doc_FILE/%d'

  ],
  [
    'id' => 4987,
    'name' => 'GRAD_has_exp2_PERS',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Person',
    'query' => 'data/entities/Graduation/linked/GRAD_has_exp2_PERS/%d'
  ],
  [
    'id' => 5010,
    'name' => 'GRAD_has_exp1_PERS',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Person',
    'query' => 'data/entities/Graduation/linked/GRAD_has_exp1_PERS/%d'
  ],
  [
    'id' => 5033,
    'name' => 'PROJ_has_FUND',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'cfFund',
    'query' => 'data/entities/Project/linked/PROJ_has_FUND/%d'

  ],
  [
    'id' => 5056,
    'name' => 'PROJ_has_pi_CARD',
    'isTree' => false,
    'description' => '[X] Projektleiter',
    'select' => 'Project',
    'where' => 'Card',
    'query' => 'data/entities/Project/linked/PROJ_has_pi_CARD/%d'
  ],
  [
    'id' => 5079,
    'name' => 'PROJ_has_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_FILE/%d'

  ],
  [
    'id' => 5102,
    'name' => 'PROJ_has_coi_CARD',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Card',
    'query' => 'data/entities/Project/linked/PROJ_has_coi_CARD/%d'

  ],
  [
    'id' => 5125,
    'name' => 'PROJ_has_AREA',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Area',
    'query' => 'data/entities/Project/linked/PROJ_has_AREA/%d'
  ],
  [
    'id' => 5148,
    'name' => 'PROJ_has_claim_TASK',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Task',
    'query' => 'data/entities/Project/linked/PROJ_has_claim_TASK/%d'
  ],
  [
    'id' => 5171,
    'name' => 'PROJ_has_PAPL',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Project application',
    'query' => 'data/entities/Project/linked/PROJ_has_PAPL/%d'
  ],
  [
    'id' => 5194,
    'name' => 'PAPL_has_spo_ORGA',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Organisation',
    'query' => 'data/entities/Project application/linked/PAPL_has_spo_ORGA/%d'
  ],
  [
    'id' => 5217,
    'name' => 'PAPL_has_TASK',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Task',
    'query' => 'data/entities/Project application/linked/PAPL_has_TASK/%d'
  ],
  [
    'id' => 5240,
    'name' => 'PAPL_has_pi_CARD',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Card',
    'query' => 'data/entities/Project application/linked/PAPL_has_pi_CARD/%d'
  ],
  [
    'id' => 5263,
    'name' => 'PAPL_has_FUND',
    'isTree' => false,
    'description' => '[X] Verknüpfung zur Förderlinie \/ Ausschreibung',
    'select' => 'Project application',
    'where' => 'cfFund',
    'query' => 'data/entities/Project application/linked/PAPL_has_FUND/%d'

  ],
  [
    'id' => 5286,
    'name' => 'PAPL_has_coi_CARD',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Card',
    'query' => 'data/entities/Project application/linked/PAPL_has_coi_CARD/%d'

  ],
  [
    'id' => 5309,
    'name' => 'PAPL_has_AREA',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Area',
    'query' => 'data/entities/Project application/linked/PAPL_has_AREA/%d'
  ],
  [
    'id' => 5332,
    'name' => 'PAPL_has_app_FILE',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_app_FILE/%d'

  ],
  [
    'id' => 5355,
    'name' => 'PAPL_has_adm_CARD',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Card',
    'query' => 'data/entities/Project application/linked/PAPL_has_adm_CARD/%d'

  ],
  [
    'id' => 5378,
    'name' => 'SEAR_HAS_CARD',
    'isTree' => false,
    'select' => 'Search profile',
    'where' => 'Card',
    'query' => 'data/entities/Search profile/linked/SEAR_HAS_CARD/%d'
  ],
  [
    'id' => 5424,
    'name' => 'PEER_has_UNIT',
    'isTree' => false,
    'select' => 'PeerReview',
    'where' => 'UnitOfAssess',
    'query' => 'data/entities/PeerReview/linked/PEER_has_UNIT/%d'
  ],
  [
    'id' => 5447,
    'name' => 'REFC_has_UNIT',
    'isTree' => false,
    'select' => 'REFClaim',
    'where' => 'UnitOfAssess',
    'query' => 'data/entities/REFClaim/linked/REFC_has_UNIT/%d'
  ],
  [
    'id' => 5470,
    'name' => 'UNIT_has_child_UNIT',
    'isTree' => true,
    'select' => 'UnitOfAssess',
    'where' => 'UnitOfAssess',
    'query' => 'data/entities/UnitOfAssess/linked/UNIT_has_child_UNIT/%d'
  ],
  [
    'id' => 5493,
    'name' => 'REFC_has_CARD',
    'isTree' => false,
    'select' => 'REFClaim',
    'where' => 'Card',
    'query' => 'data/entities/REFClaim/linked/REFC_has_CARD/%d'

  ],
  [
    'id' => 5516,
    'name' => 'PROJ_has_scan_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_scan_FILE/%d'

  ],
  [
    'id' => 5539,
    'name' => 'PROJ_has_adm_CARD',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Card',
    'query' => 'data/entities/Project/linked/PROJ_has_adm_CARD/%d'

  ],
  [
    'id' => 5562,
    'name' => 'IDEA_has_spo_ORGA',
    'isTree' => false,
    'select' => 'Idea',
    'where' => 'Organisation',
    'query' => 'data/entities/Idea/linked/IDEA_has_spo_ORGA/%d'
  ],
  [
    'id' => 5585,
    'name' => 'PEER_has_PERS',
    'isTree' => false,
    'description' => 'Relation to indicate by whom the peer rating has been given.',
    'select' => 'PeerReview',
    'where' => 'Person',
    'query' => 'data/entities/PeerReview/linked/PEER_has_PERS/%d'
  ],
  [
    'id' => 5608,
    'name' => 'TAGS_has_PROJ',
    'isTree' => false,
    'description' => 'Projects that have the respective keyword related.',
    'select' => 'Tags',
    'where' => 'Project',
    'query' => 'data/entities/Tags/linked/TAGS_has_PROJ/%d'
  ],
  [
    'id' => 5631,
    'name' => 'TAGS_has_PUBL',
    'isTree' => false,
    'description' => 'Publications that have the respective keyword related.',
    'select' => 'Tags',
    'where' => 'Publication',
    'query' => 'data/entities/Tags/linked/TAGS_has_PUBL/%d'
  ],
  [
    'id' => 5654,
    'name' => 'IDEA_has_TASK',
    'isTree' => false,
    'select' => 'Idea',
    'where' => 'Task',
    'query' => 'data/entities/Idea/linked/IDEA_has_TASK/%d'
  ],
  [
    'id' => 5677,
    'name' => 'TAGS_has_PAPL',
    'isTree' => false,
    'select' => 'Tags',
    'where' => 'Project application',
    'query' => 'data/entities/Tags/linked/TAGS_has_PAPL/%d'
  ],
  [
    'id' => 5700,
    'name' => 'TAGS_has_AREA',
    'isTree' => false,
    'description' => 'Thematic areas that have the respective keyword related.',
    'select' => 'Tags',
    'where' => 'Area',
    'query' => 'data/entities/Tags/linked/TAGS_has_AREA/%d'
  ],
  [
    'id' => 5723,
    'name' => 'TAGS_has_IDEA',
    'isTree' => false,
    'description' => 'Ideas that have the respective keyword related.',
    'select' => 'Tags',
    'where' => 'Idea',
    'query' => 'data/entities/Tags/linked/TAGS_has_IDEA/%d'
  ],
  [
    'id' => 5746,
    'name' => 'IDEA_has_CARD',
    'isTree' => false,
    'description' => 'Currently planed team to fulfil the idea.',
    'select' => 'Idea',
    'where' => 'Card',
    'query' => 'data/entities/Idea/linked/IDEA_has_CARD/%d'
  ],
  [
    'id' => 5769,
    'name' => 'IDEA_has_FILE',
    'isTree' => false,
    'description' => 'Key documents related to the project idea.',
    'select' => 'Idea',
    'where' => 'File',
    'query' => 'data/entities/Idea/linked/IDEA_has_FILE/%d'

  ],
  [
    'id' => 5792,
    'name' => 'IDEA_has_FUND',
    'isTree' => false,
    'description' => 'Potential funding source. It is important to note that not the funder as an organisation is linked, but the funding programme.',
    'select' => 'Idea',
    'where' => 'cfFund',
    'query' => 'data/entities/Idea/linked/IDEA_has_FUND/%d'
  ],
  [
    'id' => 5815,
    'name' => 'IDEA_has_idea_CARD',
    'isTree' => false,
    'description' => 'Owner of the idea.',
    'select' => 'Idea',
    'where' => 'Card',
    'query' => 'data/entities/Idea/linked/IDEA_has_idea_CARD/%d'
  ],
  [
    'id' => 5838,
    'name' => 'GRAD_has_AREA',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Area',
    'query' => 'data/entities/Graduation/linked/GRAD_has_AREA/%d'
  ],
  [
    'id' => 5861,
    'name' => 'IDEA_has_AREA',
    'isTree' => false,
    'description' => 'Thematic area to categorize this project idea.',
    'select' => 'Idea',
    'where' => 'Area',
    'query' => 'data/entities/Idea/linked/IDEA_has_AREA/%d'
  ],
  [
    'id' => 5884,
    'name' => 'IDEA_has_PAPL',
    'isTree' => false,
    'description' => 'Related project applications.',
    'select' => 'Idea',
    'where' => 'Project application',
    'query' => 'data/entities/Idea/linked/IDEA_has_PAPL/%d'
  ],
  [
    'id' => 5907,
    'name' => 'PAPL_has_EFIL',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Embedded file',
    'query' => 'data/entities/Project application/linked/PAPL_has_EFIL/%d'
  ],
  [
    'id' => 5930,
    'name' => 'PAPL_has_docu_FILE',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_docu_FILE/%d'
  ],
  [
    'id' => 5953,
    'name' => 'ADMI_has_AREA',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'Area',
    'query' => 'data/entities/Admission/linked/ADMI_has_AREA/%d'
  ],
  [
    'id' => 5976,
    'name' => 'STUD_has_AREA',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Area',
    'query' => 'data/entities/Study plan/linked/STUD_has_AREA/%d'
  ],
  [
    'id' => 5999,
    'name' => 'PROJ_has_PUBL',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Publication',
    'query' => 'data/entities/Project/linked/PROJ_has_PUBL/%d'
  ],
  [
    'id' => 6022,
    'name' => 'PROJ_has_ext_ORGA',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Organisation',
    'query' => 'data/entities/Project/linked/PROJ_has_ext_ORGA/%d'

  ],
  [
    'id' => 6045,
    'name' => 'PAPL_has_ext_ORGA',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Organisation',
    'query' => 'data/entities/Project application/linked/PAPL_has_ext_ORGA/%d'

  ],
  [
    'id' => 6068,
    'name' => 'PROJ_has_spo_ORGA',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Organisation',
    'query' => 'data/entities/Project/linked/PROJ_has_spo_ORGA/%d'

  ],
  [
    'id' => 6091,
    'name' => 'PROJ_has_TASK',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Task',
    'query' => 'data/entities/Project/linked/PROJ_has_TASK/%d'
  ],
  [
    'id' => 6114,
    'name' => 'PATE_has_PROJ',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Project',
    'query' => 'data/entities/cfResPat/linked/PATE_has_PROJ/%d'
  ],
  [
    'id' => 6137,
    'name' => 'STUD_has_PART',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'Participation',
    'query' => 'data/entities/Study plan/linked/STUD_has_PART/%d'
  ],
  [
    'id' => 6160,
    'name' => 'TAGS_has_JOUR',
    'isTree' => false,
    'select' => 'Tags',
    'where' => 'Journal',
    'query' => 'data/entities/Tags/linked/TAGS_has_JOUR/%d'
  ],
  [
    'id' => 6183,
    'name' => 'PAPL_has_FILE',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_FILE/%d'
  ],
  [
    'id' => 6206,
    'name' => 'PROJ_has_pers_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_pers_FILE/%d'

  ],
  [
    'id' => 6229,
    'name' => 'TAGS_has_EVEN',
    'isTree' => false,
    'select' => 'Tags',
    'where' => 'cfEvent',
    'query' => 'data/entities/Tags/linked/TAGS_has_EVEN/%d'
  ],
  [
    'id' => 6252,
    'name' => 'PROJ_has_team_CARD',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Card',
    'query' => 'data/entities/Project/linked/PROJ_has_team_CARD/%d'

  ],
  [
    'id' => 6275,
    'name' => 'PAPL_has_pers_FILE',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_pers_FILE/%d'

  ],
  [
    'id' => 6298,
    'name' => 'EVEN_has_EVAL',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'Evaluation',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_EVAL/%d'
  ],
  [
    'id' => 6321,
    'name' => 'EVAL_has_eval_PERS',
    'isTree' => false,
    'select' => 'Evaluation',
    'where' => 'Person',
    'query' => 'data/entities/Evaluation/linked/EVAL_has_eval_PERS/%d'
  ],
  [
    'id' => 6344,
    'name' => 'EVEN_has_PART',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'Participation',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_PART/%d'
  ],
  [
    'id' => 6390,
    'name' => 'FUND_has_AREA',
    'isTree' => false,
    'select' => 'cfFund',
    'where' => 'Area',
    'query' => 'data/entities/cfFund/linked/FUND_has_AREA/%d'
  ],
  [
    'id' => 6413,
    'name' => 'REGI_has_FILE',
    'isTree' => false,
    'select' => 'Registration',
    'where' => 'File',
    'query' => 'data/entities/Registration/linked/REGI_has_FILE/%d'
  ],
  [
    'id' => 6436,
    'name' => 'PART_has_EVAL',
    'isTree' => false,
    'select' => 'Participation',
    'where' => 'Evaluation',
    'query' => 'data/entities/Participation/linked/PART_has_EVAL/%d'
  ],
  [
    'id' => 6459,
    'name' => 'EVEN_has_org_PERS',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'Person',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_org_PERS/%d'
  ],
  [
    'id' => 6482,
    'name' => 'EVEN_has_eval_PERS',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'Person',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_eval_PERS/%d'
  ],
  [
    'id' => 6505,
    'name' => 'INDI_has_inv_CARD',
    'isTree' => false,
    'description' => 'All members of the innovation group (additional relation type attribute for % contribution)',
    'select' => 'InventionDisc',
    'where' => 'Card',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_inv_CARD/%d'

  ],
  [
    'id' => 6528,
    'name' => 'INDI_has_resp_CARD',
    'isTree' => false,
    'description' => 'The user who is reporting the innovation',
    'select' => 'InventionDisc',
    'where' => 'Card',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_resp_CARD/%d'
  ],
  [
    'id' => 6574,
    'name' => 'PROJ_has_CARD',
    'isTree' => false,
    'description' => 'PROJ_has_CARD',
    'select' => 'Project',
    'where' => 'Card',
    'query' => 'data/entities/Project/linked/PROJ_has_CARD/%d'
  ],
  [
    'id' => 6643,
    'name' => 'FUND_has_child_FUND',
    'isTree' => true,
    'select' => 'cfFund',
    'where' => 'cfFund',
    'query' => 'data/entities/cfFund/linked/FUND_has_child_FUND/%d'
  ],
  [
    'id' => 6666,
    'name' => 'TAGS_has_FUND',
    'isTree' => false,
    'select' => 'Tags',
    'where' => 'cfFund',
    'query' => 'data/entities/Tags/linked/TAGS_has_FUND/%d'
  ],
  [
    'id' => 6689,
    'name' => 'PROJ_has_EXTE',
    'isTree' => false,
    'description' => 'Relation to extension to document\/approve an extension of the project',
    'select' => 'Project',
    'where' => 'Extension',
    'query' => 'data/entities/Project/linked/PROJ_has_EXTE/%d'
  ],
  [
    'id' => 6712,
    'name' => 'FUND_has_ORGA',
    'isTree' => false,
    'select' => 'cfFund',
    'where' => 'Organisation',
    'query' => 'data/entities/cfFund/linked/FUND_has_ORGA/%d'
  ],
  [
    'id' => 6735,
    'name' => 'FUND_has_FILE',
    'isTree' => false,
    'description' => '[X] Ausschreibungstext',
    'select' => 'cfFund',
    'where' => 'File',
    'query' => 'data/entities/cfFund/linked/FUND_has_FILE/%d'
  ],
  [
    'id' => 6781,
    'name' => 'EXTE_has_FILE',
    'isTree' => false,
    'description' => 'Document containing the (signed) approval for the deadline extension.',
    'select' => 'Extension',
    'where' => 'File',
    'query' => 'data/entities/Extension/linked/EXTE_has_FILE/%d'

  ],
  [
    'id' => 6804,
    'name' => 'EXTE_has_reas_FILE',
    'isTree' => false,
    'description' => 'Document to further emphasize the reasion for the deadline extension.',
    'select' => 'Extension',
    'where' => 'File',
    'query' => 'data/entities/Extension/linked/EXTE_has_reas_FILE/%d'

  ],
  [
    'id' => 6827,
    'name' => 'PATE_has_inv_CARD',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Card',
    'query' => 'data/entities/cfResPat/linked/PATE_has_inv_CARD/%d'

  ],
  [
    'id' => 6850,
    'name' => 'ETHI_has_revi_PERS',
    'isTree' => false,
    'select' => 'EthicsReview',
    'where' => 'Person',
    'query' => 'data/entities/EthicsReview/linked/ETHI_has_revi_PERS/%d'
  ],
  [
    'id' => 6873,
    'name' => 'ETHI_has_FILE',
    'isTree' => false,
    'select' => 'EthicsReview',
    'where' => 'File',
    'query' => 'data/entities/EthicsReview/linked/ETHI_has_FILE/%d'

  ],
  [
    'id' => 6919,
    'name' => 'PROJ_has_ETHI',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'EthicsReview',
    'query' => 'data/entities/Project/linked/PROJ_has_ETHI/%d'
  ],
  [
    'id' => 6942,
    'name' => 'PAPL_has_ETHI',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'EthicsReview',
    'query' => 'data/entities/Project application/linked/PAPL_has_ETHI/%d'
  ],
  [
    'id' => 6988,
    'name' => 'PATE_has_TAGS',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Tags',
    'query' => 'data/entities/cfResPat/linked/PATE_has_TAGS/%d'
  ],
  [
    'id' => 7011,
    'name' => 'PAPL_has_team_CARD',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Card',
    'query' => 'data/entities/Project application/linked/PAPL_has_team_CARD/%d'

  ],
  [
    'id' => 7034,
    'name' => 'ORGA_has_TAGS',
    'isTree' => false,
    'select' => 'Organisation',
    'where' => 'Tags',
    'query' => 'data/entities/Organisation/linked/ORGA_has_TAGS/%d'
  ],
  [
    'id' => 7057,
    'name' => 'PATE_has_FILE',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'File',
    'query' => 'data/entities/cfResPat/linked/PATE_has_FILE/%d'

  ],
  [
    'id' => 7080,
    'name' => 'PATE_has_AREA',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Area',
    'query' => 'data/entities/cfResPat/linked/PATE_has_AREA/%d'
  ],
  [
    'id' => 7103,
    'name' => 'PATE_has_PUBL',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Publication',
    'query' => 'data/entities/cfResPat/linked/PATE_has_PUBL/%d'
  ],
  [
    'id' => 7126,
    'name' => 'LICE_has_EXRA',
    'isTree' => false,
    'description' => 'License has exchange rate',
    'select' => 'License',
    'where' => 'ExchangeRate',
    'query' => 'data/entities/License/linked/LICE_has_EXRA/%d'
  ],
  [
    'id' => 7172,
    'name' => 'REGI_has_AGREEMENT_FILE',
    'isTree' => false,
    'select' => 'Registration',
    'where' => 'File',
    'query' => 'data/entities/Registration/linked/REGI_has_AGREEMENT_FILE/%d'
  ],
  [
    'id' => 7218,
    'name' => 'DDC_has_child_DDC',
    'isTree' => true,
    'select' => 'DDC',
    'where' => 'DDC',
    'query' => 'data/entities/DDC/linked/DDC_has_child_DDC/%d'
  ],
  [
    'id' => 7241,
    'name' => 'CIPC_has_child_CIPC',
    'isTree' => true,
    'select' => 'CIPCode',
    'where' => 'CIPCode',
    'query' => 'data/entities/CIPCode/linked/CIPC_has_child_CIPC/%d'
  ],
  [
    'id' => 7264,
    'name' => 'INDI_has_PROJ',
    'isTree' => false,
    'select' => 'InventionDisc',
    'where' => 'Project',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_PROJ/%d'
  ],
  [
    'id' => 7333,
    'name' => 'PATA_has_PUBL',
    'isTree' => false,
    'select' => 'PatentApp',
    'where' => 'Publication',
    'query' => 'data/entities/PatentApp/linked/PATA_has_PUBL/%d'
  ],
  [
    'id' => 7356,
    'name' => 'JOUR_has_COUN',
    'isTree' => false,
    'select' => 'Journal',
    'where' => 'Country',
    'query' => 'data/entities/Journal/linked/JOUR_has_COUN/%d'
  ],
  [
    'id' => 7379,
    'name' => 'INDI_has_PUBL',
    'isTree' => false,
    'select' => 'InventionDisc',
    'where' => 'Publication',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_PUBL/%d'
  ],
  [
    'id' => 7402,
    'name' => 'PATA_has_PROJ',
    'isTree' => false,
    'select' => 'PatentApp',
    'where' => 'Project',
    'query' => 'data/entities/PatentApp/linked/PATA_has_PROJ/%d'
  ],
  [
    'id' => 7471,
    'name' => 'JOUR_has_ISIA',
    'isTree' => false,
    'select' => 'Journal',
    'where' => 'ISISubjCat',
    'query' => 'data/entities/Journal/linked/JOUR_has_ISIA/%d'
  ],
  [
    'id' => 7494,
    'name' => 'JOUR_has_LANG',
    'isTree' => false,
    'select' => 'Journal',
    'where' => 'cfLang',
    'query' => 'data/entities/Journal/linked/JOUR_has_LANG/%d'
  ],
  [
    'id' => 7540,
    'name' => 'EVEN_has_CIPC',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'CIPCode',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_CIPC/%d'
  ],
  [
    'id' => 7563,
    'name' => 'GRAD_has_CIPC',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'CIPCode',
    'query' => 'data/entities/Graduation/linked/GRAD_has_CIPC/%d'
  ],
  [
    'id' => 7586,
    'name' => 'STUD_has_CIPC',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'CIPCode',
    'query' => 'data/entities/Study plan/linked/STUD_has_CIPC/%d'
  ],
  [
    'id' => 7609,
    'name' => 'FACI_has_FUND',
    'isTree' => false,
    'description' => 'The funding in which the facility is based',
    'select' => 'cfFacility',
    'where' => 'cfFund',
    'query' => 'data/entities/cfFacility/linked/FACI_has_FUND/%d'
  ],
  [
    'id' => 7632,
    'name' => 'FACI_has_inf_FILE',
    'isTree' => false,
    'description' => 'Document containing further information for interested parties',
    'select' => 'cfFacility',
    'where' => 'File',
    'query' => 'data/entities/cfFacility/linked/FACI_has_inf_FILE/%d'

  ],
  [
    'id' => 7655,
    'name' => 'FACI_has_tou_FILE',
    'isTree' => false,
    'description' => 'Terms of Use',
    'select' => 'cfFacility',
    'where' => 'File',
    'query' => 'data/entities/cfFacility/linked/FACI_has_tou_FILE/%d'

  ],
  [
    'id' => 7678,
    'name' => 'FACI_has_ack_FILE',
    'isTree' => false,
    'description' => 'TExt to be used in publications to acknowledge the use of the facility',
    'select' => 'cfFacility',
    'where' => 'File',
    'query' => 'data/entities/cfFacility/linked/FACI_has_ack_FILE/%d'

  ],
  [
    'id' => 7701,
    'name' => 'FACI_has_resp_CARD',
    'isTree' => false,
    'description' => 'The responsible re3searcher associated with the facility',
    'select' => 'cfFacility',
    'where' => 'Card',
    'query' => 'data/entities/cfFacility/linked/FACI_has_resp_CARD/%d'
  ],
  [
    'id' => 7724,
    'name' => 'FACI_has_AREA',
    'isTree' => false,
    'description' => 'Research area the facility can be classified under',
    'select' => 'cfFacility',
    'where' => 'Area',
    'query' => 'data/entities/cfFacility/linked/FACI_has_AREA/%d'
  ],
  [
    'id' => 7747,
    'name' => 'FACI_has_PUBL',
    'isTree' => false,
    'description' => 'Any publications related to the facility',
    'select' => 'cfFacility',
    'where' => 'Publication',
    'query' => 'data/entities/cfFacility/linked/FACI_has_PUBL/%d'
  ],
  [
    'id' => 7770,
    'name' => 'PROJ_has_FACI',
    'isTree' => false,
    'description' => 'Project that were assoiciated with the setup or operation of the facility',
    'select' => 'Project',
    'where' => 'cfFacility',
    'query' => 'data/entities/Project/linked/PROJ_has_FACI/%d'
  ],
  [
    'id' => 7793,
    'name' => 'INDI_has_FILE',
    'isTree' => false,
    'description' => 'Any supporting documents',
    'select' => 'InventionDisc',
    'where' => 'File',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_FILE/%d'

  ],
  [
    'id' => 7816,
    'name' => 'INDI_has_prior_INDI',
    'isTree' => false,
    'description' => 'Invention has prior invention',
    'select' => 'InventionDisc',
    'where' => 'InventionDisc',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_prior_INDI/%d'
  ],
  [
    'id' => 7839,
    'name' => 'INDI_has_AREA',
    'isTree' => false,
    'select' => 'InventionDisc',
    'where' => 'Area',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_AREA/%d'
  ],
  [
    'id' => 7862,
    'name' => 'PATA_has_inv_CARD',
    'isTree' => false,
    'description' => 'inventor(s) minimum 1',
    'select' => 'PatentApp',
    'where' => 'Card',
    'query' => 'data/entities/PatentApp/linked/PATA_has_inv_CARD/%d'

  ],
  [
    'id' => 7885,
    'name' => 'PATA_has_app_CARD',
    'isTree' => false,
    'description' => 'One applicant (normally official contact of organisation)',
    'select' => 'PatentApp',
    'where' => 'Card',
    'query' => 'data/entities/PatentApp/linked/PATA_has_app_CARD/%d'
  ],
  [
    'id' => 7908,
    'name' => 'PATA_has_FILE',
    'isTree' => false,
    'description' => 'Relevant documents ',
    'select' => 'PatentApp',
    'where' => 'File',
    'query' => 'data/entities/PatentApp/linked/PATA_has_FILE/%d'

  ],
  [
    'id' => 7931,
    'name' => 'PATA_has_INDI',
    'isTree' => false,
    'description' => 'Invention Disclosure associated with this application.',
    'select' => 'PatentApp',
    'where' => 'InventionDisc',
    'query' => 'data/entities/PatentApp/linked/PATA_has_INDI/%d'
  ],
  [
    'id' => 7954,
    'name' => 'PATA_has_sibl_PATA',
    'isTree' => false,
    'description' => 'Associated Applications when multiple applications',
    'select' => 'PatentApp',
    'where' => 'PatentApp',
    'query' => 'data/entities/PatentApp/linked/PATA_has_sibl_PATA/%d'
  ],
  [
    'id' => 7977,
    'name' => 'PATA_has_AREA',
    'isTree' => false,
    'select' => 'PatentApp',
    'where' => 'Area',
    'query' => 'data/entities/PatentApp/linked/PATA_has_AREA/%d'
  ],
  [
    'id' => 8000,
    'name' => 'PATA_has_pat_ORGA',
    'isTree' => false,
    'select' => 'PatentApp',
    'where' => 'Organisation',
    'query' => 'data/entities/PatentApp/linked/PATA_has_pat_ORGA/%d'

  ],
  [
    'id' => 8023,
    'name' => 'PATE_has_app_CARD',
    'isTree' => false,
    'description' => 'One applicant (normally official contact of organisation)',
    'select' => 'cfResPat',
    'where' => 'Card',
    'query' => 'data/entities/cfResPat/linked/PATE_has_app_CARD/%d'
  ],
  [
    'id' => 8046,
    'name' => 'PATE_has_PATA',
    'isTree' => false,
    'description' => 'Associated patent application',
    'select' => 'cfResPat',
    'where' => 'PatentApp',
    'query' => 'data/entities/cfResPat/linked/PATE_has_PATA/%d'
  ],
  [
    'id' => 8069,
    'name' => 'PATE_has_pat_ORGA',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Organisation',
    'query' => 'data/entities/cfResPat/linked/PATE_has_pat_ORGA/%d'

  ],
  [
    'id' => 8092,
    'name' => 'PROJ_has_RESR',
    'isTree' => false,
    'description' => 'Projects that were the origin of the result',
    'select' => 'Project',
    'where' => 'ResearchResult',
    'query' => 'data/entities/Project/linked/PROJ_has_RESR/%d'
  ],
  [
    'id' => 8115,
    'name' => 'RESR_has_PUBL',
    'isTree' => false,
    'description' => 'Any publications related to the result',
    'select' => 'ResearchResult',
    'where' => 'Publication',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_PUBL/%d'
  ],
  [
    'id' => 8138,
    'name' => 'RESR_has_lic_FILE',
    'isTree' => false,
    'description' => 'Terms of license for this result could be creative commons etc',
    'select' => 'ResearchResult',
    'where' => 'File',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_lic_FILE/%d'

  ],
  [
    'id' => 8161,
    'name' => 'RESR_has_sibl_RESR',
    'isTree' => false,
    'description' => 'Any other results that are related',
    'select' => 'ResearchResult',
    'where' => 'ResearchResult',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_sibl_RESR/%d'
  ],
  [
    'id' => 8184,
    'name' => 'RESR_has_TAGS',
    'isTree' => false,
    'select' => 'ResearchResult',
    'where' => 'Tags',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_TAGS/%d'
  ],
  [
    'id' => 8207,
    'name' => 'RESR_has_AREA',
    'isTree' => false,
    'description' => 'Research area the result can be classified under',
    'select' => 'ResearchResult',
    'where' => 'Area',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_AREA/%d'
  ],
  [
    'id' => 8230,
    'name' => 'RESR_has_INDI',
    'isTree' => false,
    'select' => 'ResearchResult',
    'where' => 'InventionDisc',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_INDI/%d'
  ],
  [
    'id' => 8253,
    'name' => 'RESR_has_pi_CARD',
    'isTree' => false,
    'description' => 'The PI associated with the research result',
    'select' => 'ResearchResult',
    'where' => 'Card',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_pi_CARD/%d'
  ],
  [
    'id' => 8276,
    'name' => 'LICE_has_own_CARD',
    'isTree' => false,
    'description' => 'Card of IPR owner',
    'select' => 'License',
    'where' => 'Card',
    'query' => 'data/entities/License/linked/LICE_has_own_CARD/%d'
  ],
  [
    'id' => 8299,
    'name' => 'RESR_has_fund_ORGA',
    'isTree' => false,
    'description' => 'The funder of the research that led to the result',
    'select' => 'ResearchResult',
    'where' => 'Organisation',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_fund_ORGA/%d'
  ],
  [
    'id' => 8322,
    'name' => 'LICE_has_AREA',
    'isTree' => false,
    'description' => 'The research areas covered by the license',
    'select' => 'License',
    'where' => 'Area',
    'query' => 'data/entities/License/linked/LICE_has_AREA/%d'
  ],
  [
    'id' => 8345,
    'name' => 'LICE_has_cont_FILE',
    'isTree' => false,
    'description' => 'The contract',
    'select' => 'License',
    'where' => 'File',
    'query' => 'data/entities/License/linked/LICE_has_cont_FILE/%d'

  ],
  [
    'id' => 8368,
    'name' => 'LICE_has_PATE',
    'isTree' => false,
    'description' => 'Any patent that is the basis for the license',
    'select' => 'License',
    'where' => 'cfResPat',
    'query' => 'data/entities/License/linked/LICE_has_PATE/%d'
  ],
  [
    'id' => 8391,
    'name' => 'LICE_has_RESR',
    'isTree' => false,
    'description' => 'Link to any relevant research results from which license arises or which it includes access to',
    'select' => 'License',
    'where' => 'ResearchResult',
    'query' => 'data/entities/License/linked/LICE_has_RESR/%d'
  ],
  [
    'id' => 8414,
    'name' => 'INDI_has_TAGS',
    'isTree' => false,
    'select' => 'InventionDisc',
    'where' => 'Tags',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_TAGS/%d'
  ],
  [
    'id' => 8437,
    'name' => 'PATA_has_TAGS',
    'isTree' => false,
    'select' => 'PatentApp',
    'where' => 'Tags',
    'query' => 'data/entities/PatentApp/linked/PATA_has_TAGS/%d'
  ],
  [
    'id' => 8460,
    'name' => 'PATE_has_sibl_PATE',
    'isTree' => false,
    'description' => 'Associated patens when multiple patents',
    'select' => 'cfResPat',
    'where' => 'cfResPat',
    'query' => 'data/entities/cfResPat/linked/PATE_has_sibl_PATE/%d'
  ],
  [
    'id' => 8483,
    'name' => 'LICE_has_TAGS',
    'isTree' => false,
    'select' => 'License',
    'where' => 'Tags',
    'query' => 'data/entities/License/linked/LICE_has_TAGS/%d'
  ],
  [
    'id' => 8506,
    'name' => 'COOP_has_TAGS',
    'isTree' => false,
    'select' => 'cooperation',
    'where' => 'Tags',
    'query' => 'data/entities/cooperation/linked/COOP_has_TAGS/%d'
  ],
  [
    'id' => 8529,
    'name' => 'COOP_has_AREA',
    'isTree' => false,
    'description' => 'Science classification',
    'select' => 'cooperation',
    'where' => 'Area',
    'query' => 'data/entities/cooperation/linked/COOP_has_AREA/%d'
  ],
  [
    'id' => 8552,
    'name' => 'COOP_has_EXTE',
    'isTree' => false,
    'description' => 'Extension period of a cooperation',
    'select' => 'cooperation',
    'where' => 'Extension',
    'query' => 'data/entities/cooperation/linked/COOP_has_EXTE/%d'
  ],
  [
    'id' => 8575,
    'name' => 'COOP_has_pre_COOP',
    'isTree' => false,
    'select' => 'cooperation',
    'where' => 'cooperation',
    'query' => 'data/entities/cooperation/linked/COOP_has_pre_COOP/%d'
  ],
  [
    'id' => 8598,
    'name' => 'COOP_has_fund_ORGA',
    'isTree' => false,
    'select' => 'cooperation',
    'where' => 'Organisation',
    'query' => 'data/entities/cooperation/linked/COOP_has_fund_ORGA/%d'
  ],
  [
    'id' => 8621,
    'name' => 'COOP_has_ext_ORGA',
    'isTree' => false,
    'description' => 'Link to the external partner organisation',
    'select' => 'cooperation',
    'where' => 'Organisation',
    'query' => 'data/entities/cooperation/linked/COOP_has_ext_ORGA/%d'

  ],
  [
    'id' => 8644,
    'name' => 'COOP_has_resp_ORGA',
    'isTree' => false,
    'description' => 'Link to the responsible organisation',
    'select' => 'cooperation',
    'where' => 'Organisation',
    'query' => 'data/entities/cooperation/linked/COOP_has_resp_ORGA/%d'
  ],
  [
    'id' => 8667,
    'name' => 'COOP_has_resp_CARD',
    'isTree' => false,
    'description' => 'Responsible person',
    'select' => 'cooperation',
    'where' => 'Card',
    'query' => 'data/entities/cooperation/linked/COOP_has_resp_CARD/%d'
  ],
  [
    'id' => 8690,
    'name' => 'COOP_has_cont_CARD',
    'isTree' => false,
    'description' => 'Link to the contact person',
    'select' => 'cooperation',
    'where' => 'Card',
    'query' => 'data/entities/cooperation/linked/COOP_has_cont_CARD/%d'

  ],
  [
    'id' => 8713,
    'name' => 'FACI_has_USGE',
    'isTree' => false,
    'select' => 'cfFacility',
    'where' => 'Usage',
    'query' => 'data/entities/cfFacility/linked/FACI_has_USGE/%d'
  ],
  [
    'id' => 8736,
    'name' => 'FACI_has_cont_CARD',
    'isTree' => false,
    'select' => 'cfFacility',
    'where' => 'Card',
    'query' => 'data/entities/cfFacility/linked/FACI_has_cont_CARD/%d'
  ],
  [
    'id' => 8759,
    'name' => 'USGE_has_PROJ',
    'isTree' => false,
    'description' => 'The project which was using the service or equipment',
    'select' => 'Usage',
    'where' => 'Project',
    'query' => 'data/entities/Usage/linked/USGE_has_PROJ/%d'
  ],
  [
    'id' => 8782,
    'name' => 'USGE_has_RESR',
    'isTree' => false,
    'description' => 'The Research result that generated that make use of the equipment and\/or service',
    'select' => 'Usage',
    'where' => 'ResearchResult',
    'query' => 'data/entities/Usage/linked/USGE_has_RESR/%d'
  ],
  [
    'id' => 8805,
    'name' => 'USGE_has_PUBL',
    'isTree' => false,
    'description' => 'The publications generated that make use of the equipment and\/or service',
    'select' => 'Usage',
    'where' => 'Publication',
    'query' => 'data/entities/Usage/linked/USGE_has_PUBL/%d'
  ],
  [
    'id' => 8828,
    'name' => 'USGE_has_res_CARD',
    'isTree' => false,
    'description' => 'The investigators that were part of the team using the facility',
    'select' => 'Usage',
    'where' => 'Card',
    'query' => 'data/entities/Usage/linked/USGE_has_res_CARD/%d'
  ],
  [
    'id' => 8851,
    'name' => 'COOP_has_rej_EVEN',
    'isTree' => false,
    'description' => 'Rejected event activity (requested by owner)',
    'select' => 'cooperation',
    'where' => 'cfEvent',
    'query' => 'data/entities/cooperation/linked/COOP_has_rej_EVEN/%d'
  ],
  [
    'id' => 8874,
    'name' => 'COOP_has_TASK',
    'isTree' => false,
    'description' => 'Tasks',
    'select' => 'cooperation',
    'where' => 'Task',
    'query' => 'data/entities/cooperation/linked/COOP_has_TASK/%d'
  ],
  [
    'id' => 8897,
    'name' => 'COOP_has_EVEN',
    'isTree' => false,
    'description' => 'Related Events',
    'select' => 'cooperation',
    'where' => 'cfEvent',
    'query' => 'data/entities/cooperation/linked/COOP_has_EVEN/%d'
  ],
  [
    'id' => 8920,
    'name' => 'COOP_has_cl_EVEN',
    'isTree' => false,
    'description' => 'equested event relations (by owner)',
    'select' => 'cooperation',
    'where' => 'cfEvent',
    'query' => 'data/entities/cooperation/linked/COOP_has_cl_EVEN/%d'
  ],
  [
    'id' => 8943,
    'name' => 'COOP_has_cl_ACTI',
    'isTree' => false,
    'description' => 'Requested activity relations (by owner)',
    'select' => 'cooperation',
    'where' => 'Activity',
    'query' => 'data/entities/cooperation/linked/COOP_has_cl_ACTI/%d'
  ],
  [
    'id' => 8966,
    'name' => 'COOP_has_rej_ACTI',
    'isTree' => false,
    'description' => 'Rejected activity activity (requested by owner)',
    'select' => 'cooperation',
    'where' => 'Activity',
    'query' => 'data/entities/cooperation/linked/COOP_has_rej_ACTI/%d'
  ],
  [
    'id' => 8989,
    'name' => 'COOP_has_rej_PATE',
    'isTree' => false,
    'description' => 'Rejected patent activity (requested by owner)',
    'select' => 'cooperation',
    'where' => 'cfResPat',
    'query' => 'data/entities/cooperation/linked/COOP_has_rej_PATE/%d'
  ],
  [
    'id' => 9012,
    'name' => 'COOP_has_ACTI',
    'isTree' => false,
    'description' => 'Related activities',
    'select' => 'cooperation',
    'where' => 'Activity',
    'query' => 'data/entities/cooperation/linked/COOP_has_ACTI/%d'
  ],
  [
    'id' => 9035,
    'name' => 'COOP_has_PATE',
    'isTree' => false,
    'description' => 'Related Patents',
    'select' => 'cooperation',
    'where' => 'cfResPat',
    'query' => 'data/entities/cooperation/linked/COOP_has_PATE/%d'
  ],
  [
    'id' => 9058,
    'name' => 'COOP_has_cl_PATE',
    'isTree' => false,
    'description' => 'Requested patent relations (by patent responsible)',
    'select' => 'cooperation',
    'where' => 'cfResPat',
    'query' => 'data/entities/cooperation/linked/COOP_has_cl_PATE/%d'
  ],
  [
    'id' => 9081,
    'name' => 'COOP_has_cl_PUBL',
    'isTree' => false,
    'description' => 'Requested publication relations (by author)',
    'select' => 'cooperation',
    'where' => 'Publication',
    'query' => 'data/entities/cooperation/linked/COOP_has_cl_PUBL/%d'
  ],
  [
    'id' => 9104,
    'name' => 'COOP_has_rej_PUBL',
    'isTree' => false,
    'description' => 'Rejected publication relations (requested by author)',
    'select' => 'cooperation',
    'where' => 'Publication',
    'query' => 'data/entities/cooperation/linked/COOP_has_rej_PUBL/%d'
  ],
  [
    'id' => 9127,
    'name' => 'COOP_has_rej_PROJ',
    'isTree' => false,
    'description' => 'Rejected project relations (requested by principal investigator)',
    'select' => 'cooperation',
    'where' => 'Project',
    'query' => 'data/entities/cooperation/linked/COOP_has_rej_PROJ/%d'
  ],
  [
    'id' => 9150,
    'name' => 'COOP_has_PUBL',
    'isTree' => false,
    'description' => 'Related publications',
    'select' => 'cooperation',
    'where' => 'Publication',
    'query' => 'data/entities/cooperation/linked/COOP_has_PUBL/%d'
  ],
  [
    'id' => 9173,
    'name' => 'COOP_has_PROJ',
    'isTree' => false,
    'description' => 'Projects belonging to the cooperation',
    'select' => 'cooperation',
    'where' => 'Project',
    'query' => 'data/entities/cooperation/linked/COOP_has_PROJ/%d'
  ],
  [
    'id' => 9196,
    'name' => 'COOP_has_cl_PROJ',
    'isTree' => false,
    'description' => 'Projects that may belong to cooperation (to be confirmed by the researcher)',
    'select' => 'cooperation',
    'where' => 'Project',
    'query' => 'data/entities/cooperation/linked/COOP_has_cl_PROJ/%d'
  ],
  [
    'id' => 9219,
    'name' => 'EQUI_has_RESR',
    'isTree' => false,
    'description' => 'The research results generated by the equipment',
    'select' => 'cfEquipment',
    'where' => 'ResearchResult',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_RESR/%d'
  ],
  [
    'id' => 9242,
    'name' => 'EQUI_has_AREA',
    'isTree' => false,
    'description' => 'Research area the facility can be classified under',
    'select' => 'cfEquipment',
    'where' => 'Area',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_AREA/%d'
  ],
  [
    'id' => 9265,
    'name' => 'EQUI_has_cont_CARD',
    'isTree' => false,
    'description' => 'The PI associated with the equipment',
    'select' => 'cfEquipment',
    'where' => 'Card',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_cont_CARD/%d'
  ],
  [
    'id' => 9288,
    'name' => 'EQUI_has_resp_CARD',
    'isTree' => false,
    'description' => 'The researcher responsible for the equipment',
    'select' => 'cfEquipment',
    'where' => 'Card',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_resp_CARD/%d'
  ],
  [
    'id' => 9311,
    'name' => 'PROJ_has_EQUI',
    'isTree' => false,
    'description' => 'Projects that were associated with the equipment',
    'select' => 'Project',
    'where' => 'cfEquipment',
    'query' => 'data/entities/Project/linked/PROJ_has_EQUI/%d'
  ],
  [
    'id' => 9334,
    'name' => 'EQUI_has_FUND',
    'isTree' => false,
    'description' => 'The funding that led to the equipment',
    'select' => 'cfEquipment',
    'where' => 'cfFund',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_FUND/%d'
  ],
  [
    'id' => 9357,
    'name' => 'FACI_has_EQUI',
    'isTree' => false,
    'description' => 'Related Facility',
    'select' => 'cfFacility',
    'where' => 'cfEquipment',
    'query' => 'data/entities/cfFacility/linked/FACI_has_EQUI/%d'
  ],
  [
    'id' => 9380,
    'name' => 'EQUI_has_ack_FILE',
    'isTree' => false,
    'description' => 'Text to be used in publications to acknowledge the use of the equipment',
    'select' => 'cfEquipment',
    'where' => 'File',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_ack_FILE/%d'

  ],
  [
    'id' => 9403,
    'name' => 'TAGS_has_FACI',
    'isTree' => false,
    'description' => 'Any relevant keywqords (may have to be relation)',
    'select' => 'Tags',
    'where' => 'cfFacility',
    'query' => 'data/entities/Tags/linked/TAGS_has_FACI/%d'
  ],
  [
    'id' => 9426,
    'name' => 'EQUI_has_inf_FILE',
    'isTree' => false,
    'description' => 'Document containing further information for interested parties',
    'select' => 'cfEquipment',
    'where' => 'File',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_inf_FILE/%d'

  ],
  [
    'id' => 9449,
    'name' => 'EQUI_has_tou_FILE',
    'isTree' => false,
    'description' => 'Terms of Use',
    'select' => 'cfEquipment',
    'where' => 'File',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_tou_FILE/%d'

  ],
  [
    'id' => 9472,
    'name' => 'FACI_has_host_ORGA',
    'isTree' => false,
    'description' => 'The organisation hosting the facility',
    'select' => 'cfFacility',
    'where' => 'Organisation',
    'query' => 'data/entities/cfFacility/linked/FACI_has_host_ORGA/%d'
  ],
  [
    'id' => 9495,
    'name' => 'FACI_has_ORGA',
    'isTree' => false,
    'description' => 'The facility owner',
    'select' => 'cfFacility',
    'where' => 'Organisation',
    'query' => 'data/entities/cfFacility/linked/FACI_has_ORGA/%d'
  ],
  [
    'id' => 9518,
    'name' => 'FACI_has_COUN',
    'isTree' => false,
    'description' => 'The country of the facility owner',
    'select' => 'cfFacility',
    'where' => 'Country',
    'query' => 'data/entities/cfFacility/linked/FACI_has_COUN/%d'
  ],
  [
    'id' => 9541,
    'name' => 'SERV_has_RESR',
    'isTree' => false,
    'description' => 'The research results generated by the service',
    'select' => 'cfService',
    'where' => 'ResearchResult',
    'query' => 'data/entities/cfService/linked/SERV_has_RESR/%d'
  ],
  [
    'id' => 9564,
    'name' => 'TAGS_has_SERV',
    'isTree' => false,
    'description' => 'Any relevant keywords that describe the service',
    'select' => 'Tags',
    'where' => 'cfService',
    'query' => 'data/entities/Tags/linked/TAGS_has_SERV/%d'
  ],
  [
    'id' => 9587,
    'name' => 'SERV_has_USGE',
    'isTree' => false,
    'description' => 'The usage instances of the service by int. or ext. researchers',
    'select' => 'cfService',
    'where' => 'Usage',
    'query' => 'data/entities/cfService/linked/SERV_has_USGE/%d'
  ],
  [
    'id' => 9610,
    'name' => 'EQUI_has_USGE',
    'isTree' => false,
    'description' => 'The usage instance of the equipment by int. or ext. researchers',
    'select' => 'cfEquipment',
    'where' => 'Usage',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_USGE/%d'
  ],
  [
    'id' => 9633,
    'name' => 'EQUI_has_SERV',
    'isTree' => false,
    'description' => 'Any equipment related to the service',
    'select' => 'cfEquipment',
    'where' => 'cfService',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_SERV/%d'
  ],
  [
    'id' => 9656,
    'name' => 'SERV_has_AREA',
    'isTree' => false,
    'description' => 'Research area the service can be classified under',
    'select' => 'cfService',
    'where' => 'Area',
    'query' => 'data/entities/cfService/linked/SERV_has_AREA/%d'
  ],
  [
    'id' => 9679,
    'name' => 'SERV_has_pi_CARD',
    'isTree' => false,
    'description' => 'The PI associated with the service',
    'select' => 'cfService',
    'where' => 'Card',
    'query' => 'data/entities/cfService/linked/SERV_has_pi_CARD/%d'
  ],
  [
    'id' => 9702,
    'name' => 'SERV_has_cont_CARD',
    'isTree' => false,
    'description' => 'The admin associated with the service',
    'select' => 'cfService',
    'where' => 'Card',
    'query' => 'data/entities/cfService/linked/SERV_has_cont_CARD/%d'
  ],
  [
    'id' => 9725,
    'name' => 'SERV_has_FUND',
    'isTree' => false,
    'description' => 'The funding that supports the service',
    'select' => 'cfService',
    'where' => 'cfFund',
    'query' => 'data/entities/cfService/linked/SERV_has_FUND/%d'
  ],
  [
    'id' => 9748,
    'name' => 'PROJ_has_SERV',
    'isTree' => false,
    'description' => 'Projects that were associated with the service',
    'select' => 'Project',
    'where' => 'cfService',
    'query' => 'data/entities/Project/linked/PROJ_has_SERV/%d'
  ],
  [
    'id' => 9771,
    'name' => 'SERV_has_PUBL',
    'isTree' => false,
    'description' => 'Any publications related to the service',
    'select' => 'cfService',
    'where' => 'Publication',
    'query' => 'data/entities/cfService/linked/SERV_has_PUBL/%d'
  ],
  [
    'id' => 9794,
    'name' => 'FACI_has_SERV',
    'isTree' => false,
    'description' => 'Any facility related to the service',
    'select' => 'cfFacility',
    'where' => 'cfService',
    'query' => 'data/entities/cfFacility/linked/FACI_has_SERV/%d'
  ],
  [
    'id' => 9817,
    'name' => 'TAGS_has_EQUI',
    'isTree' => false,
    'description' => 'Any relevant keywords to describe the equipment',
    'select' => 'Tags',
    'where' => 'cfEquipment',
    'query' => 'data/entities/Tags/linked/TAGS_has_EQUI/%d'
  ],
  [
    'id' => 9840,
    'name' => 'SERV_has_ack_FILE',
    'isTree' => false,
    'description' => 'Text to be used in publications to acknowledge the use of the service',
    'select' => 'cfService',
    'where' => 'File',
    'query' => 'data/entities/cfService/linked/SERV_has_ack_FILE/%d'

  ],
  [
    'id' => 9863,
    'name' => 'SERV_has_tou_FILE',
    'isTree' => false,
    'description' => 'Terms of Use',
    'select' => 'cfService',
    'where' => 'File',
    'query' => 'data/entities/cfService/linked/SERV_has_tou_FILE/%d'

  ],
  [
    'id' => 9886,
    'name' => 'SERV_has_inf_FILE',
    'isTree' => false,
    'description' => 'Document containing further information for interested parties',
    'select' => 'cfService',
    'where' => 'File',
    'query' => 'data/entities/cfService/linked/SERV_has_inf_FILE/%d'

  ],
  [
    'id' => 9909,
    'name' => 'DFGA_has_child_DFGA',
    'isTree' => true,
    'select' => 'DFGArea',
    'where' => 'DFGArea',
    'query' => 'data/entities/DFGArea/linked/DFGA_has_child_DFGA/%d'
  ],
  [
    'id' => 9932,
    'name' => 'PATE_has_SERV',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'cfService',
    'query' => 'data/entities/cfResPat/linked/PATE_has_SERV/%d'
  ],
  [
    'id' => 9955,
    'name' => 'PATE_has_EQUI',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'cfEquipment',
    'query' => 'data/entities/cfResPat/linked/PATE_has_EQUI/%d'
  ],
  [
    'id' => 9978,
    'name' => 'PAPL_has_SERV',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'cfService',
    'query' => 'data/entities/Project application/linked/PAPL_has_SERV/%d'
  ],
  [
    'id' => 10001,
    'name' => 'DFGA_has_PICT',
    'isTree' => false,
    'select' => 'DFGArea',
    'where' => 'Picture',
    'query' => 'data/entities/DFGArea/linked/DFGA_has_PICT/%d'

  ],
  [
    'id' => 10024,
    'name' => 'DFGA_has_FILE',
    'isTree' => false,
    'select' => 'DFGArea',
    'where' => 'File',
    'query' => 'data/entities/DFGArea/linked/DFGA_has_FILE/%d'

  ],
  [
    'id' => 10047,
    'name' => 'PATE_has_PAPL',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Project application',
    'query' => 'data/entities/cfResPat/linked/PATE_has_PAPL/%d'
  ],
  [
    'id' => 10070,
    'name' => 'PROJ_has_COUN',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Country',
    'query' => 'data/entities/Project/linked/PROJ_has_COUN/%d'
  ],
  [
    'id' => 10093,
    'name' => 'PAPL_has_pre_PROJ',
    'isTree' => false,
    'description' => 'Vorprojekte',
    'select' => 'Project application',
    'where' => 'Project',
    'query' => 'data/entities/Project application/linked/PAPL_has_pre_PROJ/%d'
  ],
  [
    'id' => 10116,
    'name' => 'PAPL_has_COUN',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Country',
    'query' => 'data/entities/Project application/linked/PAPL_has_COUN/%d'
  ],
  [
    'id' => 10139,
    'name' => 'PATE_has_FACI',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'cfFacility',
    'query' => 'data/entities/cfResPat/linked/PATE_has_FACI/%d'
  ],
  [
    'id' => 10162,
    'name' => 'PATE_has_COUN',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Country',
    'query' => 'data/entities/cfResPat/linked/PATE_has_COUN/%d'
  ],
  [
    'id' => 10185,
    'name' => 'PATE_has_ORGA',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Organisation',
    'query' => 'data/entities/cfResPat/linked/PATE_has_ORGA/%d'
  ],
  [
    'id' => 10208,
    'name' => 'STEX_has_ORGA',
    'isTree' => false,
    'select' => 'staffExchange',
    'where' => 'Organisation',
    'query' => 'data/entities/staffExchange/linked/STEX_has_ORGA/%d'
  ],
  [
    'id' => 10231,
    'name' => 'COOP_has_STEX',
    'isTree' => false,
    'select' => 'cooperation',
    'where' => 'staffExchange',
    'query' => 'data/entities/cooperation/linked/COOP_has_STEX/%d'
  ],
  [
    'id' => 10254,
    'name' => 'PROJ_has_child_PROJ',
    'isTree' => false,
    'description' => 'Weiterführende Projekte',
    'select' => 'Project',
    'where' => 'Project',
    'query' => 'data/entities/Project/linked/PROJ_has_child_PROJ/%d'
  ],
  [
    'id' => 10277,
    'name' => 'PROJ_has_lead_ORGA',
    'isTree' => false,
    'description' => 'Projektträger',
    'select' => 'Project',
    'where' => 'Organisation',
    'query' => 'data/entities/Project/linked/PROJ_has_lead_ORGA/%d'
  ],
  [
    'id' => 10300,
    'name' => 'PROJ_has_pre_PROJ',
    'isTree' => false,
    'description' => 'Vorgängerprojekte',
    'select' => 'Project',
    'where' => 'Project',
    'query' => 'data/entities/Project/linked/PROJ_has_pre_PROJ/%d'
  ],
  [
    'id' => 10323,
    'name' => 'ACTI_has_GRAD',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Graduation',
    'query' => 'data/entities/Activity/linked/ACTI_has_GRAD/%d'
  ],
  [
    'id' => 10346,
    'name' => 'ACTI_has_AREA',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Area',
    'query' => 'data/entities/Activity/linked/ACTI_has_AREA/%d'
  ],
  [
    'id' => 10369,
    'name' => 'ACTI_has_young_AWAR',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Award',
    'query' => 'data/entities/Activity/linked/ACTI_has_young_AWAR/%d'
  ],
  [
    'id' => 10392,
    'name' => 'ADMI_has_dis_LANG',
    'isTree' => false,
    'description' => 'In welcher Sprache wird die Dissertation eingereicht',
    'select' => 'Admission',
    'where' => 'cfLang',
    'query' => 'data/entities/Admission/linked/ADMI_has_dis_LANG/%d'
  ],
  [
    'id' => 10415,
    'name' => 'ADMI_has_exp_FILE',
    'isTree' => false,
    'description' => 'Exposé für Uni',
    'select' => 'Admission',
    'where' => 'File',
    'query' => 'data/entities/Admission/linked/ADMI_has_exp_FILE/%d'

  ],
  [
    'id' => 10438,
    'name' => 'ADMI_has_ACTI',
    'isTree' => false,
    'description' => 'Hochschulabschluss',
    'select' => 'Admission',
    'where' => 'Activity',
    'query' => 'data/entities/Admission/linked/ADMI_has_ACTI/%d'
  ],
  [
    'id' => 10461,
    'name' => 'ADMI_has_PROJ',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'Project',
    'query' => 'data/entities/Admission/linked/ADMI_has_PROJ/%d'
  ],
  [
    'id' => 10484,
    'name' => 'COOP_has_FUND',
    'isTree' => false,
    'description' => 'Finanzierungsprogramm',
    'select' => 'cooperation',
    'where' => 'cfFund',
    'query' => 'data/entities/cooperation/linked/COOP_has_FUND/%d'
  ],
  [
    'id' => 10507,
    'name' => 'COOP_has_other_FILE',
    'isTree' => false,
    'description' => 'Other documents',
    'select' => 'cooperation',
    'where' => 'File',
    'query' => 'data/entities/cooperation/linked/COOP_has_other_FILE/%d'
  ],
  [
    'id' => 10530,
    'name' => 'COOP_has_agree_FILE',
    'isTree' => false,
    'description' => 'Agreement files',
    'select' => 'cooperation',
    'where' => 'File',
    'query' => 'data/entities/cooperation/linked/COOP_has_agree_FILE/%d'

  ],
  [
    'id' => 10553,
    'name' => 'ACTI_has_PUBL',
    'isTree' => false,
    'description' => '[X] Any publications related to the Activity',
    'select' => 'Activity',
    'where' => 'Publication',
    'query' => 'data/entities/Activity/linked/ACTI_has_PUBL/%d'
  ],
  [
    'id' => 10576,
    'name' => 'ACTI_has_COUN',
    'isTree' => false,
    'description' => 'Country in which the Editorial Board is located.',
    'select' => 'Activity',
    'where' => 'Country',
    'query' => 'data/entities/Activity/linked/ACTI_has_COUN/%d'
  ],
  [
    'id' => 10599,
    'name' => 'ACTI_has_ORGA',
    'isTree' => false,
    'description' => 'Organisation',
    'select' => 'Activity',
    'where' => 'Organisation',
    'query' => 'data/entities/Activity/linked/ACTI_has_ORGA/%d'
  ],
  [
    'id' => 10622,
    'name' => 'ACTI_has_AWAR',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Award',
    'query' => 'data/entities/Activity/linked/ACTI_has_AWAR/%d'
  ],
  [
    'id' => 10645,
    'name' => 'ACTI_has_PATE',
    'isTree' => false,
    'description' => 'Relatierte Patente',
    'select' => 'Activity',
    'where' => 'cfResPat',
    'query' => 'data/entities/Activity/linked/ACTI_has_PATE/%d'
  ],
  [
    'id' => 10668,
    'name' => 'ACTI_has_PROJ',
    'isTree' => false,
    'description' => 'Relatierte Projekte',
    'select' => 'Activity',
    'where' => 'Project',
    'query' => 'data/entities/Activity/linked/ACTI_has_PROJ/%d'
  ],
  [
    'id' => 10691,
    'name' => 'GRAD_has_TAGS',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Tags',
    'query' => 'data/entities/Graduation/linked/GRAD_has_TAGS/%d'
  ],
  [
    'id' => 10714,
    'name' => 'GRAD_has_EVEN',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'cfEvent',
    'query' => 'data/entities/Graduation/linked/GRAD_has_EVEN/%d'
  ],
  [
    'id' => 10737,
    'name' => 'GRDP_has_appl_CARD',
    'isTree' => false,
    'select' => 'Graduate Programs',
    'where' => 'Card',
    'query' => 'data/entities/Graduate Programs/linked/GRDP_has_appl_CARD/%d'
  ],
  [
    'id' => 10760,
    'name' => 'GRDP_has_spea_CARD',
    'isTree' => false,
    'select' => 'Graduate Programs',
    'where' => 'Card',
    'query' => 'data/entities/Graduate Programs/linked/GRDP_has_spea_CARD/%d'
  ],
  [
    'id' => 10783,
    'name' => 'GRDP_has_stud_CARD',
    'isTree' => false,
    'select' => 'Graduate Programs',
    'where' => 'Card',
    'query' => 'data/entities/Graduate Programs/linked/GRDP_has_stud_CARD/%d'
  ],
  [
    'id' => 10806,
    'name' => 'GRDP_has_CARD',
    'isTree' => false,
    'select' => 'Graduate Programs',
    'where' => 'Card',
    'query' => 'data/entities/Graduate Programs/linked/GRDP_has_CARD/%d'
  ],
  [
    'id' => 10829,
    'name' => 'GRDP_has_EVEN',
    'isTree' => false,
    'select' => 'Graduate Programs',
    'where' => 'cfEvent',
    'query' => 'data/entities/Graduate Programs/linked/GRDP_has_EVEN/%d'
  ],
  [
    'id' => 10852,
    'name' => 'GRAD_has_EXTE',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Extension',
    'query' => 'data/entities/Graduation/linked/GRAD_has_EXTE/%d'
  ],
  [
    'id' => 10875,
    'name' => 'EQUI_has_PAPL',
    'isTree' => false,
    'select' => 'cfEquipment',
    'where' => 'Project application',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_PAPL/%d'
  ],
  [
    'id' => 10898,
    'name' => 'EQUI_has_ORGA',
    'isTree' => false,
    'select' => 'cfEquipment',
    'where' => 'Organisation',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_ORGA/%d'
  ],
  [
    'id' => 10921,
    'name' => 'EQUI_has_ACTI',
    'isTree' => false,
    'select' => 'cfEquipment',
    'where' => 'Activity',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_ACTI/%d'
  ],
  [
    'id' => 10944,
    'name' => 'PAPL_has_FACI',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'cfFacility',
    'query' => 'data/entities/Project application/linked/PAPL_has_FACI/%d'
  ],
  [
    'id' => 10967,
    'name' => 'GRAD_has_PROJ',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Project',
    'query' => 'data/entities/Graduation/linked/GRAD_has_PROJ/%d'
  ],
  [
    'id' => 10990,
    'name' => 'GRAD_has_AWAR',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Award',
    'query' => 'data/entities/Graduation/linked/GRAD_has_AWAR/%d'
  ],
  [
    'id' => 11013,
    'name' => 'GRAD_has_PUBL',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Publication',
    'query' => 'data/entities/Graduation/linked/GRAD_has_PUBL/%d'
  ],
  [
    'id' => 11036,
    'name' => 'FUDR_has_CSFL',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'Cash Flow',
    'query' => 'data/entities/Funder/linked/FUDR_has_CSFL/%d'
  ],
  [
    'id' => 11059,
    'name' => 'FUDR_has_EXFU',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'external Funds',
    'query' => 'data/entities/Funder/linked/FUDR_has_EXFU/%d'
  ],
  [
    'id' => 11082,
    'name' => 'FUDR_has_PAPL',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'Project application',
    'query' => 'data/entities/Funder/linked/FUDR_has_PAPL/%d'
  ],
  [
    'id' => 11105,
    'name' => 'FUDR_has_PROJ',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'Project',
    'query' => 'data/entities/Funder/linked/FUDR_has_PROJ/%d'

  ],
  [
    'id' => 11128,
    'name' => 'FUDR_has_PATE',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'cfResPat',
    'query' => 'data/entities/Funder/linked/FUDR_has_PATE/%d'
  ],
  [
    'id' => 11151,
    'name' => 'FUDR_has_ACTI',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'Activity',
    'query' => 'data/entities/Funder/linked/FUDR_has_ACTI/%d'
  ],
  [
    'id' => 11174,
    'name' => 'GRAD_has_FUDR',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Funder',
    'query' => 'data/entities/Graduation/linked/GRAD_has_FUDR/%d'
  ],
  [
    'id' => 11197,
    'name' => 'WEIT_has_CSFL',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Cash Flow',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_CSFL/%d'
  ],
  [
    'id' => 11220,
    'name' => 'STAT_has_child_STAT',
    'isTree' => true,
    'select' => 'StatisticsArea',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/StatisticsArea/linked/STAT_has_child_STAT/%d'
  ],
  [
    'id' => 11243,
    'name' => 'GRDP_has_FUDR',
    'isTree' => false,
    'select' => 'Graduate Programs',
    'where' => 'Funder',
    'query' => 'data/entities/Graduate Programs/linked/GRDP_has_FUDR/%d'
  ],
  [
    'id' => 11266,
    'name' => 'PATE_has_CSFL',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'Cash Flow',
    'query' => 'data/entities/cfResPat/linked/PATE_has_CSFL/%d'
  ],
  [
    'id' => 11289,
    'name' => 'WEIT_has_FUDR',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Funder',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_FUDR/%d'
  ],
  [
    'id' => 11312,
    'name' => 'WEIT_has_TAGS',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Tags',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_TAGS/%d'
  ],
  [
    'id' => 11335,
    'name' => 'WEIT_has_PUBL',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Publication',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_PUBL/%d'
  ],
  [
    'id' => 11358,
    'name' => 'WEIT_has_SERV',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'cfService',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_SERV/%d'
  ],
  [
    'id' => 11381,
    'name' => 'WEIT_has_sibl_WEIT',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Weiterbildungsangebot',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_sibl_WEIT/%d'
  ],
  [
    'id' => 11404,
    'name' => 'TAGS_has_ACTI',
    'isTree' => false,
    'select' => 'Tags',
    'where' => 'Activity',
    'query' => 'data/entities/Tags/linked/TAGS_has_ACTI/%d'
  ],
  [
    'id' => 11427,
    'name' => 'FUDR_has_DFGA',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'DFGArea',
    'query' => 'data/entities/Funder/linked/FUDR_has_DFGA/%d'
  ],
  [
    'id' => 11450,
    'name' => 'FUDR_has_PUBL',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'Publication',
    'query' => 'data/entities/Funder/linked/FUDR_has_PUBL/%d'
  ],
  [
    'id' => 11473,
    'name' => 'HABI_has_TAGS',
    'isTree' => false,
    'description' => 'Schlagwörter',
    'select' => 'Habilitation',
    'where' => 'Tags',
    'query' => 'data/entities/Habilitation/linked/HABI_has_TAGS/%d'
  ],
  [
    'id' => 11496,
    'name' => 'WEIT_has_EQUI',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'cfEquipment',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_EQUI/%d'
  ],
  [
    'id' => 11519,
    'name' => 'WEIT_has_COUN',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Country',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_COUN/%d'
  ],
  [
    'id' => 11542,
    'name' => 'WEIT_has_AREA',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Area',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_AREA/%d'
  ],
  [
    'id' => 11565,
    'name' => 'WEIT_has_app_CARD',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Card',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_app_CARD/%d'
  ],
  [
    'id' => 11588,
    'name' => 'WEIT_has_ACTI',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Activity',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_ACTI/%d'
  ],
  [
    'id' => 11611,
    'name' => 'EMPL_has_ORGA',
    'isTree' => false,
    'select' => 'Employment',
    'where' => 'Organisation',
    'query' => 'data/entities/Employment/linked/EMPL_has_ORGA/%d'
  ],
  [
    'id' => 11634,
    'name' => 'EMPL_has_FUND',
    'isTree' => false,
    'select' => 'Employment',
    'where' => 'cfFund',
    'query' => 'data/entities/Employment/linked/EMPL_has_FUND/%d'
  ],
  [
    'id' => 11657,
    'name' => 'WEIT_has_PROJ',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Project',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_PROJ/%d'
  ],
  [
    'id' => 11680,
    'name' => 'WEIT_has_PAPL',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Project application',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_PAPL/%d'
  ],
  [
    'id' => 11703,
    'name' => 'WEIT_has_ORGA',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Organisation',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_ORGA/%d'
  ],
  [
    'id' => 11726,
    'name' => 'WEIT_has_FACI',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'cfFacility',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_FACI/%d'
  ],
  [
    'id' => 11749,
    'name' => 'EXFU_has_FUND',
    'isTree' => false,
    'select' => 'external Funds',
    'where' => 'cfFund',
    'query' => 'data/entities/external Funds/linked/EXFU_has_FUND/%d'
  ],
  [
    'id' => 11772,
    'name' => 'EXFU_has_CARD',
    'isTree' => false,
    'select' => 'external Funds',
    'where' => 'Card',
    'query' => 'data/entities/external Funds/linked/EXFU_has_CARD/%d'
  ],
  [
    'id' => 11795,
    'name' => 'EXFU_has_FACI',
    'isTree' => false,
    'select' => 'external Funds',
    'where' => 'cfFacility',
    'query' => 'data/entities/external Funds/linked/EXFU_has_FACI/%d'
  ],
  [
    'id' => 11818,
    'name' => 'EXFU_has_ORGA',
    'isTree' => false,
    'select' => 'external Funds',
    'where' => 'Organisation',
    'query' => 'data/entities/external Funds/linked/EXFU_has_ORGA/%d'
  ],
  [
    'id' => 11841,
    'name' => 'EXFU_has_EQUI',
    'isTree' => false,
    'select' => 'external Funds',
    'where' => 'cfEquipment',
    'query' => 'data/entities/external Funds/linked/EXFU_has_EQUI/%d'
  ],
  [
    'id' => 11864,
    'name' => 'TAGS_has_DFGA',
    'isTree' => false,
    'select' => 'Tags',
    'where' => 'DFGArea',
    'query' => 'data/entities/Tags/linked/TAGS_has_DFGA/%d'
  ],
  [
    'id' => 11887,
    'name' => 'STUD_has_STAT',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Study plan/linked/STUD_has_STAT/%d'
  ],
  [
    'id' => 11910,
    'name' => 'TAGS_has_STAT',
    'isTree' => false,
    'select' => 'Tags',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Tags/linked/TAGS_has_STAT/%d'
  ],
  [
    'id' => 11933,
    'name' => 'WEIT_has_DFGA',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'DFGArea',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_DFGA/%d'
  ],
  [
    'id' => 11979,
    'name' => 'WEIT_has_STAT',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_STAT/%d'
  ],
  [
    'id' => 12002,
    'name' => 'WEIT_has_org_PERS',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Person',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_org_PERS/%d'
  ],
  [
    'id' => 12048,
    'name' => 'WEIT_has_eval_PERS',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'Person',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_eval_PERS/%d'
  ],
  [
    'id' => 12071,
    'name' => 'ACTI_has_adv_CARD',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Card',
    'query' => 'data/entities/Activity/linked/ACTI_has_adv_CARD/%d'
  ],
  [
    'id' => 12094,
    'name' => 'WEIT_has_CIPC',
    'isTree' => false,
    'select' => 'Weiterbildungsangebot',
    'where' => 'CIPCode',
    'query' => 'data/entities/Weiterbildungsangebot/linked/WEIT_has_CIPC/%d'
  ],
  [
    'id' => 12117,
    'name' => 'ACTI_has_STAT',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Activity/linked/ACTI_has_STAT/%d'
  ],
  [
    'id' => 12140,
    'name' => 'ACTI_has_DFGA',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'DFGArea',
    'query' => 'data/entities/Activity/linked/ACTI_has_DFGA/%d'
  ],
  [
    'id' => 12163,
    'name' => 'PATE_has_STAT',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/cfResPat/linked/PATE_has_STAT/%d'
  ],
  [
    'id' => 12232,
    'name' => 'PUBA_has_DFGA',
    'isTree' => false,
    'select' => 'Publication application',
    'where' => 'DFGArea',
    'query' => 'data/entities/Publication application/linked/PUBA_has_DFGA/%d'
  ],
  [
    'id' => 12255,
    'name' => 'PUBA_has_STAT',
    'isTree' => false,
    'select' => 'Publication application',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Publication application/linked/PUBA_has_STAT/%d'
  ],
  [
    'id' => 12278,
    'name' => 'RESR_has_DFGA',
    'isTree' => false,
    'select' => 'ResearchResult',
    'where' => 'DFGArea',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_DFGA/%d'
  ],
  [
    'id' => 12301,
    'name' => 'RESR_has_STAT',
    'isTree' => false,
    'select' => 'ResearchResult',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/ResearchResult/linked/RESR_has_STAT/%d'
  ],
  [
    'id' => 12324,
    'name' => 'SERV_has_CSFL',
    'isTree' => false,
    'select' => 'cfService',
    'where' => 'Cash Flow',
    'query' => 'data/entities/cfService/linked/SERV_has_CSFL/%d'
  ],
  [
    'id' => 12347,
    'name' => 'SERV_has_FUDR',
    'isTree' => false,
    'select' => 'cfService',
    'where' => 'Funder',
    'query' => 'data/entities/cfService/linked/SERV_has_FUDR/%d'
  ],
  [
    'id' => 12370,
    'name' => 'SERV_has_DFGA',
    'isTree' => false,
    'select' => 'cfService',
    'where' => 'DFGArea',
    'query' => 'data/entities/cfService/linked/SERV_has_DFGA/%d'
  ],
  [
    'id' => 12393,
    'name' => 'SERV_has_STAT',
    'isTree' => false,
    'select' => 'cfService',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/cfService/linked/SERV_has_STAT/%d'
  ],
  [
    'id' => 12416,
    'name' => 'SERV_has_ORGA',
    'isTree' => false,
    'select' => 'cfService',
    'where' => 'Organisation',
    'query' => 'data/entities/cfService/linked/SERV_has_ORGA/%d'
  ],
  [
    'id' => 12439,
    'name' => 'SERV_has_ACTI',
    'isTree' => false,
    'select' => 'cfService',
    'where' => 'Activity',
    'query' => 'data/entities/cfService/linked/SERV_has_ACTI/%d'
  ],
  [
    'id' => 12462,
    'name' => 'STUD_has_DFGA',
    'isTree' => false,
    'select' => 'Study plan',
    'where' => 'DFGArea',
    'query' => 'data/entities/Study plan/linked/STUD_has_DFGA/%d'
  ],
  [
    'id' => 12485,
    'name' => 'IDEA_has_STAT',
    'isTree' => false,
    'select' => 'Idea',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Idea/linked/IDEA_has_STAT/%d'
  ],
  [
    'id' => 12508,
    'name' => 'IDEA_has_DFGA',
    'isTree' => false,
    'select' => 'Idea',
    'where' => 'DFGArea',
    'query' => 'data/entities/Idea/linked/IDEA_has_DFGA/%d'
  ],
  [
    'id' => 12531,
    'name' => 'GRAD_has_STAT',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Graduation/linked/GRAD_has_STAT/%d'
  ],
  [
    'id' => 12554,
    'name' => 'GRAD_has_DFGA',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'DFGArea',
    'query' => 'data/entities/Graduation/linked/GRAD_has_DFGA/%d'
  ],
  [
    'id' => 12577,
    'name' => 'LICE_has_STAT',
    'isTree' => false,
    'select' => 'License',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/License/linked/LICE_has_STAT/%d'
  ],
  [
    'id' => 12600,
    'name' => 'LICE_has_DFGA',
    'isTree' => false,
    'select' => 'License',
    'where' => 'DFGArea',
    'query' => 'data/entities/License/linked/LICE_has_DFGA/%d'
  ],
  [
    'id' => 12623,
    'name' => 'INDI_has_STAT',
    'isTree' => false,
    'select' => 'InventionDisc',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_STAT/%d'
  ],
  [
    'id' => 12646,
    'name' => 'INDI_has_DFGA',
    'isTree' => false,
    'select' => 'InventionDisc',
    'where' => 'DFGArea',
    'query' => 'data/entities/InventionDisc/linked/INDI_has_DFGA/%d'
  ],
  [
    'id' => 12669,
    'name' => 'PAPL_has_STAT',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Project application/linked/PAPL_has_STAT/%d'
  ],
  [
    'id' => 12692,
    'name' => 'ORGA_has_STAT',
    'isTree' => false,
    'description' => '[X] Zugeordnete Kategorie der amtlichen Statisitk (Destatis)',
    'select' => 'Organisation',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Organisation/linked/ORGA_has_STAT/%d'
  ],
  [
    'id' => 12715,
    'name' => 'ORGA_has_DFGA',
    'isTree' => false,
    'description' => '[X] Zuordnung DFG-Kategorien',
    'select' => 'Organisation',
    'where' => 'DFGArea',
    'query' => 'data/entities/Organisation/linked/ORGA_has_DFGA/%d'
  ],
  [
    'id' => 12738,
    'name' => 'PATE_has_DFGA',
    'isTree' => false,
    'select' => 'cfResPat',
    'where' => 'DFGArea',
    'query' => 'data/entities/cfResPat/linked/PATE_has_DFGA/%d'
  ],
  [
    'id' => 12761,
    'name' => 'PATA_has_STAT',
    'isTree' => false,
    'select' => 'PatentApp',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/PatentApp/linked/PATA_has_STAT/%d'
  ],
  [
    'id' => 12784,
    'name' => 'PATA_has_DFGA',
    'isTree' => false,
    'select' => 'PatentApp',
    'where' => 'DFGArea',
    'query' => 'data/entities/PatentApp/linked/PATA_has_DFGA/%d'
  ],
  [
    'id' => 12807,
    'name' => 'PAPL_has_DFGA',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'DFGArea',
    'query' => 'data/entities/Project application/linked/PAPL_has_DFGA/%d'
  ],
  [
    'id' => 12830,
    'name' => 'EVEN_has_PROJ',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'Project',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_PROJ/%d'
  ],
  [
    'id' => 12853,
    'name' => 'EVEN_has_ORGA',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'Organisation',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_ORGA/%d'
  ],
  [
    'id' => 12876,
    'name' => 'STAT_has_FILE',
    'isTree' => false,
    'select' => 'StatisticsArea',
    'where' => 'File',
    'query' => 'data/entities/StatisticsArea/linked/STAT_has_FILE/%d'

  ],
  [
    'id' => 12899,
    'name' => 'STAT_has_PICT',
    'isTree' => false,
    'select' => 'StatisticsArea',
    'where' => 'Picture',
    'query' => 'data/entities/StatisticsArea/linked/STAT_has_PICT/%d'

  ],
  [
    'id' => 12922,
    'name' => 'EVEN_has_DFGA',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'DFGArea',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_DFGA/%d'
  ],
  [
    'id' => 12945,
    'name' => 'EVEN_has_STAT',
    'isTree' => false,
    'select' => 'cfEvent',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/cfEvent/linked/EVEN_has_STAT/%d'
  ],
  [
    'id' => 12968,
    'name' => 'ADMI_has_DFGA',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'DFGArea',
    'query' => 'data/entities/Admission/linked/ADMI_has_DFGA/%d'
  ],
  [
    'id' => 12991,
    'name' => 'ADMI_has_STAT',
    'isTree' => false,
    'select' => 'Admission',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Admission/linked/ADMI_has_STAT/%d'
  ],
  [
    'id' => 13014,
    'name' => 'EQUI_has_DFGA',
    'isTree' => false,
    'select' => 'cfEquipment',
    'where' => 'DFGArea',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_DFGA/%d'
  ],
  [
    'id' => 13037,
    'name' => 'EQUI_has_STAT',
    'isTree' => false,
    'select' => 'cfEquipment',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_STAT/%d'
  ],
  [
    'id' => 13060,
    'name' => 'COOP_has_DFGA',
    'isTree' => false,
    'select' => 'cooperation',
    'where' => 'DFGArea',
    'query' => 'data/entities/cooperation/linked/COOP_has_DFGA/%d'
  ],
  [
    'id' => 13083,
    'name' => 'COOP_has_STAT',
    'isTree' => false,
    'select' => 'cooperation',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/cooperation/linked/COOP_has_STAT/%d'
  ],
  [
    'id' => 13106,
    'name' => 'FUND_has_DFGA',
    'isTree' => false,
    'select' => 'cfFund',
    'where' => 'DFGArea',
    'query' => 'data/entities/cfFund/linked/FUND_has_DFGA/%d'
  ],
  [
    'id' => 13129,
    'name' => 'FUND_has_STAT',
    'isTree' => false,
    'select' => 'cfFund',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/cfFund/linked/FUND_has_STAT/%d'
  ],
  [
    'id' => 13152,
    'name' => 'FACI_has_DFGA',
    'isTree' => false,
    'select' => 'cfFacility',
    'where' => 'DFGArea',
    'query' => 'data/entities/cfFacility/linked/FACI_has_DFGA/%d'
  ],
  [
    'id' => 13175,
    'name' => 'FACI_has_STAT',
    'isTree' => false,
    'select' => 'cfFacility',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/cfFacility/linked/FACI_has_STAT/%d'
  ],
  [
    'id' => 13198,
    'name' => 'CENT_has_stat_FILE',
    'isTree' => false,
    'select' => 'Centrum',
    'where' => 'File',
    'query' => 'data/entities/Centrum/linked/CENT_has_stat_FILE/%d'

  ],
  [
    'id' => 13221,
    'name' => 'PAPL_has_cal_FILE',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_cal_FILE/%d'
  ],
  [
    'id' => 13244,
    'name' => 'PAPL_has_fca_FILE',
    'isTree' => false,
    'description' => 'Project Application has ausgefüllte Vorkalkulation',
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_fca_FILE/%d'
  ],
  [
    'id' => 13267,
    'name' => 'CODC_has_sup_CARD',
    'isTree' => false,
    'description' => 'Betreuung an der HFD',
    'select' => 'CODC',
    'where' => 'Card',
    'query' => 'data/entities/CODC/linked/CODC_has_sup_CARD/%d'

  ],
  [
    'id' => 13290,
    'name' => 'PROJ_has_CENT',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Centrum',
    'query' => 'data/entities/Project/linked/PROJ_has_CENT/%d'
  ],
  [
    'id' => 13313,
    'name' => 'PROJ_has_RENE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'researchNetwork',
    'query' => 'data/entities/Project/linked/PROJ_has_RENE/%d'
  ],
  [
    'id' => 13336,
    'name' => 'PROJ_has_FOSP',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Forschungsschwerpunkt',
    'query' => 'data/entities/Project/linked/PROJ_has_FOSP/%d'
  ],
  [
    'id' => 13359,
    'name' => 'PAPL_has_FOSP',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Forschungsschwerpunkt',
    'query' => 'data/entities/Project application/linked/PAPL_has_FOSP/%d'
  ],
  [
    'id' => 13382,
    'name' => 'PAPL_has_con_FILE',
    'isTree' => false,
    'description' => 'Project Application has Contract',
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_con_FILE/%d'

  ],
  [
    'id' => 13405,
    'name' => 'PAPL_has_dra_FILE',
    'isTree' => false,
    'description' => '[X] Project Application has draft Contract',
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_dra_FILE/%d'
  ],
  [
    'id' => 13428,
    'name' => 'PROJ_has_PICT',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Picture',
    'query' => 'data/entities/Project/linked/PROJ_has_PICT/%d'
  ],
  [
    'id' => 13451,
    'name' => 'FUTY_has_PAPL',
    'isTree' => false,
    'select' => 'FundingType',
    'where' => 'Project application',
    'query' => 'data/entities/FundingType/linked/FUTY_has_PAPL/%d'
  ],
  [
    'id' => 13497,
    'name' => 'PAPL_has_CENT',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Centrum',
    'query' => 'data/entities/Project application/linked/PAPL_has_CENT/%d'
  ],
  [
    'id' => 13520,
    'name' => 'PAPL_has_RENE',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'researchNetwork',
    'query' => 'data/entities/Project application/linked/PAPL_has_RENE/%d'
  ],
  [
    'id' => 13635,
    'name' => 'HABI_has_ORGA',
    'isTree' => false,
    'select' => 'Habilitation',
    'where' => 'Organisation',
    'query' => 'data/entities/Habilitation/linked/HABI_has_ORGA/%d'
  ],
  [
    'id' => 13658,
    'name' => 'CENT_has_EVEN',
    'isTree' => false,
    'select' => 'Centrum',
    'where' => 'cfEvent',
    'query' => 'data/entities/Centrum/linked/CENT_has_EVEN/%d'
  ],
  [
    'id' => 13681,
    'name' => 'CENT_has_memb_CARD',
    'isTree' => false,
    'description' => 'Members of the scientific centrum',
    'select' => 'Centrum',
    'where' => 'Card',
    'query' => 'data/entities/Centrum/linked/CENT_has_memb_CARD/%d'

  ],
  [
    'id' => 13704,
    'name' => 'CENT_has_scma_CARD',
    'isTree' => false,
    'description' => 'Scientific Management',
    'select' => 'Centrum',
    'where' => 'Card',
    'query' => 'data/entities/Centrum/linked/CENT_has_scma_CARD/%d'
  ],
  [
    'id' => 13727,
    'name' => 'CENT_has_AREA',
    'isTree' => false,
    'select' => 'Centrum',
    'where' => 'Area',
    'query' => 'data/entities/Centrum/linked/CENT_has_AREA/%d'
  ],
  [
    'id' => 13750,
    'name' => 'CENT_has_ORGA',
    'isTree' => false,
    'description' => 'Cooperations',
    'select' => 'Centrum',
    'where' => 'Organisation',
    'query' => 'data/entities/Centrum/linked/CENT_has_ORGA/%d'
  ],
  [
    'id' => 13773,
    'name' => 'PROJ_has_PROJ',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Project',
    'query' => 'data/entities/Project/linked/PROJ_has_PROJ/%d'
  ],
  [
    'id' => 13796,
    'name' => 'SPIN_has_team_CARD',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Card',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_team_CARD/%d'
  ],
  [
    'id' => 13819,
    'name' => 'Statute',
    'isTree' => false,
    'select' => 'Centrum',
    'where' => 'Embedded file',
    'query' => 'data/entities/Centrum/linked/Statute/%d'
  ],
  [
    'id' => 13842,
    'name' => 'GRAD_has_STUD',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Study plan',
    'query' => 'data/entities/Graduation/linked/GRAD_has_STUD/%d'
  ],
  [
    'id' => 13865,
    'name' => 'SPIN_has_PATE',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'cfResPat',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_PATE/%d'
  ],
  [
    'id' => 13888,
    'name' => 'SPIN_has_FUND',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'cfFund',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_FUND/%d'
  ],
  [
    'id' => 13911,
    'name' => 'SPIN_has_ori_ORGA',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Organisation',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_ori_ORGA/%d'
  ],
  [
    'id' => 13934,
    'name' => 'SPIN_has_cont_ORGA',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Organisation',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_cont_ORGA/%d'
  ],
  [
    'id' => 13957,
    'name' => 'SPIN_has_CARD',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Card',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_CARD/%d'
  ],
  [
    'id' => 13980,
    'name' => 'SPIN_has_inku_ORGA',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Organisation',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_inku_ORGA/%d'
  ],
  [
    'id' => 14003,
    'name' => 'SPIN_has_STAT',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_STAT/%d'
  ],
  [
    'id' => 14026,
    'name' => 'SPIN_has_TAGS',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Tags',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_TAGS/%d'
  ],
  [
    'id' => 14049,
    'name' => 'SPIN_has_PROJ',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Project',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_PROJ/%d'
  ],
  [
    'id' => 14072,
    'name' => 'SPIN_has_PUBL',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Publication',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_PUBL/%d'
  ],
  [
    'id' => 14095,
    'name' => 'SPIN_has_DFGA',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'DFGArea',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_DFGA/%d'
  ],
  [
    'id' => 14118,
    'name' => 'SPIN_has_FUDR',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Funder',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_FUDR/%d'
  ],
  [
    'id' => 14141,
    'name' => 'SPIN_has_AREA',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Area',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_AREA/%d'
  ],
  [
    'id' => 14164,
    'name' => 'SPIN_has_COUN',
    'isTree' => false,
    'select' => 'AcademicSpinoff',
    'where' => 'Country',
    'query' => 'data/entities/AcademicSpinoff/linked/SPIN_has_COUN/%d'
  ],
  [
    'id' => 14187,
    'name' => 'ACTI_has_awar_ORGA',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Organisation',
    'query' => 'data/entities/Activity/linked/ACTI_has_awar_ORGA/%d'
  ],
  [
    'id' => 14210,
    'name' => 'ACTI_has_JOUR',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Journal',
    'query' => 'data/entities/Activity/linked/ACTI_has_JOUR/%d'
  ],
  [
    'id' => 14233,
    'name' => 'ACTI_has_awar_CARD',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Card',
    'query' => 'data/entities/Activity/linked/ACTI_has_awar_CARD/%d'
  ],
  [
    'id' => 14256,
    'name' => 'EXFU_has_spea_CARD',
    'isTree' => false,
    'select' => 'external Funds',
    'where' => 'Card',
    'query' => 'data/entities/external Funds/linked/EXFU_has_spea_CARD/%d'
  ],
  [
    'id' => 14279,
    'name' => 'GRAD_has_young_AWAR',
    'isTree' => false,
    'select' => 'Graduation',
    'where' => 'Award',
    'query' => 'data/entities/Graduation/linked/GRAD_has_young_AWAR/%d'
  ],
  [
    'id' => 14302,
    'name' => 'EMPL_has_other_ORGA',
    'isTree' => false,
    'select' => 'Employment',
    'where' => 'Organisation',
    'query' => 'data/entities/Employment/linked/EMPL_has_other_ORGA/%d'
  ],
  [
    'id' => 14325,
    'name' => 'EQUI_has_PUBL',
    'isTree' => false,
    'select' => 'cfEquipment',
    'where' => 'Publication',
    'query' => 'data/entities/cfEquipment/linked/EQUI_has_PUBL/%d'
  ],
  [
    'id' => 14348,
    'name' => 'COUN_has_PERS',
    'isTree' => false,
    'select' => 'Country',
    'where' => 'Person',
    'query' => 'data/entities/Country/linked/COUN_has_PERS/%d'
  ],
  [
    'id' => 14371,
    'name' => 'FUDR_has_COUN',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'Country',
    'query' => 'data/entities/Funder/linked/FUDR_has_COUN/%d'
  ],
  [
    'id' => 14394,
    'name' => 'FUDR_has_STAT',
    'isTree' => false,
    'select' => 'Funder',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Funder/linked/FUDR_has_STAT/%d'
  ],
  [
    'id' => 14417,
    'name' => 'PUBA_has_app_CARD',
    'isTree' => false,
    'select' => 'Publication application',
    'where' => 'Card',
    'query' => 'data/entities/Publication application/linked/PUBA_has_app_CARD/%d'
  ],
  [
    'id' => 14486,
    'name' => 'PROJ_has_CSFL',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Cash Flow',
    'query' => 'data/entities/Project/linked/PROJ_has_CSFL/%d'
  ],
  [
    'id' => 14509,
    'name' => 'PROJ_has_EXFU',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'external Funds',
    'query' => 'data/entities/Project/linked/PROJ_has_EXFU/%d'
  ],
  [
    'id' => 14532,
    'name' => 'PROJ_has_STAT',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Project/linked/PROJ_has_STAT/%d'
  ],
  [
    'id' => 14555,
    'name' => 'PROJ_has_DFGA',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'DFGArea',
    'query' => 'data/entities/Project/linked/PROJ_has_DFGA/%d'
  ],
  [
    'id' => 14578,
    'name' => 'RENE_has_stat_FILE',
    'isTree' => false,
    'description' => 'Satzung',
    'select' => 'researchNetwork',
    'where' => 'File',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_stat_FILE/%d'
  ],
  [
    'id' => 14601,
    'name' => 'CENT_has_FOSP',
    'isTree' => false,
    'description' => 'Forschungsschwerpunkt der Zentren',
    'select' => 'Centrum',
    'where' => 'Forschungsschwerpunkt',
    'query' => 'data/entities/Centrum/linked/CENT_has_FOSP/%d'
  ],
  [
    'id' => 14624,
    'name' => 'GRDP_has_ext_ORGA',
    'isTree' => false,
    'description' => 'Externe Organisation, an der das Graduierten Programm angesiedelt ist',
    'select' => 'Graduate Programs',
    'where' => 'Organisation',
    'query' => 'data/entities/Graduate Programs/linked/GRDP_has_ext_ORGA/%d'
  ],
  [
    'id' => 14647,
    'name' => 'PROJ_has_year_PAYM',
    'isTree' => false,
    'description' => 'Zu einem Auftragsnummer zugeordneten Jahreseinnahmen und -ausgaben ',
    'select' => 'Project',
    'where' => 'PAYM',
    'query' => 'data/entities/Project/linked/PROJ_has_year_PAYM/%d'
  ],
  [
    'id' => 14670,
    'name' => 'CODC_has_ACTI',
    'isTree' => false,
    'description' => 'Forschungsaktivitäten wie Gutachtertätigkeiten, Vorträge etc.',
    'select' => 'CODC',
    'where' => 'Activity',
    'query' => 'data/entities/CODC/linked/CODC_has_ACTI/%d'
  ],
  [
    'id' => 14693,
    'name' => 'ACTI_has_ext_ORGA',
    'isTree' => false,
    'description' => 'Externe Organisation',
    'select' => 'Activity',
    'where' => 'Organisation',
    'query' => 'data/entities/Activity/linked/ACTI_has_ext_ORGA/%d'
  ],
  [
    'id' => 14739,
    'name' => 'ACTI_has_FOSP',
    'isTree' => false,
    'description' => 'HFD Forschungsschwerpunkt',
    'select' => 'Activity',
    'where' => 'Forschungsschwerpunkt',
    'query' => 'data/entities/Activity/linked/ACTI_has_FOSP/%d'
  ],
  [
    'id' => 14762,
    'name' => 'RENE_has_adm_CARD',
    'isTree' => false,
    'description' => 'Kontaktperson\r\n',
    'select' => 'researchNetwork',
    'where' => 'Card',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_adm_CARD/%d'
  ],
  [
    'id' => 14785,
    'name' => 'RENE_has_scma_CARD',
    'isTree' => false,
    'description' => 'Wissenschaftliche Leitung\r\n',
    'select' => 'researchNetwork',
    'where' => 'Card',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_scma_CARD/%d'
  ],
  [
    'id' => 14808,
    'name' => 'RENE_has_ORGA',
    'isTree' => false,
    'description' => 'Partnerorganisationen',
    'select' => 'researchNetwork',
    'where' => 'Organisation',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_ORGA/%d'
  ],
  [
    'id' => 14831,
    'name' => 'RENE_has_team_CARD',
    'isTree' => false,
    'description' => 'Mitglieder des Verbundes',
    'select' => 'researchNetwork',
    'where' => 'Card',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_team_CARD/%d'

  ],
  [
    'id' => 14854,
    'name' => 'RENE_has_FOSP',
    'isTree' => false,
    'description' => 'HFD Forschungsschwerpunkte',
    'select' => 'researchNetwork',
    'where' => 'Forschungsschwerpunkt',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_FOSP/%d'
  ],
  [
    'id' => 14877,
    'name' => 'CODC_has_grad_CARD',
    'isTree' => false,
    'description' => 'Graduate student's card',
    'select' => 'CODC',
    'where' => 'Card',
    'query' => 'data/entities/CODC/linked/CODC_has_grad_CARD/%d'
  ],
  [
    'id' => 15015,
    'name' => 'RENE_has_PICT',
    'isTree' => false,
    'description' => 'Logo\r\n',
    'select' => 'researchNetwork',
    'where' => 'Picture',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_PICT/%d'
  ],
  [
    'id' => 15038,
    'name' => 'CENT_has_ACTI',
    'isTree' => false,
    'select' => 'Centrum',
    'where' => 'Activity',
    'query' => 'data/entities/Centrum/linked/CENT_has_ACTI/%d'
  ],
  [
    'id' => 15061,
    'name' => 'CENT_has_adm_CARD',
    'isTree' => false,
    'description' => 'Kontaktperson',
    'select' => 'Centrum',
    'where' => 'Card',
    'query' => 'data/entities/Centrum/linked/CENT_has_adm_CARD/%d'
  ],
  [
    'id' => 15107,
    'name' => 'RENE_has_AREA',
    'isTree' => false,
    'select' => 'researchNetwork',
    'where' => 'Area',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_AREA/%d'
  ],
  [
    'id' => 15130,
    'name' => 'RENE_has_ACTI',
    'isTree' => false,
    'select' => 'researchNetwork',
    'where' => 'Activity',
    'query' => 'data/entities/researchNetwork/linked/RENE_has_ACTI/%d'
  ],
  [
    'id' => 15153,
    'name' => 'CODC_has_TASK',
    'isTree' => false,
    'description' => 'Aufgaben',
    'select' => 'CODC',
    'where' => 'Task',
    'query' => 'data/entities/CODC/linked/CODC_has_TASK/%d'
  ],
  [
    'id' => 15176,
    'name' => 'CODC_has_FOSP',
    'isTree' => false,
    'select' => 'CODC',
    'where' => 'Forschungsschwerpunkt',
    'query' => 'data/entities/CODC/linked/CODC_has_FOSP/%d'
  ],
  [
    'id' => 15199,
    'name' => 'CODC_has_doct_FILE',
    'isTree' => false,
    'description' => 'Doktorurkunde',
    'select' => 'CODC',
    'where' => 'File',
    'query' => 'data/entities/CODC/linked/CODC_has_doct_FILE/%d'
  ],
  [
    'id' => 15222,
    'name' => 'CODC_has_diss_PUBL',
    'isTree' => false,
    'description' => 'Dissertation',
    'select' => 'CODC',
    'where' => 'Publication',
    'query' => 'data/entities/CODC/linked/CODC_has_diss_PUBL/%d'
  ],
  [
    'id' => 15245,
    'name' => 'CODC_has_CARD',
    'isTree' => false,
    'select' => 'CODC',
    'where' => 'Card',
    'query' => 'data/entities/CODC/linked/CODC_has_CARD/%d'
  ],
  [
    'id' => 15268,
    'name' => 'CENT_has_PICT',
    'isTree' => false,
    'description' => 'Logo',
    'select' => 'Centrum',
    'where' => 'Picture',
    'query' => 'data/entities/Centrum/linked/CENT_has_PICT/%d'
  ],
  [
    'id' => 15337,
    'name' => 'CODC_has_PUBL',
    'isTree' => false,
    'description' => 'Publikationen',
    'select' => 'CODC',
    'where' => 'Publication',
    'query' => 'data/entities/CODC/linked/CODC_has_PUBL/%d'
  ],
  [
    'id' => 15360,
    'name' => 'CODC_has_PROJ',
    'isTree' => false,
    'description' => 'Projekte',
    'select' => 'CODC',
    'where' => 'Project',
    'query' => 'data/entities/CODC/linked/CODC_has_PROJ/%d'
  ],
  [
    'id' => 15383,
    'name' => 'CODC_has_GRDP',
    'isTree' => false,
    'description' => 'Graduiertenprogramm',
    'select' => 'CODC',
    'where' => 'Graduate Programs',
    'query' => 'data/entities/CODC/linked/CODC_has_GRDP/%d'
  ],
  [
    'id' => 15406,
    'name' => 'CODC_has_TAGS',
    'isTree' => false,
    'description' => 'Schlagwörter',
    'select' => 'CODC',
    'where' => 'Tags',
    'query' => 'data/entities/CODC/linked/CODC_has_TAGS/%d'
  ],
  [
    'id' => 15429,
    'name' => 'CODC_has_admi_FILE',
    'isTree' => false,
    'description' => 'Zulassung zur Promotion an der Partneruniversität',
    'select' => 'CODC',
    'where' => 'File',
    'query' => 'data/entities/CODC/linked/CODC_has_admi_FILE/%d'
  ],
  [
    'id' => 15452,
    'name' => 'CODC_has_cv_FILE',
    'isTree' => false,
    'description' => 'Curriculum Vitae',
    'select' => 'CODC',
    'where' => 'File',
    'query' => 'data/entities/CODC/linked/CODC_has_cv_FILE/%d'
  ],
  [
    'id' => 15475,
    'name' => 'CODC_has_dip_FILE',
    'isTree' => false,
    'description' => 'Hochschulabschlusszeugnis',
    'select' => 'CODC',
    'where' => 'File',
    'query' => 'data/entities/CODC/linked/CODC_has_dip_FILE/%d'

  ],
  [
    'id' => 15498,
    'name' => 'CODC_has_exp_FILE',
    'isTree' => false,
    'description' => 'Upload des Exposés',
    'select' => 'CODC',
    'where' => 'File',
    'query' => 'data/entities/CODC/linked/CODC_has_exp_FILE/%d'

  ],
  [
    'id' => 15521,
    'name' => 'CODC_has_ext_ORGA',
    'isTree' => false,
    'description' => 'Partneruniversität',
    'select' => 'CODC',
    'where' => 'Organisation',
    'query' => 'data/entities/CODC/linked/CODC_has_ext_ORGA/%d'
  ],
  [
    'id' => 15544,
    'name' => 'CODC_has_info_FILE',
    'isTree' => false,
    'description' => 'Informationen zum Promovieren an der Hochschule Fulda',
    'select' => 'CODC',
    'where' => 'File',
    'query' => 'data/entities/CODC/linked/CODC_has_info_FILE/%d'

  ],
  [
    'id' => 15567,
    'name' => 'CODC_has_stud_ORGA',
    'isTree' => false,
    'description' => 'Besuchte Hochschule',
    'select' => 'CODC',
    'where' => 'Organisation',
    'query' => 'data/entities/CODC/linked/CODC_has_stud_ORGA/%d'
  ],
  [
    'id' => 15590,
    'name' => 'CODC_has_STAT',
    'isTree' => false,
    'description' => 'Zuordnung laut amtlicher Statistik',
    'select' => 'CODC',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/CODC/linked/CODC_has_STAT/%d'
  ],
  [
    'id' => 15613,
    'name' => 'CODC_has_AREA',
    'isTree' => false,
    'description' => 'HFD Forschungsschwerpunkte',
    'select' => 'CODC',
    'where' => 'Area',
    'query' => 'data/entities/CODC/linked/CODC_has_AREA/%d'
  ],
  [
    'id' => 15636,
    'name' => 'CODC_has_RENE',
    'isTree' => false,
    'description' => 'HFD Forschungsverbünde',
    'select' => 'CODC',
    'where' => 'researchNetwork',
    'query' => 'data/entities/CODC/linked/CODC_has_RENE/%d'
  ],
  [
    'id' => 15659,
    'name' => 'CODC_has_CENT',
    'isTree' => false,
    'description' => 'HFD Wissenschaftliche Zentren',
    'select' => 'CODC',
    'where' => 'Centrum',
    'query' => 'data/entities/CODC/linked/CODC_has_CENT/%d'
  ],
  [
    'id' => 912030,
    'name' => 'CODC_has_studext_ORGA',
    'isTree' => false,
    'description' => 'Externe besuchte Hochschule',
    'select' => 'CODC',
    'where' => 'Organisation',
    'query' => 'data/entities/CODC/linked/CODC_has_studext_ORGA/%d'
  ],
  [
    'id' => 913359,
    'name' => 'ACTI_has_FILE',
    'isTree' => false,
    'description' => '[X] Vortragsdokument',
    'select' => 'Activity',
    'where' => 'File',
    'query' => 'data/entities/Activity/linked/ACTI_has_FILE/%d'
  ],
  [
    'id' => 2076332,
    'name' => 'WIZW_has_child_WIZW',
    'isTree' => true,
    'select' => 'Wirtschaftszweige',
    'where' => 'Wirtschaftszweige',
    'query' => 'data/entities/Wirtschaftszweige/linked/WIZW_has_child_WIZW/%d'
  ],
  [
    'id' => 2076361,
    'name' => 'ORGA_has_WIZW',
    'isTree' => false,
    'description' => '[X] Wirtschaftswzeig \/ Branche',
    'select' => 'Organisation',
    'where' => 'Wirtschaftszweige',
    'query' => 'data/entities/Organisation/linked/ORGA_has_WIZW/%d'
  ],
  [
    'id' => 2209235,
    'name' => 'ORGA_has_leit_FUNC',
    'isTree' => false,
    'description' => '[X] Leitungsfunktion der Orga Einheit',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_leit_FUNC/%d'
  ],
  [
    'id' => 2209308,
    'name' => 'ORGA_has_leit_PERS',
    'isTree' => false,
    'description' => '[X] Leitungsperson',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_leit_PERS/%d'
  ],
  [
    'id' => 2209342,
    'name' => 'ORGA_has_sv_PERS',
    'isTree' => false,
    'description' => '[X] Stellvertretender Leiter',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_sv_PERS/%d'
  ],
  [
    'id' => 2209373,
    'name' => 'ORGA_has_prof_CARD',
    'isTree' => false,
    'description' => '[X] Professoren der Org-Einheit',
    'select' => 'Organisation',
    'where' => 'Card',
    'query' => 'data/entities/Organisation/linked/ORGA_has_prof_CARD/%d'
  ],
  [
    'id' => 2209404,
    'name' => 'PROF_has_hon_CARD',
    'isTree' => false,
    'select' => 'Organisation',
    'where' => 'Card',
    'query' => 'data/entities/Organisation/linked/PROF_has_hon_CARD/%d'
  ],
  [
    'id' => 2209435,
    'name' => 'ORGA_has_emer_CARD',
    'isTree' => false,
    'description' => '[X] Emeriti',
    'select' => 'Organisation',
    'where' => 'Card',
    'query' => 'data/entities/Organisation/linked/ORGA_has_emer_CARD/%d'
  ],
  [
    'id' => 2209466,
    'name' => 'ORGA_has_hon_CARD',
    'isTree' => false,
    'description' => '[X] Honorarprofessoren',
    'select' => 'Organisation',
    'where' => 'Card',
    'query' => 'data/entities/Organisation/linked/ORGA_has_hon_CARD/%d'
  ],
  [
    'id' => 2209497,
    'name' => 'ORGA_has_ma_CARD',
    'isTree' => false,
    'description' => '[X] wissenschaftliche Mitarbeiter der ORg-Einheit',
    'select' => 'Organisation',
    'where' => 'Card',
    'query' => 'data/entities/Organisation/linked/ORGA_has_ma_CARD/%d'
  ],
  [
    'id' => 2209528,
    'name' => 'ORGA_has_sv_FUNC',
    'isTree' => false,
    'description' => '[X] Funktionsbzeichnung der stellv. Leitung',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_sv_FUNC/%d'
  ],
  [
    'id' => 2209595,
    'name' => 'ORGA_has_sprech_PERS',
    'isTree' => false,
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_sprech_PERS/%d'
  ],
  [
    'id' => 2209626,
    'name' => 'ORGA_has_sprech_FUNC',
    'isTree' => false,
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_sprech_FUNC/%d'
  ],
  [
    'id' => 2209657,
    'name' => 'ORGA_has_koor_PERS',
    'isTree' => false,
    'description' => '[X] Koordinator',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_koor_PERS/%d'
  ],
  [
    'id' => 2209688,
    'name' => 'ORGA_has_koor_FUNC',
    'isTree' => false,
    'description' => '[X] Koordinierungsfunktion',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_koor_FUNC/%d'
  ],
  [
    'id' => 2209719,
    'name' => 'ORGA_has_co_CARD',
    'isTree' => false,
    'select' => 'Organisation',
    'where' => 'Card',
    'query' => 'data/entities/Organisation/linked/ORGA_has_co_CARD/%d'
  ],
  [
    'id' => 2245604,
    'name' => 'CENT_has_PUBL',
    'isTree' => false,
    'select' => 'Centrum',
    'where' => 'Publication',
    'query' => 'data/entities/Centrum/linked/CENT_has_PUBL/%d'
  ],
  [
    'id' => 2287183,
    'name' => 'JOUR_has_DDC',
    'isTree' => false,
    'select' => 'Journal',
    'where' => 'DDC',
    'query' => 'data/entities/Journal/linked/JOUR_has_DDC/%d'
  ],
  [
    'id' => 2296923,
    'name' => 'THES_has_CARD',
    'isTree' => false,
    'description' => '[X] Verknüpfung zum Betreuer [Z:Darstellung, Auswertung]',
    'select' => 'Supervised thesis',
    'where' => 'Card',
    'query' => 'data/entities/Supervised thesis/linked/THES_has_CARD/%d'
  ],
  [
    'id' => 2300057,
    'name' => 'THES_has_FOFE',
    'isTree' => false,
    'description' => '[X] Verknüpfung zum Forschungsfeld',
    'select' => 'Supervised thesis',
    'where' => 'forschungsfeld',
    'query' => 'data/entities/Supervised thesis/linked/THES_has_FOFE/%d'
  ],
  [
    'id' => 2300086,
    'name' => 'THES_has_AREA',
    'isTree' => false,
    'description' => '[X] THM Schwerpunkt der Abschlussarbeit',
    'select' => 'Supervised thesis',
    'where' => 'Area',
    'query' => 'data/entities/Supervised thesis/linked/THES_has_AREA/%d'
  ],
  [
    'id' => 2300116,
    'name' => 'THES_has_STAT',
    'isTree' => false,
    'description' => '[X] Verknüpfung zur amtlichen Statistik',
    'select' => 'Supervised thesis',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Supervised thesis/linked/THES_has_STAT/%d'
  ],
  [
    'id' => 2300145,
    'name' => 'THES_has_DFGA',
    'isTree' => false,
    'description' => '[X] Verknüpfung zu DFG-Kategorien',
    'select' => 'Supervised thesis',
    'where' => 'DFGArea',
    'query' => 'data/entities/Supervised thesis/linked/THES_has_DFGA/%d'
  ],
  [
    'id' => 2300176,
    'name' => 'THES_has_WIZW',
    'isTree' => false,
    'description' => '[X] Verknüpfung zwischen Thesis und Wirtschaftszweigen',
    'select' => 'Supervised thesis',
    'where' => 'Wirtschaftszweige',
    'query' => 'data/entities/Supervised thesis/linked/THES_has_WIZW/%d'
  ],
  [
    'id' => 2300707,
    'name' => 'AWAR_has_CARD',
    'isTree' => false,
    'select' => 'Award',
    'where' => 'Card',
    'query' => 'data/entities/Award/linked/AWAR_has_CARD/%d'
  ],
  [
    'id' => 2300736,
    'name' => 'AWAR_has_EXTO',
    'isTree' => false,
    'select' => 'Award',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Award/linked/AWAR_has_EXTO/%d'
  ],
  [
    'id' => 2300766,
    'name' => 'AWAR_has_AREA',
    'isTree' => false,
    'select' => 'Award',
    'where' => 'Area',
    'query' => 'data/entities/Award/linked/AWAR_has_AREA/%d'
  ],
  [
    'id' => 2300795,
    'name' => 'AWAR_has_STAT',
    'isTree' => false,
    'select' => 'Award',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/Award/linked/AWAR_has_STAT/%d'
  ],
  [
    'id' => 2300824,
    'name' => 'AWAR_has_DFGA',
    'isTree' => false,
    'select' => 'Award',
    'where' => 'DFGArea',
    'query' => 'data/entities/Award/linked/AWAR_has_DFGA/%d'
  ],
  [
    'id' => 2300853,
    'name' => 'AWAR_has_WIZW',
    'isTree' => false,
    'select' => 'Award',
    'where' => 'Wirtschaftszweige',
    'query' => 'data/entities/Award/linked/AWAR_has_WIZW/%d'
  ],
  [
    'id' => 2301221,
    'name' => 'EXTO_has_COUN',
    'isTree' => false,
    'description' => '[X] Zuordnung zu Ländern',
    'select' => 'externalOrganisation',
    'where' => 'Country',
    'query' => 'data/entities/externalOrganisation/linked/EXTO_has_COUN/%d'
  ],
  [
    'id' => 2301248,
    'name' => 'EXTO_has_WIZW',
    'isTree' => false,
    'description' => '[X] Wirtschaftswzeig \/ Branche ',
    'select' => 'externalOrganisation',
    'where' => 'Wirtschaftszweige',
    'query' => 'data/entities/externalOrganisation/linked/EXTO_has_WIZW/%d'
  ],
  [
    'id' => 2301277,
    'name' => 'EXTO_has_STAT',
    'isTree' => false,
    'description' => '[X] Zugeordnete Kategorie der amtlichen Statisitk (Destatis) ',
    'select' => 'externalOrganisation',
    'where' => 'StatisticsArea',
    'query' => 'data/entities/externalOrganisation/linked/EXTO_has_STAT/%d'
  ],
  [
    'id' => 2301306,
    'name' => 'EXTO_has_DFGA',
    'isTree' => false,
    'description' => '[X] Zuordnung DFG-Kategorien ',
    'select' => 'externalOrganisation',
    'where' => 'DFGArea',
    'query' => 'data/entities/externalOrganisation/linked/EXTO_has_DFGA/%d'
  ],
  [
    'id' => 2301335,
    'name' => 'EXTO_has_AREA',
    'isTree' => false,
    'description' => '[X] Zuordnung zu THM Schwerpunkten',
    'select' => 'externalOrganisation',
    'where' => 'Area',
    'query' => 'data/entities/externalOrganisation/linked/EXTO_has_AREA/%d'
  ],
  [
    'id' => 2301512,
    'name' => 'ACTI_has_EXTO',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Activity/linked/ACTI_has_EXTO/%d'
  ],
  [
    'id' => 2301674,
    'name' => 'ACTI_has_WIZW',
    'isTree' => false,
    'select' => 'Activity',
    'where' => 'Wirtschaftszweige',
    'query' => 'data/entities/Activity/linked/ACTI_has_WIZW/%d'
  ],
  [
    'id' => 2312862,
    'name' => 'PAPL_has_fund_EXTO',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Project application/linked/PAPL_has_fund_EXTO/%d'
  ],
  [
    'id' => 2312889,
    'name' => 'PAPL_has_pt_EXTO',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Project application/linked/PAPL_has_pt_EXTO/%d'
  ],
  [
    'id' => 2312952,
    'name' => 'PAPL_has_EXFU',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'external Funds',
    'query' => 'data/entities/Project application/linked/PAPL_has_EXFU/%d'
  ],
  [
    'id' => 2312985,
    'name' => 'PAPL_has_ftn_PERS',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Person',
    'query' => 'data/entities/Project application/linked/PAPL_has_ftn_PERS/%d'
  ],
  [
    'id' => 2313015,
    'name' => 'PAPL_has_admi_CARD',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Card',
    'query' => 'data/entities/Project application/linked/PAPL_has_admi_CARD/%d'
  ],
  [
    'id' => 2313056,
    'name' => 'PAPL_has_WIZW',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'Wirtschaftszweige',
    'query' => 'data/entities/Project application/linked/PAPL_has_WIZW/%d'
  ],
  [
    'id' => 2313569,
    'name' => 'EXFU_has_pi_CARD',
    'isTree' => false,
    'select' => 'external Funds',
    'where' => 'Card',
    'query' => 'data/entities/external Funds/linked/EXFU_has_pi_CARD/%d'
  ],
  [
    'id' => 2313599,
    'name' => 'EXFU_has_fund_EXTO',
    'isTree' => false,
    'select' => 'external Funds',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/external Funds/linked/EXFU_has_fund_EXTO/%d'
  ],
  [
    'id' => 2314128,
    'name' => 'PROJ_has_kost_ORGA',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Organisation',
    'query' => 'data/entities/Project/linked/PROJ_has_kost_ORGA/%d'
  ],
  [
    'id' => 2314160,
    'name' => 'PROJ_has_part_EXTO',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Project/linked/PROJ_has_part_EXTO/%d'
  ],
  [
    'id' => 2314203,
    'name' => 'PROJ_has_fund_EXTO',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Project/linked/PROJ_has_fund_EXTO/%d'
  ],
  [
    'id' => 2314251,
    'name' => 'PROJ_has_pt_EXTO',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Project/linked/PROJ_has_pt_EXTO/%d'
  ],
  [
    'id' => 2314280,
    'name' => 'PROJ_has_CONT',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'contact',
    'query' => 'data/entities/Project/linked/PROJ_has_CONT/%d'
  ],
  [
    'id' => 2314518,
    'name' => 'PROJ_has_gran_FILE',
    'isTree' => false,
    'description' => '[X] Bewilligungsbescheid',
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_gran_FILE/%d'
  ],
  [
    'id' => 2314547,
    'name' => 'PROJ_has_koop_FILE',
    'isTree' => false,
    'description' => '[X] Kooperationsvertrag',
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_koop_FILE/%d'
  ],
  [
    'id' => 2314576,
    'name' => 'PROJ_has_cal_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_cal_FILE/%d'
  ],
  [
    'id' => 2314606,
    'name' => 'PROJ_has_pay_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_pay_FILE/%d'
  ],
  [
    'id' => 2314635,
    'name' => 'PROJ_has_repo_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_repo_FILE/%d'
  ],
  [
    'id' => 2314664,
    'name' => 'PROJ_has_use_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_use_FILE/%d'
  ],
  [
    'id' => 2314693,
    'name' => 'PROJ_has_app_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_app_FILE/%d'
  ],
  [
    'id' => 2314722,
    'name' => 'PROJ_has_eval_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_eval_FILE/%d'
  ],
  [
    'id' => 2314751,
    'name' => 'PROJ_has_final_FILE',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_final_FILE/%d'
  ],
  [
    'id' => 2314862,
    'name' => 'PROJ_has_WIZW',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Wirtschaftszweige',
    'query' => 'data/entities/Project/linked/PROJ_has_WIZW/%d'
  ],
  [
    'id' => 2314891,
    'name' => 'PROJ_has_ftn_PERS',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Person',
    'query' => 'data/entities/Project/linked/PROJ_has_ftn_PERS/%d'
  ],
  [
    'id' => 2325253,
    'name' => 'PAPL_has_part_EXTO',
    'isTree' => false,
    'select' => 'Project application',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Project application/linked/PAPL_has_part_EXTO/%d'
  ],
  [
    'id' => 2328733,
    'name' => 'PROJ_has_FIZW',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'finanzierungszweck',
    'query' => 'data/entities/Project/linked/PROJ_has_FIZW/%d'
  ],
  [
    'id' => 2335644,
    'name' => 'FUND_has_fund_EXTO',
    'isTree' => false,
    'select' => 'cfFund',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/cfFund/linked/FUND_has_fund_EXTO/%d'
  ],
  [
    'id' => 2388649,
    'name' => 'FUND_has_FIZW',
    'isTree' => false,
    'description' => '[X] Finanzierungszweck der Förderlinie',
    'select' => 'cfFund',
    'where' => 'finanzierungszweck',
    'query' => 'data/entities/cfFund/linked/FUND_has_FIZW/%d'
  ],
  [
    'id' => 2388680,
    'name' => 'FUND_has_ftn_PERS',
    'isTree' => false,
    'description' => '[X] Zuständiger Sachbearbeiter bei FTN',
    'select' => 'cfFund',
    'where' => 'Person',
    'query' => 'data/entities/cfFund/linked/FUND_has_ftn_PERS/%d'
  ],
  [
    'id' => 2388768,
    'name' => 'PAPL_has_FIZW',
    'isTree' => false,
    'description' => '[X] Finanzierungszweck',
    'select' => 'Project application',
    'where' => 'finanzierungszweck',
    'query' => 'data/entities/Project application/linked/PAPL_has_FIZW/%d'
  ],
  [
    'id' => 2413593,
    'name' => 'ATES_has_BTES',
    'isTree' => false,
    'select' => 'allgTestB',
    'where' => 'allgTestEntity',
    'query' => 'data/entities/allgTestB/linked/ATES_has_BTES/%d'
  ],
  [
    'id' => 2465992,
    'name' => 'ORGA_has_pos1_PERS',
    'isTree' => false,
    'description' => '[X] Person 1 ',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos1_PERS/%d'
  ],
  [
    'id' => 2466023,
    'name' => 'ORGA_has_pos1_FUNC',
    'isTree' => false,
    'description' => '[X] Positionsbezeichnung 1',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos1_FUNC/%d'
  ],
  [
    'id' => 2466304,
    'name' => 'ORGA_has_pos2_PERS',
    'isTree' => false,
    'description' => '[X] Person 2',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos2_PERS/%d'
  ],
  [
    'id' => 2466335,
    'name' => 'ORGA_has_pos3_PERS',
    'isTree' => false,
    'description' => '[X] Person 3',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos3_PERS/%d'
  ],
  [
    'id' => 2466366,
    'name' => 'ORGA_has_pos4_PERS',
    'isTree' => false,
    'description' => '[X] Person 4',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos4_PERS/%d'
  ],
  [
    'id' => 2466397,
    'name' => 'ORGA_has_pos5_PERS',
    'isTree' => false,
    'description' => '[X] Person 5',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos5_PERS/%d'
  ],
  [
    'id' => 2466429,
    'name' => 'ORGA_has_pos6_PERS',
    'isTree' => false,
    'description' => '[X] Person 6',
    'select' => 'Organisation',
    'where' => 'Person',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos6_PERS/%d'
  ],
  [
    'id' => 2466463,
    'name' => 'ORGA_has_pos2_FUNC',
    'isTree' => false,
    'description' => '[X] Postionsbezeichnung 2',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos2_FUNC/%d'
  ],
  [
    'id' => 2466494,
    'name' => 'ORGA_has_pos3_FUNC',
    'isTree' => false,
    'description' => '[X] Positionsbezeichnung 3',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos3_FUNC/%d'
  ],
  [
    'id' => 2466525,
    'name' => 'ORGA_has_pos4_FUNC',
    'isTree' => false,
    'description' => '[X] Positionsbezeichnung 4',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos4_FUNC/%d'
  ],
  [
    'id' => 2466556,
    'name' => 'ORGA_has_pos5_FUNC',
    'isTree' => false,
    'description' => '[X] Positionsbezeichnung 5',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos5_FUNC/%d'
  ],
  [
    'id' => 2466587,
    'name' => 'ORGA_has_pos6_FUNC',
    'isTree' => false,
    'description' => '[X] Positionsbezeichnung 6',
    'select' => 'Organisation',
    'where' => 'function',
    'query' => 'data/entities/Organisation/linked/ORGA_has_pos6_FUNC/%d'
  ],
  [
    'id' => 3360950,
    'name' => 'PROJ_has_change_FILE',
    'isTree' => false,
    'description' => 'Änderungsbescheide',
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_change_FILE/%d'
  ],
  [
    'id' => 3534617,
    'name' => 'EXTO_has_CONT',
    'isTree' => false,
    'description' => 'Ansprechpartner für externe Organisationen',
    'select' => 'externalOrganisation',
    'where' => 'contact',
    'query' => 'data/entities/externalOrganisation/linked/EXTO_has_CONT/%d'
  ],
  [
    'id' => 3557693,
    'name' => 'PROJ_has_logo_PICT',
    'isTree' => false,
    'description' => 'Logo zum Projekt',
    'select' => 'Project',
    'where' => 'Picture',
    'query' => 'data/entities/Project/linked/PROJ_has_logo_PICT/%d'
  ],
  [
    'id' => 3563025,
    'name' => 'PROJ_has_coop_CONT',
    'isTree' => false,
    'description' => 'Ansprechpratner bei Kooperationspartner',
    'select' => 'Project',
    'where' => 'contact',
    'query' => 'data/entities/Project/linked/PROJ_has_coop_CONT/%d'
  ],
  [
    'id' => 3993163,
    'name' => 'PAPL_has_rej_FILE',
    'isTree' => false,
    'description' => 'Ablehnungsbescheid',
    'select' => 'Project application',
    'where' => 'File',
    'query' => 'data/entities/Project application/linked/PAPL_has_rej_FILE/%d'
  ],
  [
    'id' => 4055106,
    'name' => 'EXTO_has_child_EXTO',
    'isTree' => false,
    'description' => 'Untergeordnete externe Organisation ',
    'select' => 'externalOrganisation',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/externalOrganisation/linked/EXTO_has_child_EXTO/%d'
  ],
  [
    'id' => 4258437,
    'name' => 'PROJ_has_COMM',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Comment',
    'query' => 'data/entities/Project/linked/PROJ_has_COMM/%d'
  ],
  [
    'id' => 4258470,
    'name' => 'COMM_has_reg_PERS',
    'isTree' => false,
    'select' => 'Comment',
    'where' => 'Person',
    'query' => 'data/entities/Comment/linked/COMM_has_reg_PERS/%d'
  ],
  [
    'id' => 4258499,
    'name' => 'COMM_has_reg_Card',
    'isTree' => false,
    'select' => 'Comment',
    'where' => 'Card',
    'query' => 'data/entities/Comment/linked/COMM_has_reg_Card/%d'
  ],
  [
    'id' => 4258528,
    'name' => 'COMM_has_reg_ORGA',
    'isTree' => false,
    'description' => 'Verwaltungsorganisation, die den Kommentar registriert hat',
    'select' => 'Comment',
    'where' => 'Organisation',
    'query' => 'data/entities/Comment/linked/COMM_has_reg_ORGA/%d'
  ],
  [
    'id' => 4266098,
    'name' => 'PROJ_has_reg_CARD',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Card',
    'query' => 'data/entities/Project/linked/PROJ_has_reg_CARD/%d'
  ],
  [
    'id' => 4266131,
    'name' => 'PROJ_has_resp_ORGA',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Organisation',
    'query' => 'data/entities/Project/linked/PROJ_has_resp_ORGA/%d'
  ],
  [
    'id' => 4266166,
    'name' => 'PROJ_has_resp_CARD',
    'isTree' => false,
    'select' => 'Project',
    'where' => 'Card',
    'query' => 'data/entities/Project/linked/PROJ_has_resp_CARD/%d'
  ],
  [
    'id' => 4448652,
    'name' => 'CODC_has_stud_EXTO',
    'isTree' => false,
    'description' => 'Externe Hochschule an der der Abschluss erworben wurde.',
    'select' => 'CODC',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/CODC/linked/CODC_has_stud_EXTO/%d'
  ],
  [
    'id' => 4448681,
    'name' => 'CODC_has_EXTO',
    'isTree' => false,
    'description' => 'Partneruniversität',
    'select' => 'CODC',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/CODC/linked/CODC_has_EXTO/%d'

  ],
  [
    'id' => 4497075,
    'name' => 'EXTP_has_EXTO',
    'isTree' => false,
    'description' => 'Organisationszugehörigkeit einer externen Person zu einer externen Organisation',
    'select' => 'externalPerson',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/externalPerson/linked/EXTP_has_EXTO/%d'
  ],
  [
    'id' => 4497104,
    'name' => 'EXTP_has_COUN',
    'isTree' => false,
    'select' => 'externalPerson',
    'where' => 'Country',
    'query' => 'data/entities/externalPerson/linked/EXTP_has_COUN/%d'
  ],
  [
    'id' => 4498912,
    'name' => 'CODC_has_sup_EXTP',
    'isTree' => false,
    'select' => 'CODC',
    'where' => 'externalPerson',
    'query' => 'data/entities/CODC/linked/CODC_has_sup_EXTP/%d'

  ],
  [
    'id' => 4501216,
    'name' => 'CODC_has_GRFD',
    'isTree' => false,
    'description' => 'Angaben zur Finanzierung der Promotion',
    'select' => 'CODC',
    'where' => 'Graduate Funding',
    'query' => 'data/entities/CODC/linked/CODC_has_GRFD/%d'
  ],
  [
    'id' => 4527471,
    'name' => 'GRFD_has_PROJ',
    'isTree' => false,
    'description' => 'Drittmittelprojekt ',
    'select' => 'Graduate Funding',
    'where' => 'Project',
    'query' => 'data/entities/Graduate Funding/linked/GRFD_has_PROJ/%d'
  ],
  [
    'id' => 4527509,
    'name' => 'GRFD_has_EXTO',
    'isTree' => false,
    'description' => 'Organisation bei externer Förderung',
    'select' => 'Graduate Funding',
    'where' => 'externalOrganisation',
    'query' => 'data/entities/Graduate Funding/linked/GRFD_has_EXTO/%d'
  ],
  [
    'id' => 4527549,
    'name' => 'GRFD_has_ORGA',
    'isTree' => false,
    'description' => 'Organisation bei interner Förderung (Abteilung, Institut, usw.)',
    'select' => 'Graduate Funding',
    'where' => 'Organisation',
    'query' => 'data/entities/Graduate Funding/linked/GRFD_has_ORGA/%d'
  ],
  [
    'id' => 4528227,
    'name' => 'PROJ_has_rechn_FILE',
    'isTree' => false,
    'description' => 'Rechnung bilaterale Projekte',
    'select' => 'Project',
    'where' => 'File',
    'query' => 'data/entities/Project/linked/PROJ_has_rechn_FILE/%d'
  ],
  [
    'id' => 4529164,
    'name' => 'CODC_has_comp_ORGA',
    'isTree' => false,
    'select' => 'CODC',
    'where' => 'Organisation',
    'query' => 'data/entities/CODC/linked/CODC_has_comp_ORGA/%d'
  ],
  [
    'id' => 4529360,
    'name' => 'CODV_has_sup_FILE',
    'isTree' => false,
    'select' => 'CODC',
    'where' => 'File',
    'query' => 'data/entities/CODC/linked/CODV_has_sup_FILE/%d'
  ],
  [
    'id' => 4529389,
    'name' => 'CODC_has_sup_FILE',
    'isTree' => false,
    'select' => 'CODC',
    'where' => 'File',
    'query' => 'data/entities/CODC/linked/CODC_has_sup_FILE/%d'
  ]
]
*/