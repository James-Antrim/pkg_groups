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

class Entities
{
    // Discovered entity codes
    public const
        ACADEMIC_SPINOFFS = 1275,
        ACTIVITIES = 2250,
        ADMISSIONS = 2874,
        AREAS = 1119,
        ASSESSMENT_UNITS = 612,
        AWARD = 1470,
        BRANCHES = 2076211,
        CARDS = 1938,
        CASH_FLOWS = 1236,
        CENTERS = 2016,
        CIPCS = 807,
        CITATIONS = 1704,
        COMMENTS = 4258264,
        CONTACTS = 2312662,
        CONTINUING_EDUCATION = 1080,
        COOPERATION = 846,
        COUNTRIES = 2094,
        CURRICULA = 2601,
        DDCS = 261,
        DFG_FIELDS = 1743,
        DOCTORATES = 2172,
        EMBEDDED_FILES = 2367,
        EMPLOYMENTS = 1158,
        EQUIPMENT = 1626,
        ETHICS_REVIEWS = 222,
        EVALUATIONS = 300,
        EVENTS = 2640,
        EXCHANGE_RATES = 768,
        EXTENSIONS = 27,
        EXTERNAL_FUNDS = 1197,
        EXTERNAL_ORGANIZATIONS = 2245660,
        EXTERNAL_PERSONS = 4496786,
        FACILITIES = 885,
        FILES = 963,
        FIXED_MILESTONES = 378,
        FUNDERS = 1392,
        FUNDING = 1587,
        FUNDING_PURPOSES = 2328647,
        FUNDING_TYPES = 2133,
        GRADES = 2484,
        GRADUATE_FUNDING = 4501047,
        GRADUATIONS = 2952,
        HFD_AREAS = 1977,
        HABILITATION = 1431,
        IDEAS = 534,
        INTERRUPTIONS = 2796,
        INVENTION_DISCLOSURES = 1041,
        JOURNALS = 573,
        KDSF_FIELDS = 2208354,
        LANGUAGE_COMPETENCES = 2718,
        LANGUAGES = 2523,
        LICENSES = 729,
        MEETINGS = 2835,
        MESSAGES = 1860,
        MILESTONES = 2757,
        ORGANIZATIONS = 183,
        PARTICIPATION = 339,
        PATENT_APPLICATIONS = 651,
        PATENTS = 144,
        PAYMENTS = 2055,
        PEER_REVIEWS = 417,
        PERSONNEL_EXCHANGES = 1665,
        PERSONS = 66,
        PICTURES = 1002,
        PROFILE_PICTURES = 2289,
        PROGRAMS = 2679,
        PROJECT_APPLICATIONS = 2328,
        PROJECTS = 1353,
        PSP_ELEMENTS = 2445,
        PUBLICATION_APPLICATIONS = 2406,
        PUBLICATIONS = 495,
        RDF_DOMAINS = 2913,
        REF_CLAIMS = 456,
        REGISTRATIONS = 924,
        REPORTS = 4074298,
        RESEARCH_FOCUSES = 2211,
        RESEARCH_NETWORK = 1899,
        RESEARCH_RESULTS = 690,
        ROLES = 2208524,
        SEARCH_PROFILES = 1782,
        SERVICES = 1548,
        STATISTICS_AREAS = 1314,
        SUPERVISED_THESES = 2296810,
        TAGS = 105,
        TASKS = 2562,
        USAGE = 1509,
        WOS_CATEGORIES = 1821;

    public const ENTITIES = [

        self::ACADEMIC_SPINOFFS => [
            'entity' => 'AcademicSpinoff',
            'name_de' => 'Akademischer Spinoff',
            'name_en' => 'Academic Spinoff',
            'plural_de' => 'Akademische Spinoffs',
            'plural_en' => 'Academic Spinoffs'
        ],

        self::ACTIVITIES => [
            'entity' => 'Activity',
            'name_de' => 'Aktivität',
            'name_en' => 'Activity',
            'plural_de' => 'Aktivitäten',
            'plural_en' => 'CV Activities'
        ],

        self::ADMISSIONS => [
            'entity' => 'Admission',
            'name_de' => 'Annahme als Doktorand',
            'name_en' => 'Graduate Admission',
            'plural_de' => 'Annahmen als Doktorand',
            'plural_en' => 'Graduate Admissions'
        ],

        /**
         * [X] THM research areas
         * @see self::HFD_AREAS
         */
        self::AREAS => [
            'entity' => 'Area',
            'name_de' => 'THM Schwerpunkt',
            'name_en' => 'Research Area',
            'plural_de' => 'THM Schwerpunkte',
            'plural_en' => 'Research Areas'
        ],

        // Units of Assessment used for the REF submission in the UK.
        self::ASSESSMENT_UNITS => [
            'entity' => 'UnitOfAssess',
            'name_de' => 'Unit of Assessment',
            'name_en' => 'Unit of Assessment',
            'plural_de' => 'Units of Assessment',
            'plural_en' => 'Units of Assessment'
        ],

        self::AWARD => [
            'entity' => 'Award',
            'name_de' => 'Preis',
            'name_en' => 'Award',
            'plural_de' => 'Preise',
            'plural_en' => 'Awards'
        ],

        self::BRANCHES => [
            'entity' => 'Wirtschaftszweige',
            'name_de' => 'Branche',
            'name_en' => 'Branch',
            'plural_de' => 'Branche',
            'plural_en' => 'Branch'
        ],

        /**
         * [X] The card describes the relation between a person and an internal organisation. It includes the contact
         * dates of the person in the organisation.
         */
        self::CARDS => [
            'all' => 'data/entities/Card',
            'attributes' => 'fedId,firstName,function,lastName,staffCategory,typeOfCard',
            'entity' => 'Card',
            'name_de' => 'Visitenkarte',
            'name_en' => 'Business Card',
            'plural_de' => 'Visitenkarten',
            'plural_en' => 'Business Cards'
        ],

        self::CASH_FLOWS => [
            'entity' => 'Cash Flow',
            'name_de' => 'Mittelfluss',
            'name_en' => 'Cash Flow',
            'plural_de' => 'Mittelflüsse',
            'plural_en' => 'Cash Flows'
        ],

        self::CENTERS => [
            'entity' => 'Centrum',
            'name_de' => 'Zentrum',
            'name_en' => 'Centrum',
            'plural_de' => 'Zentren',
            'plural_en' => 'Centres'
        ],

        /**
         * CIP Code (Classification of International Programs)
         * @link https://nces.ed.gov/ipeds/cipcode/browse.aspx?y=55
         */
        self::CIPCS => [
            'entity' => 'CIPCode',
            'name_de' => 'CIP Code',
            'name_en' => 'CIP Code',
            'plural_de' => 'CIP Codes',
            'plural_en' => 'CIP Codes'
        ],

        self::CITATIONS => [
            'entity' => 'Citation',
            'name_de' => 'Zitierung',
            'name_en' => 'Citation',
            'plural_de' => 'Zitierungen',
            'plural_en' => 'Citations'
        ],

        // Project journal?
        self::COMMENTS => [
            'entity' => 'Comment',
            'name_de' => 'Kommentar',
            'name_en' => 'Comment',
            'plural_de' => 'Kommentare',
            'plural_en' => 'Comments'
        ],

        // POC for external organizations
        self::CONTACTS => [
            'entity' => 'contact',
            'name_de' => 'Ansprechpartner',
            'name_en' => 'Contact person',
            'plural_de' => 'Ansprechpartner',
            'plural_en' => 'Contact persons'
        ],

        self::CONTINUING_EDUCATION => [
            'entity' => 'Weiterbildungsangebot',
            'name_de' => 'Weiterbildungsangebot',
            'name_en' => 'Further Education Offering',
            'plural_de' => 'Weiterbildungsangebote',
            'plural_en' => 'Further Education Offerings'
        ],

        self::COOPERATION => [
            'entity' => 'cooperation',
            'name_de' => 'Kooperation',
            'name_en' => 'Cooperation',
            'plural_de' => 'Kooperationen',
            'plural_en' => 'Cooperations'
        ],

        self::COUNTRIES => [
            'entity' => 'Country',
            'name_de' => 'Land',
            'name_en' => 'Country',
            'plural_de' => 'Länder',
            'plural_en' => 'Countries'
        ],

        self::CURRICULA => [
            'entity' => 'Study plan',
            'name_de' => 'Graduiertenkolleg Plan',
            'name_en' => 'Study Plan',
            'plural_de' => 'Graduiertenkolleg Pläne',
            'plural_en' => 'Study Plans'
        ],

        self::DDCS => [
            'entity' => 'DDC',
            'name_de' => 'DDC - Dewey-Dezimalklassifikation',
            'name_en' => 'Dewey Decimal Code',
            'plural_de' => 'DDC - Dewey-Dezimalklassifikationen',
            'plural_en' => 'Dewey Decimal Codes'
        ],

        /**
         * DFG research fields
         * @link https://www.dfg.de/index.jsp
         */
        self::DFG_FIELDS => [
            'entity' => 'DFGArea',
            'description' => '[X] DFG Forschungsfeler',
            'name_de' => 'DFG Forschungsfeld',
            'name_en' => 'DFG Area',
            'plural_de' => 'DFG Forschungsfelder',
            'plural_en' => 'DFG Areas'
        ],

        // Cooperative doctorates
        self::DOCTORATES => [
            'entity' => 'CODC',
            'name_de' => 'Promotion',
            'name_en' => 'Doctorate',
            'plural_de' => 'Promotionen',
            'plural_en' => 'Doctorates'
        ],

        self::EMBEDDED_FILES => [
            'entity' => 'Embedded file',
            'name_de' => 'Datei (emb.)',
            'name_en' => 'Embedded File',
            'plural_de' => 'Dateien (emb.)',
            'plural_en' => 'Embedded Files'
        ],

        self::EMPLOYMENTS => [
            'entity' => 'Employment',
            'name_de' => 'Beschäftigungsverhältnis',
            'name_en' => 'Employment',
            'plural_de' => 'Beschäftigungsverhältnisse',
            'plural_en' => 'Employments'
        ],

        self::EQUIPMENT => [
            'entity' => 'cfEquipment',
            'name_de' => 'Ausstattung',
            'name_en' => 'Equipment',
            'plural_de' => 'Ausstattungen',
            'plural_en' => 'Equipment'
        ],

        /**
         * A form to be filled in by the PI to apply for ethics approval by a Research Ethics Board. Approval needs to
         * be obtained before the project can start.
         */
        self::ETHICS_REVIEWS => [
            'entity' => 'EthicsReview',
            'name_de' => 'Ethische Überprüfung',
            'name_en' => 'Ethics Review',
            'plural_de' => 'Ethische Überprüfungen',
            'plural_en' => 'Ethics Reviews'
        ],

        self::EVALUATIONS => [
            'entity' => 'Evaluation',
            'name_de' => 'Evaluation',
            'name_en' => 'Evaluation',
            'plural_de' => 'Evaluierungen',
            'plural_en' => 'Evaluation'
        ],

        self::EVENTS => [
            'entity' => 'cfEvent',
            'name_de' => 'Veranstaltung',
            'name_en' => 'Event',
            'plural_de' => 'Veranstaltungen',
            'plural_en' => 'Events'
        ],

        self::EXCHANGE_RATES => [
            'entity' => 'ExchangeRate',
            'description' => 'currency table',
            'name_de' => 'Wechselkurs',
            'name_en' => 'Exchange Rate',
            'plural_de' => 'Wechselkurse',
            'plural_en' => 'Exchange Rates'
        ],

        // Entity to document/approve an extension to for instance a project or a call.
        self::EXTENSIONS => [
            'entity' => 'Extension',
            'name_de' => 'Verlängerung',
            'name_en' => 'Extension',
            'plural_de' => 'Verl\u00e4ngerungen',
            'plural_en' => 'Extensions'
        ],

        // Third party funds
        self::EXTERNAL_FUNDS => [
            'entity' => 'external Funds',
            'description' => '[X] Drittmittelanzeige',
            'name_de' => 'Drittmittelanzeige',
            'name_en' => 'External Funds',
            'plural_de' => 'Drittmittelanzeigen',
            'plural_en' => 'External Funds'
        ],

        // [X] External organizations like partners, promoters, sponsors, etc.
        self::EXTERNAL_ORGANIZATIONS => [
            'entity' => 'externalOrganisation',
            'name_de' => 'Externe Organisation',
            'name_en' => 'External Organisation',
            'plural_de' => 'Externe Organisationen',
            'plural_en' => 'External Organisations'
        ],

        self::EXTERNAL_PERSONS => [
            'entity' => 'externalPerson',
            'name_de' => 'Externe Person',
            'name_en' => 'External person',
            'plural_de' => 'Externe Personen',
            'plural_en' => 'External persons'
        ],

        self::FACILITIES => [
            'entity' => 'cfFacility',
            'description' => 'Research Infrastructure Module',
            'name_de' => 'Einrichtung',
            'name_en' => 'Facility',
            'plural_de' => 'Einrichtungen',
            'plural_en' => 'Facilities'
        ],

        self::FILES => [
            'entity' => 'File',
            'name_de' => 'Datei',
            'name_en' => 'File',
            'plural_de' => 'Dateien',
            'plural_en' => 'Files'
        ],

        self::FIXED_MILESTONES => [
            'entity' => 'FixedMilestone',
            'name_de' => 'Vordefinierter Meilenstein',
            'name_en' => 'Fixed Milestone',
            'plural_de' => 'Vordefinierte Meilensteine',
            'plural_en' => 'Fixed Milestones'
        ],

        self::FUNDERS => [
            'entity' => 'Funder',
            'name_de' => 'Mittelgeber',
            'name_en' => 'Funder',
            'plural_de' => 'Mittelgeber',
            'plural_en' => 'Funders'
        ],

        self::FUNDING => [
            'entity' => 'cfFund',
            'name_de' => 'Programm / Förderlinie',
            'name_en' => 'Funding programm',
            'plural_de' => 'Programme / Förderlinien',
            'plural_en' => 'Funding programms'
        ],

        // SAP in coordination with HC
        self::FUNDING_PURPOSES => [
            'entity' => 'finanzierungszweck',
            'name_de' => 'Finanzierungszweck',
            'name_en' => 'Funding purpose',
            'plural_de' => 'Finanzierungszwecke',
            'plural_en' => 'Funding purposes'
        ],

        self::FUNDING_TYPES => [
            'entity' => 'FundingType',
            'name_de' => 'Ziel der Ausschreibung',
            'name_en' => 'FundingType',
            'plural_de' => 'Ziele der Ausschreibung',
            'plural_en' => 'FundingType'
        ],

        self::GRADES => [
            'entity' => 'Grade',
            'name_de' => 'Note',
            'name_en' => 'Grade',
            'plural_de' => 'Noten',
            'plural_en' => 'Grades'
        ],

        self::GRADUATE_FUNDING => [
            'entity' => 'Graduate Funding',
            'description' => 'Angaben zur Finanzierung der Promotion',
            'name_de' => 'Finanzierung der Promotion',
            'name_en' => 'Graduate Funding',
            'plural_de' => 'Finanzierungen der Promotion',
            'plural_en' => 'Graduate Fundings'
        ],

        self::GRADUATIONS => [
            'entity' => 'Graduation',
            'name_de' => 'Graduierung',
            'name_en' => 'Graduation',
            'plural_de' => 'Graduierungen',
            'plural_en' => 'Graduations'
        ],

        self::HABILITATION => [
            'entity' => 'Habilitation',
            'name_de' => 'Habilitation',
            'name_en' => 'Habilitation',
            'plural_de' => 'Habilitationen',
            'plural_en' => 'Habilitations'
        ],

        /**
         * (Deprecated) Forschungsschwerpunkte der HFD
         * @see self::AREAS
         */
        self::HFD_AREAS => [
            'entity' => 'Forschungsschwerpunkt',
            'name_de' => 'Forschungsschwerpunkt der THM',
            'name_en' => 'Forschungsschwerpunkt',
            'plural_de' => 'Forschungsschwerpunkte der THM',
            'plural_en' => 'Forschungsschwerpunkte'
        ],

        /**
         * Project idea allows researchers to collect project ideas in a pool. Having ideas noted down, discussed,
         * evaluated with colleagues - for some of them a project application will be the result. To clarify the
         * relation between idea, application and the project itself a constant identifier can be used through out
         * the process.
         */
        self::IDEAS => [
            'entity' => 'Idea',
            'name_de' => 'Projektidee',
            'name_en' => 'Project Idea',
            'plural_de' => 'Projektideen',
            'plural_en' => 'Project Ideas'
        ],

        self::INTERRUPTIONS => [
            'entity' => 'Progress exception',
            'name_de' => 'Fortschrittsunterbrechung',
            'name_en' => 'Progress Exception',
            'plural_de' => 'Fortschrittsunterbrechungen',
            'plural_en' => 'Progress Exceptions'
        ],

        self::INVENTION_DISCLOSURES => [
            'entity' => 'InventionDisc',
            'name_de' => 'Erfindungsmeldung',
            'name_en' => 'Invention Disclosure',
            'plural_de' => 'Erfindungsmeldungen',
            'plural_en' => 'Invention Disclosures'
        ],

        self::JOURNALS => [
            'all' => 'data/entities/Journal',
            'entity' => 'Journal',
            'name_de' => 'Fachzeitschrift',
            'name_en' => 'Journal',
            'plural_de' => 'Fachzeitschriften',
            'plural_en' => 'Journals'
        ],

        /**
         * Research fields according to Kerndatensatz Forschung
         * @link https://www.kerndatensatz-forschung.de/docs_ff/anlage_finale_forschungsfeldklassifikation_ffk-projekt.pdf
         */
        self::KDSF_FIELDS => [
            'entity' => 'forschungsfeld',
            'name_de' => 'Forschungsfeld',
            'name_en' => 'Researcharea',
            'plural_de' => 'Forschungsfelder',
            'plural_en' => 'Researchareas'
        ],

        self::LANGUAGE_COMPETENCES => [
            'entity' => 'cfLangSkill',
            'name_de' => 'Sprachkenntnis',
            'name_en' => 'Language Competency',
            'plural_de' => 'Sprachkenntnisse',
            'plural_en' => 'Language Competencies'
        ],

        self::LANGUAGES => [
            'entity' => 'cfLang',
            'name_de' => 'Sprache',
            'name_en' => 'Language',
            'plural_de' => 'Sprachen',
            'plural_en' => 'Languages'
        ],

        self::LICENSES => [
            'entity' => 'License',
            'name_de' => 'Lizenz',
            'name_en' => 'License',
            'plural_de' => 'Lizenzen',
            'plural_en' => 'Licenses'
        ],

        // Used to keep track on milestones that need to be achieved as part of a study plan.
        self::MILESTONES => [
            'entity' => 'Milestone',
            'name_de' => 'Meilenstein',
            'name_en' => 'PGR Milestone',
            'plural_de' => 'Meilensteine',
            'plural_en' => 'PGR Milestones'
        ],

        self::MEETINGS => [
            'entity' => 'Meeting',
            'name_de' => 'Treffen mit den Betreuern',
            'name_en' => 'Supervisory Meeting',
            'plural_de' => 'Treffen mit den Betreuern',
            'plural_en' => 'Supervisory Meetings'
        ],

        self::MESSAGES => [
            'entity' => 'Message',
            'name_de' => 'Nachricht',
            'name_en' => 'Message',
            'plural_de' => 'Nachrichten',
            'plural_en' => 'Messages'
        ],

        // [X] Organizations of the THM
        self::ORGANIZATIONS => [
            'entity' => 'Organisation',
            'name_de' => 'Organisation',
            'name_en' => 'Organisation',
            'plural_de' => 'Organisationen (THM)',
            'plural_en"' => 'Organisations'
        ],

        self::PARTICIPATION => [
            'entity' => 'Participation',
            'name_de' => 'Partizipation',
            'name_en' => 'Participation',
            'plural_de' => 'Partizipationen',
            'plural_en' => 'Participations'
        ],

        self::PATENT_APPLICATIONS => [
            'entity' => 'PatentApp',
            'name_de' => 'Patentantrag',
            'name_en' => 'Patent Application',
            'plural_de' => 'Patentantr\u00e4ge',
            'plural_en' => 'Patent Applications'
        ],

        self::PATENTS => [
            'entity' => 'cfResPat',
            'name_de' => 'Patent',
            'name_en' => 'Patent',
            'plural_de' => 'Patente',
            'plural_en' => 'Patents'
        ],

        self::PAYMENTS => [
            'entity' => 'PAYM',
            'name_de' => 'Zahlungen',
            'name_en' => 'Payment',
            'plural_de' => 'Zahlungen',
            'plural_en' => 'Payments'
        ],

        self::PEER_REVIEWS => [
            'entity' => 'PeerReview',
            'name_de' => 'Peer Review',
            'name_en' => 'Peer Review',
            'plural_de' => 'Peer Reviews',
            'plural_en' => 'Peer Reviews'
        ],

        self::PERSONNEL_EXCHANGES => [
            'entity' => 'staffExchange',
            'name_de' => 'Personalaustausch',
            'name_en' => 'Staff Exchange',
            'plural_de' => 'Personalaustausch',
            'plural_en' => 'Staff Exchange'
        ],

        // [X] FIS Person Entries
        self::PERSONS => [
            'all' => 'data/entities/Person',
            'attributes' => 'cfFamilyNames,cfFirstNames,fachgebiet,thmLogin,typeOfPerson',
            'entity' => 'Person',
            'name_de' => 'Person',
            'name_en' => 'Person',
            'plural_de' => 'Personen',
            'plural_en' => 'Persons',
            self::CARDS => 'data/entities/Person/linked/PERS_has_CARD/'
        ],

        self::PICTURES => [
            'entity' => 'Picture',
            'name_de' => 'Foto',
            'name_en' => 'Picture',
            'plural_de' => 'Fotos',
            'plural_en' => 'Pictures'
        ],

        self::PROGRAMS => [
            'entity' => 'Graduate Programs',
            'name_de' => 'Graduiertenprogramm',
            'name_en' => 'Graduate Program',
            'plural_de' => 'Graduiertenprogramme',
            'plural_en' => 'Graduate Programs'
        ],

        self::PROFILE_PICTURES => [
            'entity' => 'User photo',
            'name_de' => 'Profilbild',
            'name_en' => 'User Photo',
            'plural_de' => 'Profilbilder',
            'plural_en' => 'User Photo'
        ],

        // Records received project applications.
        self::PROJECT_APPLICATIONS => [
            'entity' => 'Project application',
            'name_de' => 'Projektantrag',
            'name_en' => 'Project Application',
            'plural_de' => 'Projektantr\u00e4ge',
            'plural_en' => 'Project Applications'
        ],

        self::PROJECTS => [
            'entity' => 'Project',
            'name_de' => 'Projekt',
            'name_en' => 'Project',
            'plural_de' => 'Projekte',
            'plural_en' => 'Projects'
        ],

        /**
         * @link https://help.sap.com/docs/SAP_ERP_SPV/01032ef9a74b4326998a66f9c408d6d2/8b8db853dcfcb44ce10000000a174cb4.html?locale=de-DE
         */
        self::PSP_ELEMENTS => [
            'entity' => 'PSP Element',
            'name_de' => 'Buchungselement',
            'name_en' => 'Booking Element',
            'plural_de' => 'Buchungselemente',
            'plural_en' => 'Booking elements'
        ],

        self::PUBLICATION_APPLICATIONS => [
            'entity' => 'Publication application',
            'name_de' => 'Publikationsantrag',
            'name_en' => 'Publication Application',
            'plural_de' => 'Publikationsanträge',
            'plural_en' => 'Publication Applications'
        ],

        // [X] Publicationen [KDSF:Pu4a]
        self::PUBLICATIONS => [
            'all' => 'data/entities/Publication',
            'attributes' => [
                'flag_audio',
                'flag_image',
                'flag_text',
                'flag_video'
            ],
            /*

-alternativeBookTitle
-cfAbstr
-cfEdition
-cfEndPage
-cfFedId
-cfISBN
-cfISSN
-cfIssue
-cfNameAbbrev
-cfNum
-srcPublType
-cfResPublDate
-cfSeries
-cfStartPage
-cfSubTitle
-cfTitle
-cfTotalPages
-cfURI
-cfVol
-chapter
-citAPA
-citHarvard
-comments
-crossRefId
-depositDone
-DOI
-icACCR
-icKcode
-icPercentile
-icPercentileDouble
-ID_PUBL
-idsId
-journalName
-kdsfdocumenttype
-openAccess
-pagesRange
-peerReviewed
-performanceType
-publicationatthm
-publisher
-publStatus
-publYear
-pubmedId
-purchaseTHM
-sourceOfInfo
-srcAddress
-srcAuthors
-srcBookTitle
-srcEditors
-srcIsiSubjCat
-srcJourName
-srcKeywords
-srcMonth
-srcOrganisation
-srcPublDate
-srcTitle
-srcYear
-swepubId
            */
            'entity' => 'Publication',
            'name_de' => 'Publikation',
            'name_en' => 'Publication',
            'plural_de' => 'Publikationen',
            'plural_en' => 'Publications'
        ],

        self::RDF_DOMAINS => [
            'entity' => 'RDFDomain',
            'name_de' => 'RDF Domain',
            'name_en' => 'RDF Domain',
            'plural_de' => 'RDF Domains',
            'plural_en' => 'RDF Domains'
        ],

        // Used for the REF Module to enable the researchers to suggest which publications to include in the REF submission.
        self::REF_CLAIMS => [
            'entity' => 'REFClaim',
            'name_de' => 'REF Claim',
            'name_en' => 'REF Claim',
            'plural_de' => 'REF Claims',
            'plural_en' => 'REF Claims'
        ],

        self::REGISTRATIONS => [
            'entity' => 'Registration',
            'name_de' => 'Registrierung',
            'name_en' => 'Registration',
            'plural_de' => 'Registrierungen',
            'plural_en' => 'Registration'
        ],

        self::REPORTS => [
            'entity' => 'Report',
            'name_de' => 'Report',
            'name_en' => 'Report',
            'plural_de' => 'Reports',
            'plural_en' => 'Reports'
        ],

        self::RESEARCH_FOCUSES => [
            'entity' => 'Research focus',
            'name_de' => 'Forschungsschwerpunkt',
            'name_en' => 'Research Focus',
            'plural_de' => 'Forschungsschwerpunkte',
            'plural_en' => 'Research Focuses'
        ],

        // Research networks of the HFD
        self::RESEARCH_NETWORK => [
            'entity' => 'researchNetwork',
            'name_de' => 'Forschungsverbund',
            'name_en' => 'Research Network',
            'plural_de' => 'Forschungsverbünde',
            'plural_en' => 'Research Networks'
        ],

        self::RESEARCH_RESULTS => [
            'entity' => 'ResearchResult',
            'name_de' => 'Forschungsergebnis',
            'name_en' => 'Research Result',
            'plural_de' => 'Forschungsergebnisse',
            'plural_en' => 'Research Results'
        ],

        self::ROLES => [
            'all' => 'data/entities/function',
            'entity' => 'function',
            'name_de' => 'Funktion',
            'name_en' => 'Function',
            'plural_de' => 'Funktionen',
            'plural_en' => 'Functions'
        ],

        // What the what is this?
        self::SEARCH_PROFILES => [
            'entity' => 'Search profile',
            'name_de' => 'Suchprofil',
            'name_en' => 'Search Profile',
            'plural_de' => 'Suchprofile',
            'plural_en' => 'Search Profiles'
        ],

        self::SERVICES => [
            'entity' => 'cfService',
            'name_de' => 'Dienstleistung',
            'name_en' => 'Service',
            'plural_de' => 'Dienstleistungen',
            'plural_en' => 'Services'
        ],

        /**
         * Classification according to the educational statistics of the Federal Statistical Office of Germany. (DESTATIS)
         * @link https://www.destatis.de/EN/Home/_node.html
         */
        self::STATISTICS_AREAS => [
            'entity' => 'StatisticsArea',
            'name_de' => 'DESTATIS',
            'name_en' => 'Official Statistics Area',
            'plural_de' => 'DESTATIS',
            'plural_en' => 'Official Statistics Areas'
        ],

        self::SUPERVISED_THESES => [
            'entity' => 'Supervised thesis',
            'name_de' => 'Betreute Abschlussarbeit',
            'name_en' => 'Supervised thesis',
            'plural_de' => 'Betreute Abschlussarbeiten',
            'plural_en' => 'Supervised thesis'
        ],

        // Keywords for semantic categorization
        self::TAGS => [
            'entity' => 'Tags',
            'name_de' => 'Schlagwort',
            'name_en' => 'Keyword',
            'plural_de' => 'Schlagwörter',
            'plural_en' => 'Keywords'
        ],

        // [X] Keeping track of tasks and other activities, often associated with project applications and projects.
        self::TASKS => [
            'entity' => 'Task',
            'name_de' => 'Aufgabe',
            'name_en' => 'Task',
            'plural_de' => 'Aufgaben',
            'plural_en' => 'Tasks'
        ],

        self::USAGE => [
            'entity' => 'Usage',
            'name_de' => 'Nutzung',
            'name_en' => 'Usage',
            'plural_de' => 'Nutzungen',
            'plural_en' => 'Usage'
        ],

        self::WOS_CATEGORIES => [
            'entity' => 'ISISubjCat',
            'name_de' => 'WoS Themenkategorie',
            'name_en' => 'WoS Subject Category',
            'plural_de' => 'Web of Science Themakategorien',
            'plural_en' => 'Web of Science Subject Categories'
        ],
    ];
}