<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use THM\Groups\Adapters\Application;

class Icons implements Selectable
{
    // Deprecated, divisive, frivolous, irrelevant, muddy water or vulgar icons
    private const SUPPRESSED = 0;

    // Named Contexts
    private const
        STRUCTURES = 1,
        DEVICES = 5,
        SYSTEM_ICONS = 10,

        // Copy, Cut, Paste, Redo, Undo...
        SYSTEM_FUNCTIONS = 15,

        // Browser/Page: Back, Bookmark, Forward, Login, Refresh...
        INTERNET_FUNCTIONS = 16,

        // Forward, Reply, Reply All
        MAIL_FUNCTIONS = 17,

        // Formatting, Spellchecking...
        WP_FUNCTIONS = 18,

        // Crop, Drop, Erase, Fill, Polygon...
        PAINT_FUNCTIONS = 19,
        SYMBOLS = 20,
        PLAYER_BUTTONS = 21,

        // Alpha, Number, Size...
        SORTS = 25,
        RESEARCH = 35,
        SCIENCE = 40,
        MANAGEMENT = 45,
        NAVIGATION = 50,
        NATURE = 55,
        WEATHER = 60,
        COMMUNICATION = 65,
        STREAMING = 70,
        MEDIA = 75,
        MEDICAL = 85,
        ACTIVITIES = 90,
        TRAVEL = 91,
        VEHICLES = 100,
        BATH = 105,
        // Emojis, Hand Gestures, Jobs...
        EXPRESSIONS_GESTURES = 120,

        // Presorted for potential future use.
        APPAREL = self::SUPPRESSED,
        CONSUMABLES = self::SUPPRESSED,
        POINTERS = self::SUPPRESSED,
        FINANCE = self::SUPPRESSED;

    /**
     * Control, Formatting, Structural Elements
     */
    private const PRIMARY = 1, SECONDARY = 2;


    public const ICONS = [
        '\e005' => [
            'class' => 'fa fa-faucet',
            'classes' => ['fa-faucet'],
            'content' => '\e005',
            'context' => self::BATH,
            'text_de' => 'Wasserhahn',
            'text_en' => 'Faucet'
        ],
        '\e007' => [
            'class' => 'fa fa-firefox-browser',
            'classes' => ['fa-firefox-browser'],
            'content' => '\e007',
            // Browsers are irrelevant and this logo is deprecated anyway.
            'context' => self::SUPPRESSED
        ],
        '\e013' => [
            'class' => 'fab fa-ideal',
            'classes' => ['fa-ideal'],
            'content' => '\e013',
            'context' => self::FINANCE,
            'text_de' => 'iDEAL',
            'text_en' => 'iDEAL'
        ],
        '\e01a' => [
            'class' => 'fab fa-microblog',
            'classes' => ['fa-microblog'],
            'content' => '\e01a',
            'context' => self::COMMUNICATION,
            'text_de' => 'Micro.blog',
            'text_en' => 'Micro.blog'
        ],
        '\e01e' => [
            'class' => 'fab fa-pied-piper-square',
            'classes' => ['fa-pied-piper-square'],
            'content' => '\e01e',
            // piedpipergroup.com: consulting => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e041' => [
            'class' => 'fa fa-trailer',
            'classes' => ['fa-trailer'],
            'content' => '\e041',
            'context' => self::VEHICLES,
            'text_de' => 'Anhänger',
            'text_en' => 'Trailer'
        ],
        '\e049' => [
            'class' => 'fab fa-unity',
            'classes' => ['fa-unity'],
            'content' => '\e049',
            // unity.com: gaming => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e052' => [
            'class' => 'fa fa-dailymotion',
            'classes' => ['fa-dailymotion'],
            'content' => '\e052',
            // icon is not displayed
            'context' => self::SUPPRESSED
        ],
        '\e055' => [
            'class' => 'fab fa-instagram-square',
            'classes' => ['fa-instagram-square'],
            'content' => '\e055',
            'context' => self::COMMUNICATION,
            'text_de' => 'Instagram, quadratisches Logo',
            'text_en' => 'Instagram, Square Logo'
        ],
        '\e056' => [
            'class' => 'fab fa-mixer',
            'classes' => ['fa-mixer'],
            'content' => '\e055',
            // mixer.com: streaming => deprecated
            'context' => self::SUPPRESSED
        ],
        '\e057' => [
            'class' => 'fab fa-shopify',
            'classes' => ['fa-shopify'],
            'content' => '\e057',
            // shopify.com online market development => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e059' => [
            'class' => 'fa fa-bacteria',
            'classes' => ['fa-bacteria'],
            'content' => '\e059',
            'context' => self::MEDICAL,
            'text_de' => 'Bakterien',
            'text_en' => 'Bacteria'
        ],
        '\e05a' => [
            'class' => 'fa fa-bacterium',
            'classes' => ['fa-bacterium'],
            'content' => '\e05a',
            'context' => self::MEDICAL,
            'text_de' => 'Bakterium',
            'text_en' => 'Bacterium'
        ],
        '\e05b' => [
            'class' => 'fa fa-box-tissue',
            'classes' => ['fa-box-tissue'],
            'content' => '\e05b',
            'context' => self::BATH,
            'text_de' => 'Taschentuchbox',
            'text_en' => 'Tissue Box'
        ],
        '\e05c' => [
            'class' => 'fa fa-hand-holding-medical',
            'classes' => ['fa-hand-holding-medical'],
            'content' => '\e05c',
            'context' => self::MEDICAL,
            'text_de' => 'vorgehaltener Hand mit medizinisches Kreuz',
            'text_en' => 'Cupping Hand mit Medical Cross'
        ],
        '\e05d' => [
            'class' => 'fa fa-hand-sparkles',
            'classes' => ['fa-hand-sparkles'],
            'content' => '\e05d',
            'context' => self::MEDICAL,
            'text_de' => 'Hand: Sauber',
            'text_en' => 'Hand: Clean'
        ],
        '\e05e' => [
            'class' => 'fa fa-hands-wash',
            'classes' => ['fa-hands-wash'],
            'content' => '\e05e',
            'context' => self::MEDICAL,
            'text_de' => 'Hände: Händewaschen',
            'text_en' => 'Hands: Washing'
        ],
        '\e05f' => [
            'class' => 'fa fa-handshake-alt-slash',
            'classes' => ['fa-handshake-alt-slash'],
            'content' => '\e05f',
            'context' => self::EXPRESSIONS_GESTURES,
            'text_de' => 'Händeschütteln',
            'text_en' => 'Handshake'
        ],
        '\e060' => [
            'class' => 'fa fa-handshake-slash',
            'classes' => ['fa-handshake-slash'],
            'content' => '\e060',
            'context' => self::EXPRESSIONS_GESTURES,
            'text_de' => 'Händeschütteln mit Schrägstrich',
            'text_en' => 'Handshake with Slash'
        ],
        '\e061' => [
            'class' => 'fa fa-head-side-cough',
            'classes' => ['fa-head-side-cough'],
            'content' => '\e061',
            'context' => self::MEDICAL,
            'text_de' => 'Husten',
            'text_en' => 'Coughing'
        ],
        '\e062' => [
            'class' => 'fa fa-head-side-cough-slash',
            'classes' => ['fa-head-side-cough-slash'],
            'content' => '\e062',
            'context' => self::MEDICAL,
            'text_de' => 'Husten mit Schrägstrich',
            'text_en' => 'Coughing with Slash'
        ],
        '\e063' => [
            'class' => 'fa fa-head-side-mask',
            'classes' => ['fa-head-side-mask'],
            'content' => '\e063',
            'context' => self::MEDICAL,
            'text_de' => 'medizinische Maske',
            'text_en' => 'Medical Mask'
        ],
        '\e064' => [
            'class' => 'fa fa-head-side-virus',
            'classes' => ['fa-head-side-virus'],
            'content' => '\e064',
            'context' => self::MEDICAL,
            'text_de' => 'Gehirnentzündung, ',
            'text_en' => 'Brain Infection'
        ],
        '\e065' => [
            'class' => 'fa fa-house-user',
            'classes' => ['fa-house-user'],
            'content' => '\e065',
            'context' => self::PRIMARY,
            'text_de' => 'Person im Haus',
            'text_en' => 'Person on House'
        ],
        '\e066' => [
            'class' => 'fa fa-laptop-house',
            'classes' => ['fa-laptop-house'],
            'content' => '\e066',
            'context' => self::PRIMARY,
            'text_de' => 'Home Office',
            'text_en' => 'Home Office'
        ],
        '\e067' => [
            'class' => 'fa fa-lungs-virus',
            'classes' => ['fa-lungs-virus'],
            'content' => '\e067',
            'context' => self::MEDICAL,
            'text_de' => 'Lungenentzündung',
            'text_en' => 'Lung Infection'
        ],
        '\e068' => [
            'class' => 'fa fa-people-arrows',
            'classes' => ['fa-people-arrows'],
            'content' => '\e068',
            'context' => self::MEDICAL,
            'text_de' => 'Social Distancing',
            'text_en' => 'Social Distancing'
        ],
        '\e069' => [
            'class' => 'fa fa-plane-slash',
            'classes' => ['fa-plane-slash'],
            'content' => '\e069',
            'context' => self::VEHICLES,
            'text_de' => 'Flugzeug mit Schrägstrich',
            'text_en' => 'Plane with Slash'
        ],
        '\e06a' => [
            'class' => 'fa fa-pump-medical',
            'classes' => ['fa-pump-medical'],
            'content' => '\e06a',
            'context' => self::MEDICAL,
            'text_de' => 'Desinfektionsmittel',
            'text_en' => 'Disinfectant'
        ],
        '\e06b' => [
            'class' => 'fa fa-pump-soap',
            'classes' => ['fa-pump-soap'],
            'content' => '\e06b',
            'context' => self::SECONDARY,
            'text_de' => 'Seife, flüssig',
            'text_en' => 'Soap, Liquid'
        ],
        '\e06c' => [
            'class' => 'fa fa-shield-virus',
            'classes' => ['fa-shield-virus'],
            'content' => '\e06c',
            'context' => self::MEDICAL,
            'text_de' => 'Antivirus',
            'text_en' => 'Antivirus'
        ],
        '\e06d' => [
            'class' => 'fa fa-sink',
            'classes' => ['fa-sink'],
            'content' => '\e06d',
            'context' => self::BATH,
            'text_de' => 'Waschbecken',
            'text_en' => 'Sink'
        ],
        '\e06e' => [
            'class' => 'fa fa-soap',
            'classes' => ['fa-soap'],
            'content' => '\e06e',
            'context' => self::SECONDARY,
            'text_de' => 'Seife, Stück',
            'text_en' => 'Soap, Bar'
        ],
        '\e06f' => [
            'class' => 'fa fa-stopwatch-20',
            'classes' => ['fa-stopwatch-20'],
            'content' => '\e06f',
            'context' => self::SECONDARY,
            'text_de' => 'Stoppuhr (20)',
            'text_en' => 'Stopwatch (20)'
        ],
        '\e070' => [
            'class' => 'fa fa-store-alt-slash',
            'classes' => ['fa-store-alt-slash'],
            'content' => '\e070',
            'context' => self::SECONDARY,
            'text_de' => 'Laden mit Schrägstrich',
            'text_en' => 'Store with Slash'
        ],
        '\e071' => [
            'class' => 'fa fa-store-slash',
            'classes' => ['fa-store-slash'],
            'content' => '\e071',
            'context' => self::SECONDARY,
            'text_de' => 'Marktstall mit Schrägstrich',
            'text_en' => 'Market Stall with Slash'
        ],
        '\e072' => [
            'class' => 'fa fa-toilet-paper-slash',
            'classes' => ['fa-toilet-paper-slash'],
            'content' => '\e072',
            'context' => self::BATH,
            'text_de' => 'Klopapier mit Schrägstrich',
            'text_en' => 'Toilet Paper with Slash'
        ],
        '\e073' => [
            'class' => 'fa fa-users-slash',
            'classes' => ['fa-users-slash'],
            'content' => '\e073',
            'context' => self::PRIMARY,
            'text_de' => 'Personen mit Schrägstrich',
            'text_en' => 'People with Slash'
        ],
        '\e074' => [
            'class' => 'fa fa-virus',
            'classes' => ['fa-virus'],
            'content' => '\e074',
            'context' => self::MEDICAL,
            'text_de' => 'Virus',
            'text_en' => 'Virus'
        ],
        '\e075' => [
            'class' => 'fa fa-virus-slash',
            'classes' => ['fa-virus-slash'],
            'content' => '\e075',
            'context' => self::MEDICAL,
            'text_de' => 'Virus mit Schrägstrich',
            'text_en' => 'Virus with Slash'
        ],
        '\e076' => [
            'class' => 'fa fa-viruses',
            'classes' => ['fa-viruses'],
            'content' => '\e076',
            'context' => self::MEDICAL,
            'text_de' => 'Viren',
            'text_en' => 'Viruses'
        ],
        '\e077' => [
            'class' => 'fab fa-deezer',
            'classes' => ['fa-deezer'],
            'content' => '\e077',
            'context' => self::MEDIA,
            'text_de' => 'Deezer',
            'text_en' => 'Deezer'
        ],
        '\e078' => [
            'class' => 'fab fa-edge-legacy',
            'classes' => ['fa-edge-legacy'],
            'content' => '\e078',
            // Browsers are irrelevant and this logo is deprecated anyway.
            'context' => self::SUPPRESSED
        ],
        '\e079' => [
            'class' => 'fab fa-google-pay',
            'classes' => ['fa-google-pay'],
            'content' => '\e079',
            'context' => self::FINANCE,
            'text_de' => 'Google Pay',
            'text_en' => 'Google Pay'
        ],
        '\e07a' => [
            'class' => 'fab fa-rust',
            'classes' => ['fa-rust'],
            'content' => '\e07a',
            // rust-lang.org programming language => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e07b' => [
            'class' => 'fab fa-tiktok',
            'classes' => ['fa-tiktok'],
            'content' => '\e07b',
            'context' => self::COMMUNICATION,
            'text_de' => 'TikTok',
            'text_en' => 'TikTok'
        ],
        '\e07c' => [
            'class' => 'fab fa-unsplash',
            'classes' => ['fa-unsplash'],
            'content' => '\e07c',
            'context' => self::MEDIA,
            'text_de' => 'Unsplash',
            'text_en' => 'Unsplash'
        ],
        '\e07d' => [
            'class' => 'fab fa-cloudflare',
            'classes' => ['fa-cloudflare'],
            'content' => '\e07d',
            // cloudflare.com internet security => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e07e' => [
            'class' => 'fab fa-guilded',
            'classes' => ['fa-guilded'],
            'content' => '\e07e',
            'context' => self::COMMUNICATION,
            'text_de' => 'Guilded',
            'text_en' => 'Guilded'
        ],
        '\e07f' => [
            'class' => 'fab fa-hive',
            'classes' => ['fa-hive'],
            'content' => '\e07f',
            'context' => self::MANAGEMENT,
            'text_de' => 'Hive',
            'text_en' => 'Hive'
        ],
        '\e080' => [
            'class' => 'fab fa-innosoft',
            'classes' => ['fa-innosoft'],
            'content' => '\e080',
            // innosoft.com internet services => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e081' => [
            'class' => 'fab fa-instalod',
            'classes' => ['fa-instalod'],
            'content' => '\e081',
            // instalod.com rendering technology => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e082' => [
            'class' => 'fab fa-octopus-deploy',
            'classes' => ['fa-octopus-deploy'],
            'content' => '\e082',
            // octopus.com deployment & integration => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e083' => [
            'class' => 'fab fa-perbyte',
            'classes' => ['fa-perbyte'],
            'content' => '\e083',
            // perbyte.com software consulting => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e084' => [
            'class' => 'fab fa-uncharted',
            'classes' => ['fa-uncharted'],
            'content' => '\e084',
            // muddy water
            'context' => self::SUPPRESSED
        ],
        '\e085' => [
            'class' => 'fa fa-vest',
            'classes' => ['fa-vest'],
            'content' => '\e085',
            'context' => self::APPAREL,
            'text_de' => 'Weste',
            'text_en' => 'Vest'
        ],
        '\e086' => [
            'class' => 'fa fa-vest-patches',
            'classes' => ['fa-vest-patches'],
            'content' => '\e086',
            'context' => self::APPAREL,
            'text_de' => 'Weste mit Aufnähern',
            'text_en' => 'Vest mit Patches'
        ],
        '\e087' => [
            'class' => 'fab fa-watchman-monitoring',
            'classes' => ['fa-watchman-monitoring'],
            'content' => '\e087',
            // watchmanmonitoring.com security software => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\e088' => [
            'class' => 'fab fa-wodu',
            'classes' => ['fa-wodu'],
            'content' => '\e088',
            // muddy water
            'context' => self::SUPPRESSED
        ],
        '\f000' => [
            'class' => 'fa fa-glass-martini',
            'classes' => ['fa-glass-martini'],
            'content' => '\f000',
            'context' => self::CONSUMABLES,
            'text_de' => 'Martini-Glas (leer)',
            'text_en' => 'Martini Glass (Empty)'
        ],
        '\f001' => [
            'class' => 'fa fa-music',
            'classes' => ['fa-music', 'icon-music'],
            'content' => '\f001',
            'context' => self::SECONDARY,
            'text_de' => 'Musiknoten',
            'text_en' => 'Musical Note'
        ],
        '\f002' => [
            'class' => 'fa fa-search',
            'classes' => ['fa-search', 'icon-search'],
            'content' => '\f002',
            'context' => self::PRIMARY,
            'text_de' => 'Lupe / Suche',
            'text_en' => 'Magnifying Glass / Search'
        ],
        '\f004' => [
            'class' => 'fa fa-heart',
            'classes' => ['fa-heart', 'icon-heart', 'icon-heart-2'],
            'content' => '\f004',
            'context' => self::EXPRESSIONS_GESTURES,
            'text_de' => 'Herz',
            'text_en' => 'Heart'
        ],
        '\f005' => [
            'class' => 'fa fa-star',
            'classes' => ['fa-star', 'icon-asterisk', 'icon-featured', 'icon-star', 'icon-star-empty'],
            'content' => '\f005',
            'context' => self::PRIMARY,
            'text_de' => 'Stern / Vorgestellt',
            'text_en' => 'Star / Featured '
        ],
        '\f007' => [
            'class' => 'fa fa-user',
            'classes' => ['fa-user', 'icon-user'],
            'content' => '\f007',
            'context' => self::PRIMARY,
            'text_de' => 'Person',
            'text_en' => 'Person'
        ],
        '\f008' => [
            'class' => 'fa fa-film',
            'classes' => ['fa-film'],
            'content' => '\f008',
            'context' => self::PRIMARY,
            'text_de' => 'Film',
            'text_en' => 'Film'
        ],
        '\f009' => [
            'class' => 'fa fa-th-large',
            'classes' => ['fa-th-large', 'icon-grid', 'icon-grid-view', 'icon-th-large'],
            'content' => '\f009',
            'context' => self::PRIMARY,
            'text_de' => 'Raster:  2 * 2',
            'text_en' => 'Grid: 2 * 2'
        ],
        '\f00a' => [
            'class' => 'fa fa-th',
            'classes' => ['fa-th', 'icon-grid-2', 'icon-grid-view-2', 'icon-th'],
            'content' => '\f00a',
            'context' => self::PRIMARY,
            'text_de' => 'Raster: 3 * 3',
            'text_en' => 'Grid: 3 * 3'
        ],
        '\f00b' => [
            'class' => 'fa fa-th-list',
            'classes' => ['fa-th-list'],
            'content' => '\f00b',
            'context' => self::PRIMARY,
            'text_de' => 'Liste, tabellarisch',
            'text_en' => 'List, Tabulated'
        ],
        '\f00c' => [
            'class' => 'fa fa-check',
            'classes' => ['fa-check', 'icon-check', 'icon-checkmark', 'icon-file-check', 'icon-ok', 'icon-publish'],
            'content' => '\f00c',
            'context' => self::PRIMARY,
            'text_de' => 'Häkchen',
            'text_en' => 'Checkmark'
        ],
        '\f00d' => [
            // fa deprecated => 'fa fa-xmark'
            'class' => 'fa fa-times',
            'classes' => [
                'fa-times',
                'icon-cancel',
                'icon-cancel-2',
                'icon-delete',
                'icon-file-remove',
                'icon-remove',
                'icon-times',
                'icon-unpublish'
            ],
            'content' => '\f00d',
            'context' => self::SYMBOLS,
            'text_de' => 'X-Mark',
            'text_en' => 'X-Mark'
        ],
        '\f00e' => [
            'class' => 'fa fa-search-plus',
            'classes' => ['fa-search-plus', 'icon-search-plus', 'icon-zoom-in'],
            'content' => '\f00e',
            'context' => self::PRIMARY,
            'text_de' => 'Lupe mit Plus / Hineinzoomen',
            'text_en' => 'Magnifying Glass with Plus / Zoom In'
        ],
        '\f010' => [
            'class' => 'fa fa-search-minus',
            'classes' => ['fa-search-minus', 'icon-search-minus', 'icon-zoom-out'],
            'content' => '\f010',
            'context' => self::PRIMARY,
            'text_de' => 'Lupe mit Minus / Rauszoomen',
            'text_en' => 'Magnifying Glass with Minus / Zoom Out'
        ],
        '\f011' => [
            'class' => 'fa fa-power-off',
            'classes' => ['fa-power-off', 'icon-power-off', 'icon-switch'],
            'content' => '\f011',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'An / Aus',
            'text_en' => 'On / Off'
        ],
        '\f012' => [
            'class' => 'fa fa-signal',
            'classes' => ['fa-signal'],
            'content' => '\f012',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Signalstärke',
            'text_en' => 'Signal Strength'
        ],
        '\f013' => [
            'class' => 'fa fa-cog',
            'classes' => ['fa-cog', 'icon-cog', 'icon-options'],
            'content' => '\f013',
            'context' => self::PRIMARY,
            'text_de' => 'Zahnrad / Optionen',
            'text_en' => 'Gear / Options'
        ],
        '\f015' => [
            'class' => 'fa fa-home',
            'classes' => ['fa-home', 'icon-default', 'icon-home', 'icon-home-2'],
            'content' => '\f015',
            'context' => self::PRIMARY,
            'text_de' => 'Haus',
            'text_en' => 'House'
        ],
        '\f017' => [
            'class' => 'fa fa-clock',
            'classes' => ['fa-clock', 'icon-clock'],
            'content' => '\f017',
            'context' => self::PRIMARY,
            'text_de' => 'Uhr',
            'text_en' => 'Clock'
        ],
        '\f018' => [
            'class' => 'fa fa-road',
            'classes' => ['fa-road'],
            'content' => '\f018',
            'context' => self::NAVIGATION,
            'text_de' => 'Straße',
            'text_en' => 'Road'
        ],
        '\f019' => [
            'class' => 'fa fa-download',
            'classes' => ['fa-download', 'icon-download'],
            'content' => '\f019',
            'context' => self::PRIMARY,
            'text_de' => 'Herunterladen',
            'text_en' => 'Download'
        ],
        '\f01c' => [
            'class' => 'fa fa-inbox',
            'classes' => ['fa-inbox'],
            'content' => '\f01c',
            'context' => self::MAIL_FUNCTIONS,
            'text_de' => 'Posteingang',
            'text_en' => 'Inbox'
        ],
        '\f01e' => [
            'class' => 'fa fa-redo',
            'classes' => ['fa-redo'],
            'content' => '\f01e',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Wiederholen, Strich-Pfeil',
            'text_en' => 'Redo, Line-Arrow'
        ],
        '\f021' => [
            'class' => 'fa fa-sync',
            'classes' => ['fa-sync', 'icon-loop', 'icon-redo-2', 'icon-refresh', 'icon-sync', 'icon-unblock'],
            'content' => '\f021',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Aktualisieren, Strich-Pfeile',
            'text_en' => 'Refresh, Line-Arrows'
        ],
        '\f022' => [
            'class' => 'fa fa-list-alt',
            'classes' => ['fa-list-alt'],
            'content' => '\f022',
            'context' => self::STRUCTURES,
            'text_de' => 'Liste, im Container',
            'text_en' => 'List, in Container'
        ],
        '\f023' => [
            'class' => 'fa fa-lock',
            'classes' => ['fa-lock', 'icon-checkedout', 'icon-lock', 'icon-locked', 'icon-protected'],
            'content' => '\f023',
            'context' => self::PRIMARY,
            'text_de' => 'Schloss / Geschützt',
            'text_en' => 'Lock / Protected'
        ],
        '\f024' => [
            'class' => 'fa fa-flag',
            'classes' => ['fa-flag', 'icon-flag', 'icon-flag-3'],
            'content' => '\f024',
            'text_de' => 'Flagge',
            'text_en' => 'Flag'
        ],
        '\f025' => [
            'class' => 'fa fa-headphones',
            'classes' => ['fa-headphones'],
            'content' => '\f025',
            'context' => self::DEVICES,
            'text_de' => 'Kopfhörer',
            'text_en' => 'Headphones'
        ],
        '\f026' => [
            'class' => 'fa fa-volume-off',
            'classes' => ['fa-volume-off'],
            'content' => '\f026',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Lautstärke, aus',
            'text_en' => 'Volume, Off'
        ],
        '\f027' => [
            'class' => 'fa fa-volume-down',
            'classes' => ['fa-volume-down'],
            'content' => '\f027',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Lautstärke, niedrig',
            'text_en' => 'Volume, Low'
        ],
        '\f028' => [
            'class' => 'fa fa-volume-up',
            'classes' => ['fa-volume-up'],
            'content' => '\f028',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Lautstärke, hoch',
            'text_en' => 'Volume, High'
        ],
        '\f029' => [
            'class' => 'fa fa-qrcode',
            'classes' => ['fa-qrcode'],
            'content' => '\f029',
            'text_de' => 'QR Code',
            'text_en' => 'QR Code'
        ],
        '\f02a' => [
            'class' => 'fa fa-barcode',
            'classes' => ['fa-barcode'],
            'content' => '\f02a',
            'text_de' => 'Strichcode',
            'text_en' => 'Barcode'
        ],
        '\f02b' => [
            'class' => 'fa fa-tag',
            'classes' => ['fa-tag', 'icon-tag', 'icon-tag-2'],
            'content' => '\f02b',
            'text_de' => 'Etikett / Schlagwort',
            'text_en' => 'Keyword / Tag'
        ],
        '\f02c' => [
            'class' => 'fa fa-tags',
            'classes' => ['fa-tags', 'icon-tags', 'icon-tags-2'],
            'content' => '\f02c',
            'text_de' => 'Etiketten / Schlagwörter',
            'text_en' => 'Keywords / Tags'
        ],
        '\f02d' => [
            'class' => 'fa fa-book',
            'classes' => ['fa-book', 'icon-book'],
            'content' => '\f02d',
            'text_de' => 'Buch',
            'text_en' => 'Book'
        ],
        '\f02e' => [
            'class' => 'fa fa-bookmark',
            'classes' => ['fa-bookmark', 'icon-bookmark', 'icon-bookmark-2'],
            'content' => '\f02e',
            'text_de' => 'Lesezeichen',
            'text_en' => 'Bookmark'
        ],
        '\f02f' => [
            'class' => 'fa fa-print',
            'classes' => ['fa-print', 'icon-print', 'icon-printer'],
            'content' => '\f02f',
            'context' => self::DEVICES,
            'text_de' => 'Druck',
            'text_en' => 'Print'
        ],
        '\f030' => [
            'class' => 'fa fa-camera',
            'classes' => ['fa-camera', 'icon-camera'],
            'content' => '\f030',
            'text_de' => 'Kamera',
            'text_en' => 'Camera'
        ],
        '\f031' => [
            'class' => 'fa fa-font',
            'classes' => ['fa-font'],
            'content' => '\f031',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Schriftart',
            'text_en' => 'Font'
        ],
        '\f032' => [
            'class' => 'fa fa-bold',
            'classes' => ['fa-bold'],
            'content' => '\f032',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Fett',
            'text_en' => 'Bold'
        ],
        '\f033' => [
            'class' => 'fa fa-italic',
            'classes' => ['fa-italic'],
            'content' => '\f033',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Kursiv',
            'text_en' => 'Italic'
        ],
        '\f034' => [
            'class' => 'fa fa-text-height',
            'classes' => ['fa-text-height'],
            'content' => '\f034',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Texthöhe',
            'text_en' => 'Text Height'
        ],
        '\f035' => [
            'class' => 'fa fa-text-width',
            'classes' => ['fa-text-width', 'icon-text-width'],
            'content' => '\f035',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Textbreite',
            'text_en' => 'Text Width'
        ],
        '\f036' => [
            'class' => 'fa fa-align-left',
            'classes' => ['fa-align-left', 'icon-paragraph-left'],
            'content' => '\f036',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Ausrichtung, links',
            'text_en' => 'Alignment, Left'
        ],
        '\f037' => [
            'class' => 'fa fa-align-center',
            'classes' => ['fa-align-center', 'icon-paragraph-center'],
            'content' => '\f037',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Ausrichtung, mittig',
            'text_en' => 'Alignment, Center'
        ],
        '\f038' => [
            'class' => 'fa fa-align-right',
            'classes' => ['fa-align-right', 'icon-paragraph-right'],
            'content' => '\f038',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Ausrichtung, rechts',
            'text_en' => 'Alignment, Right'
        ],
        '\f039' => [
            'class' => 'fa fa-align-justify',
            'classes' => ['fa-align-justify', 'icon-align-justify', 'icon-paragraph-justify'],
            'content' => '\f039',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Ausrichtung, Blocksatz',
            'text_en' => 'Alignment, Justify'
        ],
        '\f03a' => [
            'class' => 'fa fa-list',
            'classes' => ['fa-list', 'icon-list', 'icon-list-view'],
            'content' => '\f03a',
            'context' => self::STRUCTURES,
            'text_de' => 'Liste (Stichpunkte, eckig)',
            'text_en' => 'List (Bullets, Square)'
        ],
        '\f03b' => [
            'class' => 'fa fa-outdent',
            'classes' => ['fa-outdent'],
            'content' => '\f03b',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Ausrücken',
            'text_en' => 'Outdent'
        ],
        '\f03c' => [
            'class' => 'fa fa-indent',
            'classes' => ['fa-indent'],
            'content' => '\f03c',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Einrücken',
            'text_en' => 'Indent'
        ],
        '\f03d' => [
            'class' => 'fa fa-video',
            'classes' => ['fa-video', 'icon-camera-2', 'icon-video'],
            'content' => '\f03d',
            'context' => self::PRIMARY,
            'text_de' => 'Video',
            'text_en' => 'Video'
        ],
        '\f03e' => [
            'class' => 'fa fa-image',
            'classes' => ['fa-image', 'icon-image', 'icon-images', 'icon-picture', 'icon-pictures'],
            'content' => '\f03e',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Bild',
            'text_en' => 'Picture'
        ],
        '\f041' => [
            'class' => 'fa fa-map-marker',
            'classes' => ['fa-map-marker'],
            'content' => '\f041',
            'text_de' => 'Standort-Pin',
            'text_en' => 'Location-Pin'
        ],
        '\f042' => [
            'class' => 'fa fa-adjust',
            'classes' => ['fa-adjust'],
            'content' => '\f042',
            'context' => self::PRIMARY,
            'text_de' => 'Kreis (halbvoll, begrenzt)',
            'text_en' => 'Circle (Half-Full, Bordered)'
        ],
        '\f043' => [
            'class' => 'fa fa-tint',
            'classes' => ['fa-tint'],
            'content' => '\f043',
            'text_de' => 'Tropfen',
            'text_en' => 'Droplet'
        ],
        '\f044' => [
            'class' => 'fa fa-edit',
            'classes' => ['fa-edit', 'icon-edit', 'icon-pencil'],
            'content' => '\f044',
            'context' => self::PRIMARY,
            'text_de' => 'Stift im Quadrat / Bearbeiten',
            'text_en' => 'Pencil on Square / Edit'
        ],
        '\f048' => [
            'class' => 'fa fa-step-backward',
            'classes' => ['fa-step-backward', 'icon-arrow-first'],
            'content' => '\f048',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Vorherige',
            'text_en' => 'Previous'
        ],
        '\f049' => [
            'class' => 'fa fa-fast-backward',
            'classes' => ['fa-fast-backward', 'icon-arrow-last', 'icon-first'],
            'content' => '\f049',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Erste',
            'text_en' => 'First'
        ],
        '\f04a' => [
            'class' => 'fa fa-backward',
            'classes' => ['fa-backward', 'icon-backward', 'icon-previous'],
            'content' => '\f04a',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Zurückspulen',
            'text_en' => 'Rewind'
        ],
        '\f04b' => [
            'class' => 'fa fa-play',
            'classes' => ['fa-play', 'icon-play', 'icon-play-2', 'icon-video-2'],
            'content' => '\f04b',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Wiedergabe',
            'text_en' => 'Play'
        ],
        '\f04c' => [
            'class' => 'fa fa-pause',
            'classes' => ['fa-pause', 'icon-pause'],
            'content' => '\f04c',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Pause',
            'text_en' => 'Pause'
        ],
        '\f04d' => [
            'class' => 'fa fa-stop',
            'classes' => ['fa-stop', 'icon-stop'],
            'content' => '\f04d',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Stopp',
            'text_en' => 'Stop'
        ],
        '\f04e' => [
            'class' => 'fa fa-forward',
            'classes' => ['fa-forward', 'icon-forward'],
            'content' => '\f04e',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Vorspülen',
            'text_en' => 'Fast Forward'
        ],
        '\f050' => [
            'class' => 'fa fa-fast-forward',
            'classes' => ['fa-fast-forward', 'icon-last'],
            'content' => '\f050',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Letzte',
            'text_en' => 'Last'
        ],
        '\f051' => [
            'class' => 'fa fa-step-forward',
            'classes' => ['fa-step-forward'],
            'content' => '\f051',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Nächste',
            'text_en' => 'Next'
        ],
        '\f052' => [
            'class' => 'fa fa-eject',
            'classes' => ['fa-eject'],
            'content' => '\f052',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Auswurf',
            'text_en' => 'Eject'
        ],
        '\f053' => [
            'class' => 'fa fa-chevron-left',
            'classes' => ['fa-chevron-left', 'icon-arrow-left', 'icon-chevron-left', 'icon-leftarrow'],
            'content' => '\f053',
            'context' => self::POINTERS,
            'text_de' => 'Chevron, nach links',
            'text_en' => 'Chevron, Left'
        ],
        '\f054' => [
            'class' => 'fa fa-chevron-right',
            'classes' => ['fa-chevron-right', 'icon-arrow-right', 'icon-chevron-right', 'icon-rightarrow'],
            'content' => '\f054',
            'context' => self::POINTERS,
            'text_de' => 'Chevron, nach rechts',
            'text_en' => 'Chevron, Right'
        ],
        '\f055' => [
            'class' => 'fa fa-plus-circle',
            'classes' => ['fa-plus-circle', 'icon-plus-circle'],
            'content' => '\f055',
            'text_de' => 'Symbole: Plus im Kreis',
            'text_en' => 'Symbols: Plus on a Circle'
        ],
        '\f056' => [
            'class' => 'fa fa-minus-circle',
            'classes' => ['fa-minus-circle', 'icon-ban-circle', 'icon-expired', 'icon-minus-circle'],
            'content' => '\f056',
            'text_de' => 'Symbole: Minus im Kreis',
            'text_en' => 'Symbols: Minus on a Circle'
        ],
        '\f057' => [
            'class' => 'fa fa-times-circle',
            'classes' => ['fa-times-circle', 'icon-cancel-circle'],
            'content' => '\f057',
            'text_de' => 'Symbole: X im Kreis',
            'text_en' => 'Symbols: X on a Circle'
        ],
        '\f058' => [
            'class' => 'fa fa-check-circle',
            'classes' => [
                'fa-check-circle',
                'icon-check-circle',
                'icon-checkmark-2',
                'icon-checkmark-circle',
                'icon-radio-checked'
            ],
            'content' => '\f058',
            'text_de' => 'Symbole: Häkchen im Kreis',
            'text_en' => 'Symbols: Checkmark on a Circle'
        ],
        '\f059' => [
            'class' => 'fa fa-question-circle',
            'classes' => ['fa-question-circle', 'icon-question-2', 'icon-question-circle'],
            'content' => '\f059',
            'context' => self::SYMBOLS,
            'text_de' => 'Fragezeichen im Kreis',
            'text_en' => 'Question Mark on a Circle'
        ],
        '\f05a' => [
            'class' => 'fa fa-info-circle',
            'classes' => ['fa-info-circle', 'icon-info-2', 'icon-info-circle'],
            'content' => '\f05a',
            'text_de' => 'Symbole: I im Kreis',
            'text_en' => 'Symbols: I on a Circle'
        ],
        '\f05b' => [
            'class' => 'fa fa-crosshairs',
            'classes' => ['fa-crosshairs'],
            'content' => '\f05b',
            'text_de' => 'Symbole: Fadenkreuze',
            'text_en' => 'Symbols: Crosshairs'
        ],
        '\f05e' => [
            'class' => 'fa fa-ban',
            'classes' => ['fa-ban'],
            'content' => '\f05e',
            'text_de' => 'Symbole: Gesperrt',
            'text_en' => 'Symbols: Blocked'
        ],
        '\f060' => [
            'class' => 'fa fa-arrow-left',
            'classes' => ['fa-arrow-left', 'icon-arrow-left-4'],
            'content' => '\f060',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach links, breit',
            'text_en' => 'Arrow, Left, wide'
        ],
        '\f061' => [
            'class' => 'fa fa-arrow-right',
            'classes' => ['fa-arrow-right', 'icon-arrow-right-4'],
            'content' => '\f061',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach rechts, breit',
            'text_en' => 'Arrow, Right, wide'
        ],
        '\f062' => [
            'class' => 'fa fa-arrow-up',
            'classes' => ['fa-arrow-up', 'icon-arrow-up-4'],
            'content' => '\f062',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach oben, breit',
            'text_en' => 'Arrow, Up, wide'
        ],
        '\f063' => [
            'class' => 'fa fa-arrow-down',
            'classes' => ['fa-arrow-down', 'icon-arrow-down-4'],
            'content' => '\f063',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach unten, breit',
            'text_en' => 'Arrow, Down, wide'
        ],
        '\f064' => [
            'class' => 'fa fa-share',
            'classes' => ['fa-share', 'icon-redo', 'icon-share', 'icon-share-alt', 'icon-out'],
            'content' => '\f064',
            'context' => self::MAIL_FUNCTIONS,
            'text_de' => 'Weiterleiten',
            'text_en' => 'Forward'
        ],
        '\f065' => [
            'class' => 'fa fa-expand',
            'classes' => ['fa-expand', 'icon-expand'],
            'content' => '\f065',
            'text_de' => 'Steuerelemente: Vergrößern, Winkel',
            'text_en' => 'Controls: Expand, Angles'
        ],
        '\f066' => [
            'class' => 'fa fa-compress',
            'classes' => ['fa-compress', 'icon-contract'],
            'content' => '\f066',
            'text_de' => 'Steuerelemente: Verkleinern, Winkel',
            'text_en' => 'Controls: Compress, Angles'
        ],
        '\f067' => [
            // deprecated
            'class' => 'fa fa-plus',
            'classes' => [
                'fa-plus',
                'icon-add',
                'icon-collapse',
                'icon-file-add',
                'icon-file-plus',
                'icon-new',
                'icon-plus',
                'icon-plus-2',
                'icon-save-new'
            ],
            'content' => '\f067',
            'text_de' => 'Symbole: Plus',
            'text_en' => 'Symbols: Plus'
        ],
        '\f068' => [
            'class' => 'fa fa-minus',
            'classes' => [
                'fa-minus',
                'icon-file-minus',
                'icon-minus',
                'icon-minus-2',
                'icon-minus-sign',
                'icon-not-ok'
            ],
            'content' => '\f068',
            'text_de' => 'Minus Zeichen',
            'text_en' => 'Minus Sign'
        ],
        '\f069' => [
            // deprecated see /f621
            'class' => 'fa fa-asterisk',
            'classes' => ['fa-asterisk'],
            // unicode 2a,
            'content' => '\f069',
            'text_de' => 'Deprecated?',
            'text_en' => 'Deprecated?'
        ],
        '\f06a' => [
            'class' => 'fa fa-exclamation-circle',
            'classes' => [
                'fa-exclamation-circle',
                'icon-exclamation-circle',
                'icon-notification-2',
                'icon-notification-circle',
                'icon-warning-circle'
            ],
            'content' => '\f06a',
            'context' => self::SYMBOLS,
            'text_de' => 'Ausrufezeichen im Kreis',
            'text_en' => 'Exclamation Mark on a Circle'
        ],
        '\f06b' => [
            'class' => 'fa fa-gift',
            'classes' => ['fa-gift'],
            'content' => '\f06b',
            'text_de' => 'Geschenk',
            'text_en' => 'Gift'
        ],
        '\f06c' => [
            'class' => 'fa fa-leaf',
            'classes' => ['fa-leaf'],
            'content' => '\f06c',
            'text_de' => 'Blatt',
            'text_en' => 'Leaf'
        ],
        '\f06d' => [
            'class' => 'fa fa-fire',
            'classes' => ['fa-fire'],
            'content' => '\f06d',
            'text_de' => 'Feuer',
            'text_en' => 'Fire'
        ],
        '\f06e' => [
            'class' => 'fa fa-eye',
            'classes' => ['fa-eye', 'icon-eye', 'icon-eye-open', 'icon-hits'],
            'content' => '\f06e',
            'text_de' => 'Auge / Sehen / Sichtbar',
            'text_en' => 'Eye / See / Visible'
        ],
        '\f070' => [
            'class' => 'fa fa-eye-slash',
            'classes' => ['fa-eye-slash', 'icon-eye-2', 'icon-eye-blocked', 'icon-eye-close', 'icon-eye-slash'],
            'content' => '\f070',
            'text_de' => 'Auge (Schrägstrich) / Unsichtbar / Verstecken / Versteckt',
            'text_en' => 'Eye (Slash) / Invisible / Hidden /Hide'
        ],
        '\f071' => [
            'class' => 'fa fa-exclamation-triangle',
            'classes' => [
                'fa-exclamation-triangle',
                'icon-exclamation-triangle',
                'icon-pending',
                'icon-warning',
                'icon-warning-2'
            ],
            'content' => '\f071',
            'context' => self::SYMBOLS,
            'text_de' => 'Ausrufezeichen im Dreieck',
            'text_en' => 'Exclamation Mark on a Triangle'
        ],
        '\f072' => [
            'class' => 'fa fa-plane',
            'classes' => ['fa-plane'],
            'content' => '\f072',
            'text_de' => 'Flugzeug',
            'text_en' => 'Plane'
        ],
        '\f073' => [
            'class' => 'fa fa-calendar-alt',
            'classes' => ['fa-calendar-alt', 'icon-calendar', 'icon-calendar-alt'],
            'content' => '\f073',
            'text_de' => 'Kalender (Tage)',
            'text_en' => 'Calendar (Days)'
        ],
        '\f074' => [
            'class' => 'fa fa-random',
            'classes' => ['fa-random', 'icon-shuffle'],
            'content' => '\f074',
            'text_de' => 'Mischen / Zufällig',
            'text_en' => 'Random / Schuffle'
        ],
        '\f075' => [
            'class' => 'fa fa-comment',
            'classes' => ['fa-comment', 'icon-bubble-quote', 'icon-comment', 'icon-comments', 'icon-quote-3'],
            'content' => '\f075',
            'text_de' => 'Kommentar / Sprechblase',
            'text_en' => 'Comment / Speech Bubble'
        ],
        '\f076' => [
            'class' => 'fa fa-magnet',
            'classes' => ['fa-magnet'],
            'content' => '\f076',
            'text_de' => 'Magnet',
            'text_en' => 'Magnet'
        ],
        '\f077' => [
            'class' => 'fa fa-chevron-up',
            'classes' => ['fa-chevron-up', 'icon-arrow-up', 'icon-chevron-up', 'icon-uparrow'],
            'content' => '\f077',
            'context' => self::POINTERS,
            'text_de' => 'Chevron, nach oben',
            'text_en' => 'Chevron, Up'
        ],
        '\f078' => [
            'class' => 'fa fa-chevron-down',
            'classes' => ['fa-chevron-down', 'icon-arrow-down', 'icon-chevron-down', 'icon-downarrow'],
            'content' => '\f078',
            'context' => self::POINTERS,
            'text_de' => 'Chevron, nach unten',
            'text_en' => 'Chevron, Down'
        ],
        '\f079' => [
            'class' => 'fa fa-retweet',
            'classes' => ['fa-retweet'],
            'content' => '\f079',
            'text_de' => 'Retweet',
            'text_en' => 'Retweet'
        ],
        '\f07a' => [
            'class' => 'fa fa-shopping-cart',
            'classes' => ['fa-shopping-cart', 'icon-cart'],
            'content' => '\f07a',
            'text_de' => 'Einkaufswagen',
            'text_en' => 'Shopping Cart'
        ],
        '\f07b' => [
            'class' => 'fa fa-folder',
            'classes' => [
                'fa-folder',
                'icon-drawer-2',
                'icon-folder',
                'icon-folder-2',
                'icon-folder-close',
                'icon-folder-minus',
                'icon-folder-plus-2',
                'icon-folder-remove'
            ],
            'content' => '\f07b',
            'text_de' => 'Ordner / Ordner (zu)',
            'text_en' => 'Folder / Folder (Closed)'
        ],
        '\f07c' => [
            'class' => 'fa fa-folder-open',
            'classes' => [
                'fa-folder-open',
                'icon-drawer',
                'icon-folder-3',
                'icon-folder-open',
                'icon-folder-plus',
                'icon-unarchive'
            ],
            'content' => '\f07c',
            'text_de' => 'Dearchivieren / Ordner (offen)',
            'text_en' => 'Folder (Open) / Unarchive'
        ],
        '\f080' => [
            'class' => 'fa fa-chart-bar',
            'classes' => ['fa-chart-bar', 'icon-bars'],
            'content' => '\f080',
            'text_de' => 'Diagramm (Balken-)',
            'text_en' => 'Chart (Bar)'
        ],
        '\f081' => [
            'class' => 'fab fa-twitter-square',
            'classes' => ['fa-twitter-square'],
            'content' => '\f081',
            'text_de' => 'Logos: Twitter quadratisches Logo',
            'text_en' => 'Logos: Twitter Square Logo'
        ],
        '\f082' => [
            'class' => 'fab fa-facebook-square',
            'classes' => ['fa-facebook-square'],
            'content' => '\f082',
            'text_de' => 'Logos: Facebook quadratisches Logo',
            'text_en' => 'Logos: Facebook Square Logo'
        ],
        '\f083' => [
            'class' => 'fa fa-camera-retro',
            'classes' => ['fa-camera-retro'],
            'content' => '\f083',
            'text_de' => 'Kamera (Retro)',
            'text_en' => 'Camera (Retro)'
        ],
        '\f084' => [
            'class' => 'fa fa-key',
            'classes' => ['fa-key', 'icon-key'],
            'content' => '\f084',
            'text_de' => 'Schlüssel',
            'text_en' => 'Key'
        ],
        '\f085' => [
            'class' => 'fa fa-cogs',
            'classes' => ['fa-cogs', 'icon-cogs'],
            'content' => '\f085',
            'text_de' => 'Zahnräder',
            'text_en' => 'Gears'
        ],
        '\f086' => [
            'class' => 'fa fa-comments',
            'classes' => ['fa-comments', 'icon-comments-2'],
            'content' => '\f086',
            'text_de' => 'Kommentare / Sprechblasen',
            'text_en' => 'Comments / Speech Bubbles'
        ],
        '\f089' => [
            'class' => 'fa fa-star-half',
            'classes' => ['fa-star-half', 'icon-star-2'],
            'content' => '\f089',
            'text_de' => 'Stern (halb)',
            'text_en' => 'Star (Half)'
        ],
        '\f08c' => [
            'class' => 'fab fa-linkedin',
            'classes' => ['fa-linkedin'],
            'content' => '\f08c',
            'text_de' => 'Logos: LinkedIn quadratisches Logo',
            'text_en' => 'Logos: LinkedIn Square Logo'
        ],
        '\f08d' => [
            'class' => 'fa fa-thumbtack',
            'classes' => ['fa-thumbtack', 'icon-pin', 'icon-pushpin'],
            'content' => '\f08d',
            'text_de' => 'Reißzwecke / Stecknadel',
            'text_en' => 'Push Pin / Thumbtack'
        ],
        '\f091' => [
            'class' => 'fa fa-trophy',
            'classes' => ['fa-trophy', 'icon-trophy'],
            'content' => '\f091',
            'text_de' => 'Trophäe',
            'text_en' => 'Trophy'
        ],
        '\f092' => [
            'class' => 'fab fa-github-square',
            'classes' => ['fa-github-square'],
            'content' => '\f092',
            'text_de' => 'Logos: GitHub quadratisches Logo',
            'text_en' => 'Logos: GitHub Square Logo'
        ],
        '\f093' => [
            'class' => 'fa fa-upload',
            'classes' => ['fa-upload', 'icon-upload'],
            'content' => '\f093',
            'text_de' => 'Hochladen',
            'text_en' => 'Upload'
        ],
        '\f094' => [
            'class' => 'fa fa-lemon',
            'classes' => ['fa-lemon'],
            'content' => '\f094',
            'text_de' => 'Zitrone',
            'text_en' => 'Lemon'
        ],
        '\f095' => [
            'class' => 'fa fa-phone',
            'classes' => ['fa-phone', 'icon-phone-2'],
            'content' => '\f095',
            'context' => self::DEVICES,
            'text_de' => 'Telefon (rechts)',
            'text_en' => 'Telephone (Right)'
        ],
        '\f098' => [
            'class' => 'fa fa-phone-square',
            'classes' => ['fa-phone-square', 'icon-phone'],
            'content' => '\f098',
            'context' => self::DEVICES,
            'text_de' => 'Telefon (rechts, Quadrat)',
            'text_en' => 'Telephone (Right, Square)'
        ],
        '\f099' => [
            'class' => 'fab fa-twitter',
            'classes' => ['fa-twitter'],
            'content' => '\f099',
            'text_de' => 'Logos: Twitter',
            'text_en' => 'Logos: Twitter'
        ],
        '\f09a' => [
            'class' => 'fab fa-facebook',
            'classes' => ['fa-facebook'],
            'content' => '\f09a',
            'text_de' => 'Logos: Facebook (Kreis)',
            'text_en' => 'Logos: Facebook (Circle)'
        ],
        '\f09b' => [
            'class' => 'fab fa-github',
            'classes' => ['fa-github'],
            'content' => '\f09b',
            'text_de' => 'Logos: GitHub',
            'text_en' => 'Logos: GitHub'
        ],
        '\f09c' => [
            'class' => 'fa fa-unlock',
            'classes' => ['fa-unlock', 'icon-unlock'],
            'content' => '\f09c',
            'text_de' => 'Entsperren / Entsperrt',
            'text_en' => 'Unlock / Unlocked'
        ],
        '\f09d' => [
            'class' => 'fa fa-credit-card',
            'classes' => ['fa-credit-card', 'icon-credit', 'icon-credit-2'],
            'content' => '\f09d',
            'text_de' => 'Kreditkarte',
            'text_en' => 'Credit Card'
        ],
        '\f09e' => [
            'class' => 'fa fa-rss',
            'classes' => ['fa-rss', 'icon-rss'],
            'content' => '\f09e',
            'text_de' => 'RSS',
            'text_en' => 'RSS'
        ],
        '\f0a0' => [
            'class' => 'fa fa-hdd',
            'classes' => ['fa-hdd'],
            'content' => '\f0a0',
            'text_de' => 'HDD',
            'text_en' => 'HDD'
        ],
        '\f0a1' => [
            'class' => 'fa fa-bullhorn',
            'classes' => ['fa-bullhorn', 'icon-bullhorn'],
            'content' => '\f0a1',
            'text_de' => 'Megaphon',
            'text_en' => 'Megaphone'
        ],
        '\f0a3' => [
            'class' => 'fa fa-certificate',
            'classes' => ['fa-certificate'],
            'content' => '\f0a3',
            'text_de' => 'Siegel / Zertifikat',
            'text_en' => 'Certificate / Seal'
        ],
        '\f0a4' => [
            'class' => 'fa fa-hand-point-right',
            'classes' => ['fa-hand-point-right'],
            'content' => '\f0a4',
            'context' => self::POINTERS,
            'text_de' => 'Zeigefinger, nach rechts',
            'text_en' => 'Pointer Finger, Right'
        ],
        '\f0a5' => [
            'class' => 'fa fa-hand-point-left',
            'classes' => ['fa-hand-point-left'],
            'content' => '\f0a5',
            'context' => self::POINTERS,
            'text_de' => 'Zeigefinger, nach links',
            'text_en' => 'Pointer Finger, Left'
        ],
        '\f0a6' => [
            'class' => 'fa fa-hand-point-up',
            'classes' => ['fa-hand-point-up'],
            'content' => '\f0a6',
            'context' => self::POINTERS,
            'text_de' => 'Zeigefinger, nach oben',
            'text_en' => 'Pointer Finger, Up'
        ],
        '\f0a7' => [
            'class' => 'fa fa-hand-point-down',
            'classes' => ['fa-hand-point-down'],
            'content' => '\f0a7',
            'context' => self::POINTERS,
            'text_de' => 'Zeigefinger, nach unten',
            'text_en' => 'Pointer Finger, Down'
        ],
        '\f0a8' => [
            'class' => 'fa fa-arrow-circle-left',
            'classes' => ['fa-arrow-circle-left'],
            'content' => '\f0a8',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach links, breit, im Kreis',
            'text_en' => 'Arrow, Left, wide, on a Circle'
        ],
        '\f0a9' => [
            'class' => 'fa fa-arrow-circle-right',
            'classes' => ['fa-arrow-circle-right'],
            'content' => '\f0a9',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach rechts, breit, im Kreis',
            'text_en' => 'Arrow, Right, wide, on a Circle'
        ],
        '\f0aa' => [
            'class' => 'fa fa-arrow-circle-up',
            'classes' => ['fa-arrow-circle-up'],
            'content' => '\f0aa',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach oben, breit, im Kreis',
            'text_en' => 'Arrow, Up, wide, on a Circle'
        ],
        '\f0ab' => [
            'class' => 'fa fa-arrow-circle-down',
            'classes' => ['fa-arrow-circle-down'],
            'content' => '\f0ab',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach unten, breit, im Kreis',
            'text_en' => 'Arrow, Down, wide, on a Circle'
        ],
        '\f0ac' => [
            'class' => 'fa fa-globe',
            'classes' => ['fa-globe', 'icon-globe'],
            'content' => '\f0ac',
            'text_de' => 'Globus',
            'text_en' => 'Globe'
        ],
        '\f0ad' => [
            'class' => 'fa fa-wrench',
            'classes' => ['fa-wrench', 'icon-screwdriver', 'icon-tools', 'icon-wrench'],
            'content' => '\f0ad',
            'text_de' => 'Schraubenschlüssel',
            'text_en' => 'Wrench'
        ],
        '\f0ae' => [
            'class' => 'fa fa-tasks',
            'classes' => ['fa-tasks', 'icon-tasks'],
            'content' => '\f0ae',
            'text_de' => 'Aufgabenliste',
            'text_en' => 'Checklist'
        ],
        '\f0b0' => [
            'class' => 'fa fa-filter',
            'classes' => ['fa-filter', 'icon-filter'],
            'content' => '\f0b0',
            'text_de' => 'Filter / Trichter',
            'text_en' => 'Filter / Funnel'
        ],
        '\f0b1' => [
            'class' => 'fa fa-briefcase',
            'classes' => ['fa-briefcase', 'icon-briefcase'],
            'content' => '\f0b1',
            'text_de' => 'Aktentasche',
            'text_en' => 'Briefcase'
        ],
        '\f0b2' => [
            'class' => 'fa fa-arrows-alt',
            'classes' => ['fa-arrows-alt', 'icon-arrows-alt', 'icon-move'],
            'content' => '\f0b2',
            'text_de' => 'Bewegen / Verschieben',
            'text_en' => 'Move / Push'
        ],
        '\f0c0' => [
            'class' => 'fa fa-users',
            'classes' => ['fa-users', 'icon-users'],
            'content' => '\f0c0',
            'text_de' => 'Personen',
            'text_en' => 'People'
        ],
        '\f0c1' => [
            'class' => 'fa fa-link',
            'classes' => ['fa-link', 'icon-link'],
            'content' => '\f0c1',
            'text_de' => 'Verknüpfung / Glieder',
            'text_en' => 'Link / Links'
        ],
        '\f0c2' => [
            'class' => 'fa fa-cloud',
            'classes' => ['fa-cloud', 'icon-cloud'],
            'content' => '\f0c2',
            'text_de' => 'Wolke',
            'text_en' => 'Cloud'
        ],
        '\f0c3' => [
            'class' => 'fa fa-flask',
            'classes' => ['fa-flask'],
            'content' => '\f0c3',
            'text_de' => 'Glaskolben',
            'text_en' => 'Flask'
        ],
        '\f0c4' => [
            'class' => 'fa fa-cut',
            'classes' => ['fa-cut', 'icon-scissors'],
            'content' => '\f0c4',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Schneiden',
            'text_en' => 'Cut'
        ],
        '\f0c5' => [
            'class' => 'fa fa-copy',
            'classes' => ['fa-copy', 'icon-copy', 'icon-save-copy', 'icon-select-file', 'icon-stack'],
            'content' => '\f0c5',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Kopieren',
            'text_en' => 'Copy'
        ],
        '\f0c6' => [
            'class' => 'fa fa-paperclip',
            'classes' => ['fa-paperclip', 'icon-attachment', 'icon-flag-2', 'icon-paperclip'],
            'content' => '\f0c6',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Anhängen',
            'text_en' => 'Attach'
        ],
        '\f0c7' => [
            'class' => 'fa fa-save',
            'classes' => ['fa-save', 'icon-apply', 'icon-save'],
            'content' => '\f0c7',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Speichern',
            'text_en' => 'Save'
        ],
        '\f0c8' => [
            'class' => 'fa fa-square',
            'classes' => ['fa-square', 'icon-checkbox-partial', 'icon-checkbox-unchecked', 'icon-square'],
            'content' => '\f0c8',
            'text_de' => 'Quadrat (gefüllt, abgerundet)',
            'text_en' => 'Square (Filled, Rounded)'
        ],
        '\f0c9' => [
            'class' => 'fa fa-bars',
            'classes' => ['fa-bars', 'icon-menu'],
            'content' => '\f0c9',
            'text_de' => 'Balken (waagerecht) / Menü / Stapel (2D)',
            'text_en' => 'Bars (Horizontal) / Menu / Stack (2D)'
        ],
        '\f0ca' => [
            'class' => 'fa fa-list-ul',
            'classes' => ['fa-list-ul', 'icon-list-2'],
            'content' => '\f0ca',
            'text_de' => 'Liste (Stichpunkte)',
            'text_en' => 'List (Bullet Points)'
        ],
        '\f0cb' => [
            'class' => 'fa fa-list-ol',
            'classes' => ['fa-list-ol', 'icon-menu-3'],
            'content' => '\f0cb',
            'text_de' => 'Liste (nummeriert)',
            'text_en' => 'List (Numbered)'
        ],
        '\f0cc' => [
            'class' => 'fa fa-strikethrough',
            'classes' => ['fa-strikethrough'],
            'content' => '\f0cc',
            'text_de' => 'Durchgestrichen / Durchstreichen',
            'text_en' => 'Crossed-Out / Strikethrough'
        ],
        '\f0cd' => [
            'class' => 'fa fa-underline',
            'classes' => ['fa-underline'],
            'content' => '\f0cd',
            'text_de' => 'Unterstreichen / Unterstrichen',
            'text_en' => 'Underline / Underlined'
        ],
        '\f0ce' => [
            'class' => 'fa fa-table',
            'classes' => ['fa-table'],
            'content' => '\f0ce',
            'text_de' => 'Tabelle',
            'text_en' => 'Table'
        ],
        '\f0d0' => [
            'class' => 'fa fa-magic',
            'classes' => ['fa-magic', 'icon-wand'],
            'content' => '\f0d0',
            'text_de' => 'Zauberstab',
            'text_en' => 'Wand'
        ],
        '\f0d1' => [
            'class' => 'fa fa-truck',
            'classes' => ['fa-truck'],
            'content' => '\f0d1',
            'text_de' => 'Lastwagen (Klein)',
            'text_en' => 'Truck (Cargo)'
        ],
        '\f0d2' => [
            'class' => 'fab fa-pinterest',
            'classes' => ['fa-pinterest'],
            'content' => '\f0d2',
            'text_de' => 'Logos: Pinterest',
            'text_en' => 'Logos: Pinterest'
        ],
        '\f0d3' => [
            'class' => 'fab fa-pinterest-square',
            'classes' => ['fa-pinterest-square'],
            'content' => '\f0d3',
            'text_de' => 'Logos: Pinterest quadratisches Logo',
            'text_en' => 'Logos: Pinterest Square Logo'
        ],
        '\f0d4' => [
            'class' => 'fa fa-google-plus-square',
            'classes' => ['fa-google-plus-square'],
            'content' => '\f0d4'
        ],
        '\f0d5' => [
            'class' => 'fa fa-google-plus-g',
            'classes' => ['fa-google-plus-g'],
            'content' => '\f0d5'
        ],
        '\f0d6' => [
            'class' => 'fa fa-money-bill',
            'classes' => ['fa-money-bill'],
            'content' => '\f0d6',
            'text_de' => 'Geldschein',
            'text_en' => 'Bill'
        ],
        '\f0d7' => [
            'class' => 'fa fa-caret-down',
            'classes' => ['fa-caret-down', 'icon-arrow-down-3', 'icon-caret-down'],
            'content' => '\f0d7',
            'context' => self::POINTERS,
            'text_de' => 'Caret, nach unten',
            'text_en' => 'Caret, Down'
        ],
        '\f0d8' => [
            'class' => 'fa fa-caret-up',
            'classes' => ['fa-caret-up', 'icon-arrow-up-3', 'icon-caret-up'],
            'content' => '\f0d8',
            'context' => self::POINTERS,
            'text_de' => 'Caret, nach oben',
            'text_en' => 'Caret, Up'
        ],
        '\f0d9' => [
            'class' => 'fa fa-caret-left',
            'classes' => ['fa-caret-left', 'icon-arrow-left-3'],
            'content' => '\f0d9',
            'context' => self::POINTERS,
            'text_de' => 'Caret, nach links',
            'text_en' => 'Caret, Left'
        ],
        '\f0da' => [
            'class' => 'fa fa-caret-right',
            'classes' => ['fa-caret-right', 'icon-arrow-right-3', 'icon-caret-right'],
            'content' => '\f0da',
            'context' => self::POINTERS,
            'text_de' => 'Caret, nach rechts',
            'text_en' => 'Caret, Right'
        ],
        '\f0db' => [
            'class' => 'fa fa-columns',
            'classes' => ['fa-columns'],
            'content' => '\f0db',
            'context' => self::SECONDARY,
            'text_de' => 'Tabellenspalten',
            'text_en' => 'Table Columns'
        ],
        '\f0dc' => [
            'class' => 'fa fa-sort',
            'classes' => ['fa-sort', 'icon-menu-2', 'icon-sort'],
            'content' => '\f0dc',
            'context' => self::SORTS,
            'text_de' => 'Sort',
            'text_en' => 'Sort'
        ],
        '\f0dd' => [
            'class' => 'fa fa-sort-down',
            'classes' => ['fa-sort-down'],
            'content' => '\f0dd',
            'context' => self::SORTS,
            'text_de' => 'Sort: aufsteigend',
            'text_en' => 'Sort: Ascending'
        ],
        '\f0de' => [
            'class' => 'fa fa-sort-up',
            'classes' => ['fa-sort-up'],
            'content' => '\f0de',
            'context' => self::SORTS,
            'text_de' => 'Sort, absteigend',
            'text_en' => 'Sort, Descending'
        ],
        '\f0e0' => [
            'class' => 'fa fa-envelope',
            'classes' => ['fa-envelope', 'icon-envelope', 'icon-envelope-opened', 'icon-mail', 'icon-mail-2'],
            'content' => '\f0e0',
            'context' => self::PRIMARY,
            'text_de' => 'Umschlag / Post',
            'text_en' => 'Envelope / Mail'
        ],
        '\f0e1' => [
            'class' => 'fab fa-linkedin-in',
            'classes' => ['fa-linkedin-in'],
            'content' => '\f0e1',
            'context' => self::COMMUNICATION,
            'text_de' => 'LinkedIn',
            'text_en' => 'LinkedIn'
        ],
        '\f0e2' => [
            'class' => 'fa fa-undo',
            'classes' => ['fa-undo', 'icon-undo', 'icon-undo-2'],
            'content' => '\f0e2',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Zurücksetzen, Strich-Pfeil',
            'text_en' => 'Reset, Line-Arrow'
        ],
        '\f0e3' => [
            'class' => 'fa fa-gavel',
            'classes' => ['fa-gavel'],
            'content' => '\f0e3',
            'context' => self::SECONDARY,
            'text_de' => 'Hammer (Gerichtssaal)',
            'text_en' => 'Gavel'
        ],
        '\f0e7' => [
            'class' => 'fa fa-bolt',
            'classes' => ['fa-bolt', 'icon-bolt', 'icon-flash', 'icon-lightning'],
            'content' => '\f0e7',
            'text_de' => 'Blitz / Blitzlicht',
            'text_en' => 'Bolt / Flash / Lightning'
        ],
        '\f0e8' => [
            'class' => 'fa fa-sitemap',
            'classes' => ['fa-sitemap', 'icon-tree-2'],
            'content' => '\f0e8',
            'text_de' => 'Baum (Code) / Sitemap',
            'text_en' => 'Site / Tree (Code)'
        ],
        '\f0e9' => [
            'class' => 'fa fa-umbrella',
            'classes' => ['fa-umbrella'],
            'content' => '\f0e9',
            'text_de' => 'Regenschirm',
            'text_en' => 'Umbrella'
        ],
        '\f0ea' => [
            'class' => 'fa fa-paste',
            'classes' => ['fa-paste'],
            'content' => '\f0ea',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Einfügen',
            'text_en' => 'Paste'
        ],
        '\f0eb' => [
            'class' => 'fa fa-lightbulb',
            'classes' => ['fa-lightbulb', 'icon-lamp', 'icon-lightbulb'],
            'content' => '\f0eb',
            'text_de' => 'Glühbirne',
            'text_en' => 'Light Bulb'
        ],
        '\f0f0' => [
            'class' => 'fa fa-user-md',
            'classes' => ['fa-user-md'],
            'content' => '\f0f0',
            'context' => self::EXPRESSIONS_GESTURES,
            'text_de' => 'Arzt',
            'text_en' => 'Doctor'
        ],
        '\f0f1' => [
            'class' => 'fa fa-stethoscope',
            'classes' => ['fa-stethoscope'],
            'content' => '\f0f1',
            'context' => self::MEDICAL,
            'text_de' => 'Stethoskop',
            'text_en' => 'Stethoscope'
        ],
        '\f0f2' => [
            'class' => 'fa fa-suitcase',
            'classes' => ['fa-suitcase'],
            'content' => '\f0f2',
            'text_de' => 'Koffer',
            'text_en' => 'Suitcase'
        ],
        '\f0f3' => [
            'class' => 'fa fa-bell',
            'classes' => ['fa-bell', 'icon-bell'],
            'content' => '\f0f3',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Benachrichtigungen',
            'text_en' => 'Notifications'
        ],
        '\f0f4' => [
            'class' => 'fa fa-coffee',
            'classes' => ['fa-coffee'],
            'content' => '\f0f4',
            'text_de' => 'Kaffee / Tasse',
            'text_en' => 'Coffee / Mug'
        ],
        '\f0f8' => [
            'class' => 'fa fa-hospital',
            'classes' => ['fa-hospital'],
            'content' => '\f0f8',
            'text_de' => 'Krankenhaus',
            'text_en' => 'Hospital'
        ],
        '\f0f9' => [
            'class' => 'fa fa-ambulance',
            'classes' => ['fa-ambulance'],
            'content' => '\f0f9',
            'text_de' => 'Krankenwagen',
            'text_en' => 'Ambulance'
        ],
        '\f0fa' => [
            'class' => 'fa fa-medkit',
            'classes' => ['fa-medkit', 'icon-health'],
            'content' => '\f0fa',
            'text_de' => 'Koffer (medizinisch)',
            'text_en' => 'Suitcase (Medical)'
        ],
        '\f0fb' => [
            'class' => 'fa fa-fighter-jet',
            'classes' => ['fa-fighter-jet'],
            'content' => '\f0fb',
            'context' => self::VEHICLES,
            'text_de' => 'Düsenjäger',
            'text_en' => 'Fighter Jet'
        ],
        '\f0fc' => [
            'class' => 'fa fa-beer',
            'classes' => ['fa-beer'],
            'content' => '\f0fc',
            'context' => self::CONSUMABLES,
            'text_de' => 'Bier',
            'text_en' => 'Beer'
        ],
        '\f0fd' => [
            'class' => 'fa fa-h-square',
            'classes' => ['fa-h-square'],
            'content' => '\f0fd',
            'context' => self::SYMBOLS,
            'text_de' => 'H im Quadrat',
            'text_en' => 'H on a Square'
        ],
        '\f0fe' => [
            'class' => 'fa fa-plus-square',
            'classes' => ['fa-plus-square', 'icon-plus-square'],
            'content' => '\f0fe',
            'context' => self::SYMBOLS,
            'text_de' => 'Plus im Quadrat',
            'text_en' => 'Plus on a Square'
        ],
        '\f100' => [
            'class' => 'fa fa-angle-double-left',
            'classes' => ['fa-angle-double-left', 'icon-angle-double-left'],
            'content' => '\f100',
            'context' => self::POINTERS,
            'text_de' => 'Winkel, nach links, mehrzahl',
            'text_en' => 'Angles, Left'
        ],
        '\f101' => [
            'class' => 'fa fa-angle-double-right',
            'classes' => ['fa-angle-double-right', 'icon-angle-double-right'],
            'content' => '\f101',
            'context' => self::POINTERS,
            'text_de' => 'Winkel, nach rechts, mehrzahl',
            'text_en' => 'Angles, Right'
        ],
        '\f102' => [
            'class' => 'fa fa-angle-double-up',
            'classes' => ['fa-angle-double-up'],
            'content' => '\f102',
            'context' => self::POINTERS,
            'text_de' => 'Winkel, nach oben, mehrzahl',
            'text_en' => 'Angles, Up'
        ],
        '\f103' => [
            'class' => 'fa fa-angle-double-down',
            'classes' => ['fa-angle-double-down'],
            'content' => '\f103',
            'context' => self::POINTERS,
            'text_de' => 'Winkel, nach unten, mehrzahl',
            'text_en' => 'Angles, Down'
        ],
        '\f104' => [
            'class' => 'fa fa-angle-left',
            'classes' => ['fa-angle-left', 'icon-angle-left'],
            'content' => '\f104',
            'context' => self::POINTERS,
            'text_de' => 'Winkel, nach links',
            'text_en' => 'Angle, Left'
        ],
        '\f105' => [
            'class' => 'fa fa-angle-right',
            'classes' => ['fa-angle-right', 'icon-angle-right', 'icon-next'],
            'content' => '\f105',
            'context' => self::POINTERS,
            'text_de' => 'Winkel, nach rechts',
            'text_en' => 'Angle, Right'
        ],
        '\f106' => [
            'class' => 'fa fa-angle-up',
            'classes' => ['fa-angle-up', 'icon-angle-up'],
            'content' => '\f106',
            'context' => self::POINTERS,
            'text_de' => 'Winkel, nach oben',
            'text_en' => 'Angle, Up'
        ],
        '\f107' => [
            'class' => 'fa fa-angle-down',
            'classes' => ['fa-angle-down', 'icon-angle-down'],
            'content' => '\f107',
            'context' => self::POINTERS,
            'text_de' => 'Winkel, nach unten',
            'text_en' => 'Angle, Down'
        ],
        '\f108' => [
            'class' => 'fa fa-desktop',
            'classes' => ['fa-desktop', 'icon-desktop', 'icon-screen'],
            'content' => '\f108',
            'context' => self::DEVICES,
            'text_de' => 'Bildschirm',
            'text_en' => 'Screen'
        ],
        '\f109' => [
            'class' => 'fa fa-laptop',
            'classes' => ['fa-laptop'],
            'content' => '\f109',
            'context' => self::DEVICES,
            'text_de' => 'Laptop',
            'text_en' => 'Laptop'
        ],
        '\f10a' => [
            'class' => 'fa fa-tablet',
            'classes' => ['fa-tablet', 'icon-tablet'],
            'content' => '\f10a',
            'context' => self::DEVICES,
            'text_de' => 'Tablet, Bildschirmlos',
            'text_en' => 'Tablet, Screenless'
        ],
        '\f10b' => [
            'class' => 'fa fa-mobile',
            'classes' => ['fa-mobile', 'icon-mobile'],
            'content' => '\f10b',
            'context' => self::DEVICES,
            'text_de' => 'Mobile, Bildschirmlos',
            'text_en' => 'Mobile, Screenless'
        ],
        '\f10d' => [
            'class' => 'fa fa-quote-left',
            'classes' => ['fa-quote-left', 'icon-quote', 'icon-quotes-left'],
            'content' => '\f10d',
            'context' => self::SYMBOLS,
            'text_de' => 'Anführungszeichen, linke Seite',
            'text_en' => 'Quotation Marks, Left-Hand'
        ],
        '\f10e' => [
            'class' => 'fa fa-quote-right',
            'classes' => ['fa-quote-right', 'icon-quote-2', 'icon-quotes-right'],
            'content' => '\f10e',
            'context' => self::SYMBOLS,
            'text_de' => 'Anführungszeichen, rechte Seite',
            'text_en' => 'Quotation Marks, Right-Hand'
        ],
        '\f110' => [
            'class' => 'fa fa-spinner',
            'classes' => ['fa-spinner', 'icon-loading', 'icon-spinner'],
            'content' => '\f110',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'wird geladen',
            'text_en' => 'Loading'
        ],
        '\f111' => [
            'class' => 'fa fa-circle',
            'classes' => ['fa-circle', 'icon-circle', 'icon-radio-unchecked', 'icon-unfeatured'],
            'content' => '\f111',
            'text_de' => 'Kreis / Radio-Knopf (deaktiviert)',
            'text_en' => 'Quadrat / Radio-Button (unchecked)'
        ],
        '\f113' => [
            'class' => 'fab fa-github-alt',
            'classes' => ['fa-github-alt'],
            'content' => '\f113',
            'text_de' => 'Logos: GitHub (Kopf)',
            'text_en' => 'Logos: GitHub (Head)'
        ],
        '\f118' => [
            'class' => 'fa fa-smile',
            'classes' => [
                'fa-smile',
                'icon-smiley',
                'icon-smiley-2',
                'icon-smiley-happy',
                'icon-smiley-happy-2',
                'icon-smiley-neutral',
                'icon-smiley-neutral-2'
            ],
            'content' => '\f118'
        ],
        '\f119' => [
            'class' => 'fa fa-frown',
            'classes' => ['fa-frown', 'icon-smiley-sad', 'icon-smiley-sad-2'],
            'content' => '\f119',
            'text_de' => 'Schmollend / Traurig',
            'text_en' => 'Frowning / Sad'
        ],
        '\f11a' => [
            'class' => 'fa fa-meh',
            'classes' => ['fa-meh'],
            'content' => '\f11a',
            'text_de' => 'Naja',
            'text_en' => 'Meh'
        ],
        '\f11b' => [
            'class' => 'fa fa-gamepad',
            'classes' => ['fa-gamepad'],
            'content' => '\f11b',
            'text_de' => 'Controller',
            'text_en' => 'Gamepad'
        ],
        '\f11c' => [
            'class' => 'fa fa-keyboard',
            'classes' => ['fa-keyboard'],
            'content' => '\f11c',
            'text_de' => 'Tastatur',
            'text_en' => 'Keyboard'
        ],
        '\f11e' => [
            'class' => 'fa fa-flag-checkered',
            'classes' => ['fa-flag-checkered'],
            'content' => '\f11e',
            'text_de' => 'Flagge (kariert)',
            'text_en' => 'Flag (Checkered)'
        ],
        '\f120' => [
            'class' => 'fa fa-terminal',
            'classes' => ['fa-terminal'],
            'content' => '\f120',
            'text_de' => 'Informatik: Terminal',
            'text_en' => 'Computing: Terminal'
        ],
        '\f121' => [
            'class' => 'fa fa-code',
            'classes' => ['fa-code', 'icon-code'],
            'content' => '\f121',
            'text_de' => 'Informatik: Code, Markup',
            'text_en' => 'Computing: Code,  Markup'
        ],
        '\f122' => [
            'class' => 'fa fa-reply-all',
            'classes' => ['fa-reply-all'],
            'content' => '\f122',
            'context' => self::MAIL_FUNCTIONS,
            'text_de' => 'Antworten, alle',
            'text_en' => 'Reply, All'
        ],
        '\f124' => [
            'class' => 'fa fa-location-arrow',
            'classes' => ['fa-location-arrow'],
            'content' => '\f124',
            'text_de' => 'Pfeil (Standort)',
            'text_en' => 'Arrow (Location)'
        ],
        '\f125' => [
            'class' => 'fa fa-crop',
            'classes' => ['fa-crop', 'icon-crop'],
            'content' => '\f125',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Zuschneiden, 3D',
            'text_en' => 'Crop, 3D'
        ],
        '\f126' => [
            'class' => 'fa fa-code-branch',
            'classes' => ['fa-code-branch', 'icon-code-branch', 'icon-tree'],
            'content' => '\f126',
            'text_de' => 'Ast (Code)',
            'text_en' => 'Branch (Code)'
        ],
        '\f127' => [
            'class' => 'fa fa-unlink',
            'classes' => ['fa-unlink'],
            'content' => '\f127',
            'text_de' => 'Verknüpfung / Glieder (Schrägstrich)',
            'text_en' => 'Link / Links (Slash)'
        ],
        '\f128' => [
            // deprecated
            'class' => 'fa fa-question',
            'classes' => ['fa-question', 'icon-help', 'icon-question', 'icon-question-sign'],
            // unicode 3f
            'content' => '\f128',
            'context' => self::SYMBOLS,
            'text_de' => 'Fragezeichen',
            'text_en' => 'Question Mark'
        ],
        '\f129' => [
            'class' => 'fa fa-info',
            'classes' => ['fa-info', 'icon-info'],
            'content' => '\f129',
            'context' => self::SYMBOLS,
            'text_de' => 'I',
            'text_en' => 'I'
        ],
        '\f12a' => [
            // deprecated
            'class' => 'fa fa-exclamation',
            'classes' => ['fa-exclamation', 'icon-error', 'icon-exclamation', 'icon-notification'],
            //'content' => unicode 21,
            'content' => '\f12a',
            'context' => self::SYMBOLS,
            'text_de' => 'Ausrufezeichen',
            'text_en' => 'Exclamation Mark'
        ],
        '\f12b' => [
            'class' => 'fa fa-superscript',
            'classes' => ['fa-superscript'],
            'content' => '\f12b',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Hochstellen',
            'text_en' => 'Superscript'
        ],
        '\f12c' => [
            'class' => 'fa fa-subscript',
            'classes' => ['fa-subscript'],
            'content' => '\f12c',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Niederstellen',
            'text_en' => 'Subscript'
        ],
        '\f12d' => [
            'class' => 'fa fa-eraser',
            'classes' => ['fa-eraser'],
            'content' => '\f12d',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Radierer',
            'text_en' => 'Eraser'
        ],
        '\f12e' => [
            'class' => 'fa fa-puzzle-piece',
            'classes' => ['fa-puzzle-piece', 'icon-puzzle', 'icon-puzzle-piece'],
            'content' => '\f12e',
            'text_de' => 'Puzzle / Puzzleteil',
            'text_en' => 'Puzzle / Puzzle Piece'
        ],
        '\f130' => [
            'class' => 'fa fa-microphone',
            'classes' => ['fa-microphone'],
            'content' => '\f130',
            'context' => self::DEVICES,
            'text_de' => 'Mikrofon',
            'text_en' => 'Microphone'
        ],
        '\f131' => [
            'class' => 'fa fa-microphone-slash',
            'classes' => ['fa-microphone-slash'],
            'content' => '\f131',
            'context' => self::DEVICES,
            'text_de' => 'Mikrofon mit Schrägstrich',
            'text_en' => 'Microphone with Slash'
        ],
        '\f133' => [
            'class' => 'fa fa-calendar',
            'classes' => ['fa-calendar', 'icon-calendar-3'],
            'content' => '\f133',
            'text_de' => 'Kalender',
            'text_en' => 'Calendar'
        ],
        '\f134' => [
            'class' => 'fa fa-fire-extinguisher',
            'classes' => ['fa-fire-extinguisher'],
            'content' => '\f134',
            'text_de' => 'Feuerlöscher',
            'text_en' => 'Fire Extinguisher'
        ],
        '\f135' => [
            'class' => 'fa fa-rocket',
            'classes' => ['fa-rocket'],
            'content' => '\f135',
            'text_de' => 'Rakete',
            'text_en' => 'Rocket'
        ],
        '\f136' => [
            'class' => 'fab fa-maxcdn',
            'classes' => ['fa-maxcdn'],
            'content' => '\f136'
        ],
        '\f137' => [
            'class' => 'fa fa-chevron-circle-left',
            'classes' => ['fa-chevron-circle-left', 'icon-backward-circle'],
            'content' => '\f137',
            'context' => self::POINTERS,
            'text_de' => 'Chevron, nach links, im Kreis',
            'text_en' => 'Chevron, Left, on a Circle',
        ],
        '\f138' => [
            'class' => 'fa fa-chevron-circle-right',
            'classes' => ['fa-chevron-circle-right', 'icon-forward-circle'],
            'content' => '\f138',
            'context' => self::POINTERS,
            'text_de' => 'Chevron, nach rechts, im Kreis',
            'text_en' => 'Chevron, Right, on a Circle',
        ],
        '\f139' => [
            'class' => 'fa fa-chevron-circle-up',
            'classes' => ['fa-chevron-circle-up'],
            'content' => '\f139',
            'context' => self::POINTERS,
            'text_de' => 'Chevron, nach oben, im Kreis',
            'text_en' => 'Chevron, Up, on a Circle',
        ],
        '\f13a' => [
            'class' => 'fa fa-chevron-circle-down',
            'classes' => ['fa-chevron-circle-down'],
            'content' => '\f13a',
            'context' => self::POINTERS,
            'text_de' => 'Chevron, nach unten, im Kreis',
            'text_en' => 'Chevron, Down, on a Circle',
        ],
        '\f13b' => [
            'class' => 'fa fa-html5',
            'classes' => ['fa-html5'],
            'content' => '\f13b'
        ],
        '\f13c' => [
            'class' => 'fa fa-css3',
            'classes' => ['fa-css3'],
            'content' => '\f13c'
        ],
        '\f13d' => [
            'class' => 'fa fa-anchor',
            'classes' => ['fa-anchor'],
            'content' => '\f13d',
            'text_de' => 'Anker',
            'text_en' => 'Anchor'
        ],
        '\f13e' => [
            'class' => 'fa fa-unlock-alt',
            'classes' => ['fa-unlock-alt', 'icon-unlock-alt'],
            'content' => '\f13e',
            'text_de' => 'Entsperren / Entsprerrt (Schlüsselloch)',
            'text_en' => 'Unlock / Unlocked (Key Hole)'
        ],
        '\f140' => [
            'class' => 'fa fa-bullseye',
            'classes' => ['fa-bullseye'],
            'content' => '\f140',
            'text_de' => 'Zielscheibe',
            'text_en' => 'Bullseye'
        ],
        '\f141' => [
            'class' => 'fa fa-ellipsis-h',
            'classes' => ['fa-ellipsis-h', 'icon-ellipsis-h'],
            'content' => '\f141',
            'text_de' => 'Symbole: Ellipse, waagerecht',
            'text_en' => 'Symbols: Ellipsis, Horizontal'
        ],
        '\f142' => [
            'class' => 'fa fa-ellipsis-v',
            'classes' => ['fa-ellipsis-v', 'icon-ellipsis-v'],
            'content' => '\f142',
            'text_de' => 'Symbole: Ellipse, senkrecht',
            'text_en' => 'Symbols: Ellipsis, Vertical'
        ],
        '\f143' => [
            'class' => 'fa fa-rss-square',
            'classes' => ['fa-rss-square', 'icon-feed'],
            'content' => '\f143',
            'text_de' => 'RSS (Quadrat)',
            'text_en' => 'RSS (Square)'
        ],
        '\f144' => [
            'class' => 'fa fa-play-circle',
            'classes' => ['fa-play-circle', 'icon-play-circle'],
            'content' => '\f144',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Wiedergabe im Kreis',
            'text_en' => 'Play on a Circle'
        ],
        '\f146' => [
            'class' => 'fa fa-minus-square',
            'classes' => ['fa-minus-square'],
            'content' => '\f146',
            'text_de' => 'Symbole: Minus im Quadrat',
            'text_en' => 'Symbols: Minus on a Square'
        ],
        '\f14a' => [
            'class' => 'fa fa-check-square',
            'classes' => [
                'fa-check-square',
                'icon-check-square',
                'icon-checkbox',
                'icon-checkbox-checked',
                'icon-checkin',
                'icon-success'
            ],
            'content' => '\f14a',
            'text_de' => 'Checkbox (angehakt) / Erledigt / Häkchen (Quadrat)',
            'text_en' => 'Checkbox (Checked) / Checkmark (Square) / Done'
        ],
        '\f14b' => [
            'class' => 'fa fa-pen-square',
            'classes' => ['fa-pen-square', 'icon-pen-square'],
            'content' => '\f14b',
            'text_de' => 'Stift (Quadrat)',
            'text_en' => 'Pencil (Square)'
        ],
        '\f14d' => [
            'class' => 'fa fa-share-square',
            'classes' => ['fa-share-square'],
            'content' => '\f14d',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Weiterleiten im Quadrat',
            'text_en' => 'Forward, on a Square'
        ],
        '\f14e' => [
            'class' => 'fa fa-compass',
            'classes' => ['fa-compass', 'icon-compass'],
            'content' => '\f14e',
            'text_de' => 'Kompass',
            'text_en' => 'Compass'
        ],
        '\f150' => [
            'class' => 'fa fa-caret-square-down',
            'classes' => ['fa-caret-square-down'],
            'content' => '\f150',
            'context' => self::POINTERS,
            'text_de' => 'Caret, nach unten, im Quadrat',
            'text_en' => 'Caret, down, on a Square'
        ],
        '\f151' => [
            'class' => 'fa fa-caret-square-up',
            'classes' => ['fa-caret-square-up'],
            'content' => '\f151',
            'context' => self::POINTERS,
            'text_de' => 'Caret, nach oben, im Quadrat',
            'text_en' => 'Caret, Up, on a Square'
        ],
        '\f152' => [
            'class' => 'fa fa-caret-square-right',
            'classes' => ['fa-caret-square-right'],
            'content' => '\f152',
            'context' => self::POINTERS,
            'text_de' => 'Caret, nach rechts, im Quadrat',
            'text_en' => 'Caret, Right, on a Square'
        ],
        '\f153' => [
            'class' => 'fa fa-euro-sign',
            'classes' => ['fa-euro-sign'],
            'content' => '\f153',
            'context' => self::FINANCE,
            'text_de' => 'Euro',
            'text_en' => 'Euro'
        ],
        '\f154' => [
            'class' => 'fa fa-pound-sign',
            'classes' => ['fa-pound-sign'],
            'content' => '\f154',
            'context' => self::FINANCE,
            'text_de' => 'Pfund (Sterling)',
            'text_en' => 'Pound (Sterling)'
        ],
        '\f155' => [
            'class' => 'fa fa-dollar-sign',
            'classes' => ['fa-dollar-sign'],
            'content' => '\f155',
            'context' => self::FINANCE,
            'text_de' => 'Dollar',
            'text_en' => 'Dollar'
        ],
        '\f156' => [
            'class' => 'fa fa-rupee-sign',
            'classes' => ['fa-rupee-sign'],
            'content' => '\f156',
            'context' => self::FINANCE,
            'text_de' => 'Rupie',
            'text_en' => 'Rupee'
        ],
        '\f157' => [
            'class' => 'fa fa-yen-sign',
            'classes' => ['fa-yen-sign'],
            'content' => '\f157',
            'context' => self::FINANCE,
            'text_de' => 'Yen',
            'text_en' => 'Yen'
        ],
        '\f158' => [
            'class' => 'fa fa-ruble-sign',
            'classes' => ['fa-ruble-sign'],
            'content' => '\f158',
            'context' => self::FINANCE,
            'text_de' => 'Rubel',
            'text_en' => 'Ruble'
        ],
        '\f159' => [
            'class' => 'fa fa-won-sign',
            'classes' => ['fa-won-sign'],
            'content' => '\f159',
            'context' => self::FINANCE,
            'text_de' => 'Won',
            'text_en' => 'Won'
        ],
        '\f15a' => [
            'class' => 'fab fa-btc',
            'classes' => ['fa-btc'],
            'content' => '\f15a',
            'context' => self::FINANCE,
            'text_de' => 'Bitcoin',
            'text_en' => 'Bitcoin'
        ],
        '\f15b' => [
            'class' => 'fa fa-file',
            'classes' => ['fa-file', 'icon-file-2', 'icon-file'],
            'content' => '\f15b',
            'text_de' => 'Datei',
            'text_en' => 'File'
        ],
        '\f15c' => [
            'class' => 'fa fa-file-alt',
            'classes' => ['fa-file-alt', 'icon-file-alt'],
            'content' => '\f15c',
            'text_de' => 'Datei (Text)',
            'text_en' => 'File (Text)'
        ],
        '\f15d' => [
            'class' => 'fa fa-sort-alpha-down',
            'classes' => ['fa-sort-alpha-down'],
            'content' => '\f15d',
            'context' => self::SORTS,
            'text_de' => 'Sort, alphabetisch, aufsteigend',
            'text_en' => 'Sort, Alphabetical, Ascending'
        ],
        '\f15e' => [
            'class' => 'fa fa-sort-alpha-up',
            'classes' => ['fa-sort-alpha-up'],
            'content' => '\f15e',
            'context' => self::SORTS,
            'text_de' => 'Sort, alphabetisch, absteigend',
            'text_en' => 'Sort, Alphabetical, Descending'
        ],
        '\f160' => [
            'class' => 'fa fa-sort-amount-down',
            'classes' => ['fa-sort-amount-down'],
            'content' => '\f160',
            'context' => self::SORTS,
            'text_de' => 'Sort, nach Größe, absteigend, verkehrt',
            'text_en' => 'Sort, Amount, Descending, Reversed'
        ],
        '\f161' => [
            'class' => 'fa fa-sort-amount-up',
            'classes' => ['fa-sort-amount-up'],
            'content' => '\f161',
            'context' => self::SORTS,
            'text_de' => 'Sort, nach Größe, aufsteigend, verkehrt',
            'text_en' => 'Sort, Amount, Ascending, Reversed'
        ],
        '\f162' => [
            'class' => 'fa fa-sort-numeric-down',
            'classes' => ['fa-sort-numeric-down'],
            'content' => '\f162',
            'context' => self::SORTS,
            'text_de' => 'Sort, nummerisch, aufsteigend',
            'text_en' => 'Sort, Numerical, Ascending'
        ],
        '\f163' => [
            'class' => 'fa fa-sort-numeric-up',
            'classes' => ['fa-sort-numeric-up'],
            'content' => '\f163',
            'context' => self::SORTS,
            'text_de' => 'Sort, nummerisch, absteigend',
            'text_en' => 'Sort, Numerical, Descending'
        ],
        '\f164' => [
            'class' => 'fa fa-thumbs-up',
            'classes' => ['fa-thumbs-up', 'icon-thumbs-up'],
            'content' => '\f164',
            'text_de' => 'Daumen hoch',
            'text_en' => 'Thumbs Up'
        ],
        '\f165' => [
            'class' => 'fa fa-thumbs-down',
            'classes' => ['fa-thumbs-down', 'icon-thumbs-down'],
            'content' => '\f165',
            'text_de' => 'Daumen runter',
            'text_en' => 'Thumbs Down'
        ],
        '\f167' => [
            'class' => 'fab fa-youtube',
            'classes' => ['fa-youtube'],
            'content' => '\f167',
            'context' => self::MEDIA,
            'text_de' => 'YouTube',
            'text_en' => 'YouTube'
        ],
        '\f168' => [
            'class' => 'fab fa-xing',
            'classes' => ['fa-xing'],
            'content' => '\f168',
            'text_de' => 'Logos: XING',
            'text_en' => 'Logos: XING'
        ],
        '\f169' => [
            'class' => 'fab fa-xing-square',
            'classes' => ['fa-xing-square'],
            'content' => '\f169',
            'text_de' => 'Logos: XING quadratisches Logo',
            'text_en' => 'Logos: XING Square Logo'
        ],
        '\f16b' => [
            'class' => 'fab fa-dropbox',
            'classes' => ['fa-dropbox'],
            'content' => '\f16b',
            'text_de' => 'Logos: Dropbox',
            'text_en' => 'Logos: Dropbox'
        ],
        '\f16c' => [
            'class' => 'fab fa-stack-overflow',
            'classes' => ['fa-stack-overflow'],
            'content' => '\f16c'
        ],
        '\f16d' => [
            'class' => 'fab fa-instagram',
            'classes' => ['fa-instagram'],
            'content' => '\f16d',
            'context' => self::COMMUNICATION,
            'text_de' => 'Instagram',
            'text_en' => 'Instagram'
        ],
        '\f16e' => [
            'class' => 'fab fa-flickr',
            'classes' => ['fa-flickr'],
            'content' => '\f16e',
            'text_de' => 'flickr',
            'text_en' => 'flickr'
        ],
        '\f170' => [
            'class' => 'fab fa-adn',
            'classes' => ['fa-adn'],
            'content' => '\f170'
        ],
        '\f171' => [
            'class' => 'fab fa-bitbucket',
            'classes' => ['fa-bitbucket'],
            'content' => '\f171',
            'text_de' => 'Bitbucket',
            'text_en' => 'Bitbucket'
        ],
        '\f173' => [
            'class' => 'fab fa-tumblr',
            'classes' => ['fa-tumblr'],
            'content' => '\f173',
            'text_de' => 'tumblr',
            'text_en' => 'tumblr'
        ],
        '\f174' => [
            'class' => 'fab fa-tumblr-square',
            'classes' => ['fa-tumblr-square'],
            'content' => '\f174',
            'text_de' => 'Logos: tumblr quadratisches Logo',
            'text_en' => 'Logos: tumblr Square Logo'
        ],
        '\f179' => [
            'class' => 'fab fa-apple',
            'classes' => ['fa-apple'],
            'content' => '\f179'
        ],
        '\f17a' => [
            'class' => 'fa fa-windows',
            'classes' => ['fa-windows'],
            'content' => '\f17a'
        ],
        '\f17b' => [
            'class' => 'fa fa-android',
            'classes' => ['fa-android'],
            'content' => '\f17b'
        ],
        '\f17c' => [
            'class' => 'fa fa-linux',
            'classes' => ['fa-linux'],
            'content' => '\f17c'
        ],
        '\f17d' => [
            'class' => 'fab fa-dribbble',
            'classes' => ['fa-dribbble'],
            'content' => '\f17d',
            'text_de' => 'Logos: dribbble',
            'text_en' => 'Logos: dribbble'
        ],
        '\f17e' => [
            'class' => 'fab fa-skype',
            'classes' => ['fa-skype'],
            'content' => '\f17e',
            'text_de' => 'Logos: Skype',
            'text_en' => 'Logos: Skype'
        ],
        '\f180' => [
            'class' => 'fab fa-foursquare',
            'classes' => ['fa-foursquare'],
            'content' => '\f180'
        ],
        '\f181' => [
            'class' => 'fab fa-trello',
            'classes' => ['fa-trello'],
            'content' => '\f181',
            'text_de' => 'Logos: trello',
            'text_en' => 'Logos: trello'
        ],
        '\f182' => [
            'class' => 'fa fa-female',
            'classes' => ['fa-female'],
            'content' => '\f182',
            'text_de' => 'Weiblich',
            'text_en' => 'Female'
        ],
        '\f183' => [
            'class' => 'fa fa-male',
            'classes' => ['fa-male'],
            'content' => '\f183',
            'text_de' => 'Männlich',
            'text_en' => 'Male'
        ],
        '\f184' => [
            'class' => 'fab fa-gratipay',
            'classes' => ['fa-gratipay'],
            'content' => '\f184'
        ],
        '\f185' => [
            'class' => 'fa fa-sun',
            'classes' => ['fa-sun'],
            'content' => '\f185',
            'text_de' => 'Sonne',
            'text_en' => 'Sun'
        ],
        '\f186' => [
            'class' => 'fa fa-moon',
            'classes' => ['fa-moon'],
            'content' => '\f186',
            'text_de' => 'Mond',
            'text_en' => 'Moon'
        ],
        '\f187' => [
            'class' => 'fa fa-archive',
            'classes' => ['fa-archive', 'icon-archive', 'icon-box-add', 'icon-box-remove'],
            'content' => '\f187',
            'text_de' => 'Archivieren (Dokumente)',
            'text_en' => 'Archive (Documents)'
        ],
        '\f188' => [
            'class' => 'fa fa-bug',
            'classes' => ['fa-bug'],
            'content' => '\f188',
            'text_de' => 'Insekt',
            'text_en' => 'Bug'
        ],
        '\f189' => [
            'class' => 'fab fa-vk',
            'classes' => ['fa-vk'],
            'content' => '\f189',
            'text_de' => 'Logos: VK',
            'text_en' => 'Logos: VK'
        ],
        '\f18a' => [
            'class' => 'fab fa-weibo',
            'classes' => ['fa-weibo'],
            'content' => '\f18a',
            'text_de' => 'Logos: weibo',
            'text_en' => 'Logos: weibo'
        ],
        '\f18b' => [
            'class' => 'fab fa-renren',
            'classes' => ['fa-renren'],
            'content' => '\f18b',
            'text_de' => 'Logos: Renren',
            'text_en' => 'Logos: Renren'
        ],
        '\f18c' => [
            'class' => 'fab fa-pagelines',
            'classes' => ['fa-pagelines'],
            'content' => '\f18c'
        ],
        '\f18d' => [
            'class' => 'fab fa-stack-exchange',
            'classes' => ['fa-stack-exchange'],
            'content' => '\f18d',
            'text_de' => 'Logos: Stack Exchange',
            'text_en' => 'Logos: Stack Exchange'
        ],
        '\f191' => [
            'class' => 'fa fa-caret-square-left',
            'classes' => ['fa-caret-square-left'],
            'content' => '\f191',
            'context' => self::POINTERS,
            'text_de' => 'Caret, nach links, im Quadrat',
            'text_en' => 'Caret, Left, on a Square'
        ],
        '\f192' => [
            'class' => 'fa fa-dot-circle',
            'classes' => ['fa-dot-circle', 'icon-generic'],
            'content' => '\f192',
            'text_de' => 'Kreis (dick, hohl)',
            'text_en' => 'Circle (Hollow, Thick)'
        ],
        '\f193' => [
            'class' => 'fa fa-wheelchair',
            'classes' => ['fa-wheelchair'],
            'content' => '\f193',
            'text_de' => 'Rollstuhl',
            'text_en' => 'Wheelchair'
        ],
        '\f194' => [
            'class' => 'fab fa-vimeo-square',
            'classes' => ['fa-vimeo-square'],
            'content' => '\f194',
            'text_de' => 'Logos: vimeo quadratisches Logo',
            'text_en' => 'Logos: vimeo Square Logo'
        ],
        '\f195' => [
            'class' => 'fa fa-lira-sign',
            'classes' => ['fa-lira-sign'],
            'content' => '\f195',
            'text_de' => 'Bezahlung: Lira',
            'text_en' => 'Payment: Lira'
        ],
        '\f197' => [
            'class' => 'fa fa-space-shuttle',
            'classes' => ['fa-space-shuttle'],
            'content' => '\f197',
            'text_de' => 'Space Shuttle',
            'text_en' => 'Space Shuttle'
        ],
        '\f198' => [
            //
            'class' => 'fab fa-slack',
            'classes' => ['fa-slack'],
            'content' => '\f198',
            'text_de' => 'Logos: slack',
            'text_en' => 'Logos: slack'
        ],
        '\f199' => [
            'class' => 'fa fa-envelope-square',
            'classes' => ['fa-envelope-square'],
            'content' => '\f199',
            'text_de' => 'Umschlag (Quadrat)',
            'text_en' => 'Envelope (Square)'
        ],
        '\f19a' => [
            'class' => 'fa fa-wordpress',
            'classes' => ['fa-wordpress'],
            'content' => '\f19a'
        ],
        '\f19b' => [
            'class' => 'fab fa-openid',
            'classes' => ['fa-openid'],
            'content' => '\f19b'
        ],
        '\f19c' => [
            'class' => 'fa fa-university',
            'classes' => ['fa-university'],
            'content' => '\f19c',
            'text_de' => 'Universität',
            'text_en' => 'University'
        ],
        '\f19d' => [
            'class' => 'fa fa-graduation-cap',
            'classes' => ['fa-graduation-cap'],
            'content' => '\f19d',
            'text_de' => 'Graduiertenmütze',
            'text_en' => 'Graduation Cap'
        ],
        '\f19e' => [
            'class' => 'fab fa-yahoo',
            'classes' => ['fa-yahoo'],
            'content' => '\f19e',
            'text_de' => 'Logos: yahoo',
            'text_en' => 'Logos: yahoo'
        ],
        '\f1a0' => [
            'class' => 'fa fa-google',
            'classes' => ['fa-google'],
            'content' => '\f1a0'
        ],
        '\f1a1' => [
            'class' => 'fab fa-reddit',
            'classes' => ['fa-reddit'],
            'content' => '\f1a1',
            'text_de' => 'Logos: reddit',
            'text_en' => 'Logos: reddit'
        ],
        '\f1a2' => [
            'class' => 'fab fa-reddit-square',
            'classes' => ['fa-reddit-square'],
            'content' => '\f1a2',
            'text_de' => 'Logos: reddit quadratisches Logo',
            'text_en' => 'Logos: reddit Square Logo'
        ],
        '\f1a3' => [
            'class' => 'fab fa-stumbleupon-circle',
            'classes' => ['fa-stumbleupon-circle'],
            'content' => '\f1a3'
        ],
        '\f1a4' => [
            'class' => 'fab fa-stumbleupon',
            'classes' => ['fa-stumbleupon'],
            'content' => '\f1a4'
        ],
        '\f1a5' => [
            'class' => 'fab fa-delicious',
            'classes' => ['fa-delicious'],
            'content' => '\f1a5'
        ],
        '\f1a6' => [
            'class' => 'fab fa-digg',
            'classes' => ['fa-digg'],
            'content' => '\f1a6'
        ],
        '\f1a7' => [
            'class' => 'fab fa-pied-piper-pp',
            'classes' => ['fa-pied-piper-pp'],
            'content' => '\f1a7',
            // piedpipergroup.com: consulting => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f1a8' => [
            'class' => 'fab fa-pied-piper-alt',
            'classes' => ['fa-pied-piper-alt'],
            'content' => '\f1a8',
            // piedpipergroup.com: consulting => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f1a9' => [
            'class' => 'fab fa-drupal',
            'classes' => ['fa-drupal'],
            'content' => '\f1a9'
        ],
        '\f1aa' => [
            'class' => 'fab fa-joomla',
            'classes' => ['fa-joomla', 'icon-joomla'],
            'content' => '\f1aa'
        ],
        '\f1ab' => [
            'class' => 'fa fa-language',
            'classes' => ['fa-language', 'icon-language'],
            'content' => '\f1ab',
            'text_de' => 'Sprache',
            'text_en' => 'Language'
        ],
        '\f1ac' => [
            'class' => 'fa fa-fax',
            'classes' => ['fa-fax', 'icon-fax'],
            'content' => '\f1ac',
            'context' => self::DEVICES,
            'text_de' => 'Fax',
            'text_en' => 'Fax'
        ],
        '\f1ad' => [
            'class' => 'fa fa-building',
            'classes' => ['fa-building'],
            'content' => '\f1ad',
            'text_de' => 'Gebäude',
            'text_en' => 'Building'
        ],
        '\f1ae' => [
            'class' => 'fa fa-child',
            'classes' => ['fa-child'],
            'content' => '\f1ae',
            'text_de' => 'Kind',
            'text_en' => 'Child'
        ],
        '\f1b0' => [
            'class' => 'fa fa-paw',
            'classes' => ['fa-paw'],
            'content' => '\f1b0',
            'text_de' => 'Pfote',
            'text_en' => 'Paw'
        ],
        '\f1b2' => [
            'class' => 'fa fa-cube',
            'classes' => ['fa-cube', 'icon-cube'],
            'content' => '\f1b2',
            'text_de' => 'Würfel (3D, 2-Töne)',
            'text_en' => 'Cube / Dice (3D, 2-Tone)'
        ],
        '\f1b3' => [
            'class' => 'fa fa-cubes',
            'classes' => ['fa-cubes', 'icon-cubes'],
            'content' => '\f1b3',
            'text_de' => 'Würfel (mehrzahl)',
            'text_en' => 'Cubes / Dice (multiple)'
        ],
        '\f1b4' => [
            'class' => 'fab fa-behance',
            'classes' => ['fa-behance'],
            'content' => '\f1b4',
            'text_de' => 'Logos: Bēhance',
            'text_en' => 'Logos: Bēhance'
        ],
        '\f1b5' => [
            'class' => 'fab fa-behance-square',
            'classes' => ['fa-behance-square'],
            'content' => '\f1b5',
            'text_de' => 'Logos: Bēhance quadratisches Logo',
            'text_en' => 'Logos: Bēhance Square Logo'
        ],
        '\f1b6' => [
            'class' => 'fab fa-steam',
            'classes' => ['fa-steam'],
            'content' => '\f1b6'
        ],
        '\f1b7' => [
            'class' => 'fab fa-steam-square',
            'classes' => ['fa-steam-square'],
            'content' => '\f1b7'
        ],
        '\f1b8' => [
            'class' => 'fa fa-recycle',
            'classes' => ['fa-recycle'],
            'content' => '\f1b8',
            'text_de' => 'Recyceln',
            'text_en' => 'Recycle / Recycling'
        ],
        '\f1b9' => [
            'class' => 'fa fa-car',
            'classes' => ['fa-car'],
            'content' => '\f1b9',
            'text_de' => 'Auto (front)',
            'text_en' => 'Car (Front-View)'
        ],
        '\f1ba' => [
            'class' => 'fa fa-taxi',
            'classes' => ['fa-taxi'],
            'content' => '\f1ba',
            'text_de' => 'Taxi',
            'text_en' => 'Taxi'
        ],
        '\f1bb' => [
            'class' => 'fa fa-tree',
            'classes' => ['fa-tree'],
            'content' => '\f1bb',
            'text_de' => 'Natur: Baum',
            'text_en' => 'Nautre: Tree'
        ],
        '\f1bc' => [
            'class' => 'fab fa-spotify',
            'classes' => ['fa-spotify'],
            'content' => '\f1bc'
        ],
        '\f1bd' => [
            'class' => 'fab fa-deviantart',
            'classes' => ['fa-deviantart'],
            'content' => '\f1bd',
            'text_de' => 'Logos: DeviantArt',
            'text_en' => 'Logos: DeviantArt'
        ],
        '\f1be' => [
            'class' => 'fab fa-soundcloud',
            'classes' => ['fa-soundcloud'],
            'content' => '\f1be',
            'text_de' => 'Logos: SoundCloud',
            'text_en' => 'Logos: SoundCloud'
        ],
        '\f1c0' => [
            'class' => 'fa fa-database',
            'classes' => ['fa-database', 'icon-database'],
            'content' => '\f1c0',
            'text_de' => 'Datenbank',
            'text_en' => 'Database'
        ],
        '\f1c1' => [
            'class' => 'fa fa-file-pdf',
            'classes' => ['fa-file-pdf'],
            'content' => '\f1c1',
            'text_de' => 'Datei (PDF)',
            'text_en' => 'File (PDF)'
        ],
        '\f1c2' => [
            'class' => 'fa fa-file-word',
            'classes' => ['fa-file-word'],
            'content' => '\f1c2',
            'text_de' => 'Datei (Word)',
            'text_en' => 'File (Word)'
        ],
        '\f1c3' => [
            'class' => 'fa fa-file-excel',
            'classes' => ['fa-file-excel'],
            'content' => '\f1c3',
            'text_de' => 'Datei (Excel)',
            'text_en' => 'File (Excel)'
        ],
        '\f1c4' => [
            'class' => 'fa fa-file-powerpoint',
            'classes' => ['fa-file-powerpoint'],
            'content' => '\f1c4',
            'text_de' => 'Datei (Power Point)',
            'text_en' => 'File (Power Point)'
        ],
        '\f1c5' => [
            'class' => 'fa fa-file-image',
            'classes' => ['fa-file-image'],
            'content' => '\f1c5',
            'text_de' => 'Datei (Bild)',
            'text_en' => 'File (Image)'
        ],
        '\f1c6' => [
            'class' => 'fa fa-file-archive',
            'classes' => ['fa-file-archive'],
            'content' => '\f1c6',
            'text_de' => 'Archivieren (Datei)',
            'text_en' => 'Archive (File)'
        ],
        '\f1c7' => [
            'class' => 'fa fa-file-audio',
            'classes' => ['fa-file-audio'],
            'content' => '\f1c7',
            'text_de' => 'Datei (Ton)',
            'text_en' => 'File (Audio)'
        ],
        '\f1c8' => [
            'class' => 'fa fa-file-video',
            'classes' => ['fa-file-video'],
            'content' => '\f1c8',
            'text_de' => 'Datei (Video)',
            'text_en' => 'File (Video)'
        ],
        '\f1c9' => [
            'class' => 'fa fa-file-code',
            'classes' => ['fa-file-code'],
            'content' => '\f1c9',
            'text_de' => 'Datei (Quellcode)',
            'text_en' => 'File (Code)'
        ],
        '\f1ca' => [
            'class' => 'fab fa-vine',
            'classes' => ['fa-vine'],
            'content' => '\f1ca'
        ],
        '\f1cb' => [
            'class' => 'fab fa-codepen',
            'classes' => ['fa-codepen'],
            'content' => '\f1cb',
            'text_de' => 'Logos: CodePen',
            'text_en' => 'Logos: CodePen'
        ],
        '\f1cc' => [
            'class' => 'fab fa-jsfiddle',
            'classes' => ['fa-jsfiddle'],
            'content' => '\f1cc'
        ],
        '\f1cd' => [
            'class' => 'fa fa-life-ring',
            'classes' => ['fa-life-ring', 'icon-support'],
            'content' => '\f1cd',
            'text_de' => 'Rettungsring',
            'text_en' => 'Life Ring'
        ],
        '\f1ce' => [
            'class' => 'fa fa-circle-notch',
            'classes' => ['fa-circle-notch'],
            'content' => '\f1ce',
            'text_de' => 'Kreis (eingekerbt)',
            'text_en' => 'Circle (Notched)'
        ],
        '\f1d0' => [
            'class' => 'fab fa-rebel',
            'classes' => ['fa-rebel'],
            'content' => '\f1d0'
        ],
        '\f1d1' => [
            'class' => 'fab fa-empire',
            'classes' => ['fa-empire'],
            'content' => '\f1d1'
        ],
        '\f1d2' => [
            'class' => 'fab fa-git-square',
            'classes' => ['fa-git-square'],
            'content' => '\f1d2',
            // git-scm.com scm platform => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f1d3' => [
            'class' => 'fab fa-git',
            'classes' => ['fa-git'],
            'content' => '\f1d3',
            'text_de' => 'Logos: git',
            'text_en' => 'Logos: git'
        ],
        '\f1d4' => [
            'class' => 'fab fa-hacker-news',
            'classes' => ['fa-hacker-news'],
            'content' => '\f1d4',
        ],
        '\f1d5' => [
            'class' => 'fab fa-tencent-weibo',
            'classes' => ['fa-tencent-weibo'],
            'content' => '\f1d5'
        ],
        '\f1d6' => [
            'class' => 'fab fa-qq',
            'classes' => ['fa-qq'],
            'content' => '\f1d6',
            'text_de' => 'Logos: QQ',
            'text_en' => 'Logos: QQ'
        ],
        '\f1d7' => [
            'class' => 'fab fa-weixin',
            'classes' => ['fa-weixin'],
            'content' => '\f1d7',
            'text_de' => 'Logos: Weixin',
            'text_en' => 'Logos: Weixin'
        ],
        '\f1d8' => [
            'class' => 'fa fa-paper-plane',
            'classes' => ['fa-paper-plane'],
            'content' => '\f1d8',
            'text_de' => 'Papierflieger',
            'text_en' => 'Paper Plane'
        ],
        '\f1da' => [
            'class' => 'fa fa-history',
            'classes' => ['fa-history'],
            'content' => '\f1da',
            'text_de' => 'Historie',
            'text_en' => 'History'
        ],
        '\f1dc' => [
            'class' => 'fa fa-heading',
            'classes' => ['fa-heading'],
            'content' => '\f1dc',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Überschrift',
            'text_en' => 'Heading'
        ],
        '\f1dd' => [
            'class' => 'fa fa-paragraph',
            'classes' => ['fa-paragraph'],
            'content' => '\f1dd',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Absatz',
            'text_en' => 'Paragraph'
        ],
        '\f1de' => [
            'class' => 'fa fa-sliders-h',
            'classes' => ['fa-sliders-h', 'icon-equalizer', 'icon-sliders-h'],
            'content' => '\f1de',
            'text_de' => 'Ausgleicher / Schieberegler',
            'text_en' => 'Equalizer / Sliders'
        ],
        '\f1e0' => [
            'class' => 'fa fa-share-alt',
            'classes' => ['fa-share-alt'],
            'content' => '\f1e0',
            'text_de' => 'Teilen',
            'text_en' => 'Share'
        ],
        '\f1e1' => [
            'class' => 'fa fa-share-alt-square',
            'classes' => ['fa-share-alt-square'],
            'content' => '\f1e1',
            'text_de' => 'Teilen (Quadrat)',
            'text_en' => 'Share (Square)'
        ],
        '\f1e2' => [
            'class' => 'fa fa-bomb',
            'classes' => ['fa-bomb'],
            'content' => '\f1e2',
            'text_de' => 'Bombe',
            'text_en' => 'Bomb'
        ],
        '\f1e3' => [
            'class' => 'fa fa-futbol',
            'classes' => ['fa-futbol'],
            'content' => '\f1e3',
            'context' => self::ACTIVITIES,
            'text_de' => 'Fußball',
            'text_en' => 'Football'
        ],
        '\f1e4' => [
            'class' => 'fa fa-tty',
            'classes' => ['fa-tty'],
            'content' => '\f1e4',
            'text_de' => 'Fernschreiber',
            'text_en' => 'Teletypewriter'
        ],
        '\f1e5' => [
            'class' => 'fa fa-binoculars',
            'classes' => ['fa-binoculars'],
            'content' => '\f1e5',
            'text_de' => 'Ferngläser',
            'text_en' => 'Binoculars'
        ],
        '\f1e6' => [
            'class' => 'fa fa-plug',
            'classes' => ['fa-plug', 'icon-plug', 'icon-power-cord'],
            'content' => '\f1e6',
            'text_de' => 'Netzkabel / Stecker',
            'text_en' => 'Plug / Power Cord'
        ],
        '\f1e7' => [
            'class' => 'fab fa-slideshare',
            'classes' => ['fa-slideshare'],
            'content' => '\f1e7',
            'text_de' => 'Logos: slideshare',
            'text_en' => 'Logos: slideshare'
        ],
        '\f1e8' => [
            'class' => 'fab fa-twitch',
            'classes' => ['fa-twitch'],
            'content' => '\f1e8',
            'text_de' => 'Logos: twitch',
            'text_en' => 'Logos: twitch'
        ],
        '\f1e9' => [
            'class' => 'fab fa-yelp',
            'classes' => ['fa-yelp'],
            'content' => '\f1e9'
        ],
        '\f1ea' => [
            'class' => 'fa fa-newspaper',
            'classes' => ['fa-newspaper'],
            'content' => '\f1ea',
            'text_de' => 'Zeitung',
            'text_en' => 'Newspaper'
        ],
        '\f1eb' => [
            'class' => 'fa fa-wifi',
            'classes' => ['fa-wifi', 'icon-broadcast', 'icon-connection', 'icon-wifi'],
            'content' => '\f1eb',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Verbindung',
            'text_en' => 'Connection'
        ],
        '\f1ec' => [
            'class' => 'fa fa-calculator',
            'classes' => ['fa-calculator'],
            'content' => '\f1ec',
            'context' => self::DEVICES,
            'text_de' => 'Taschenrechner',
            'text_en' => 'Calculator'
        ],
        '\f1ed' => [
            'class' => 'fab fa-paypal',
            'classes' => ['fa-paypal'],
            'content' => '\f1ed',
            'context' => self::FINANCE,
            'text_de' => 'PayPal',
            'text_en' => 'PayPal'
        ],
        '\f1ee' => [
            'class' => 'fab fa-google-wallet',
            'classes' => ['fa-google-wallet'],
            'content' => '\f1ee',
            'context' => self::FINANCE,
            'text_de' => 'Google Wallet',
            'text_en' => 'Google Wallet'
        ],
        '\f1f0' => [
            'class' => 'fab fa-cc-visa',
            'classes' => ['fa-cc-visa'],
            'content' => '\f1f0',
            'context' => self::FINANCE,
            'text_de' => 'Visa',
            'text_en' => 'Visa'
        ],
        '\f1f1' => [
            'class' => 'fab fa-cc-mastercard',
            'classes' => ['fa-cc-mastercard'],
            'content' => '\f1f1',
            'context' => self::FINANCE,
            'text_de' => 'Mastercard',
            'text_en' => 'Mastercard'
        ],
        '\f1f2' => [
            'class' => 'fab fa-cc-discover',
            'classes' => ['fa-cc-discover'],
            'content' => '\f1f2',
            'context' => self::FINANCE,
            'text_de' => 'Discover',
            'text_en' => 'Discover'
        ],
        '\f1f3' => [
            'class' => 'fab fa-cc-amex',
            'classes' => ['fa-cc-amex'],
            'content' => '\f1f3',
            'context' => self::FINANCE,
            'text_de' => 'American Express',
            'text_en' => 'American Express'
        ],
        '\f1f4' => [
            'class' => 'fab fa-cc-paypal',
            'classes' => ['fa-cc-paypal'],
            'content' => '\f1f4',
            'context' => self::FINANCE,
            'text_de' => 'PayPal, Kreditkarte',
            'text_en' => 'PayPal, Credit Card'
        ],
        '\f1f5' => [
            'class' => 'fab fa-cc-stripe',
            'classes' => ['fa-cc-stripe'],
            'content' => '\f1f5',
            'context' => self::FINANCE,
            'text_de' => 'Stripe, Kreditkarte',
            'text_en' => 'Stripe, Credit Card'
        ],
        '\f1f6' => [
            'class' => 'fa fa-bell-slash',
            'classes' => ['fa-bell-slash'],
            'content' => '\f1f6',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Benachrichtigungen mit Schrägstrich',
            'text_en' => 'Notifications with Slash'
        ],
        '\f1f8' => [
            'class' => 'fa fa-trash',
            'classes' => ['fa-trash', 'icon-purge', 'icon-trash'],
            'content' => '\f1f8',
            'text_de' => 'Mülleimer',
            'text_en' => 'Trashcan'
        ],
        '\f1f9' => [
            'class' => 'fa fa-copyright',
            'classes' => ['fa-copyright'],
            'content' => '\f1f9',
            'text_de' => 'Urheberrecht',
            'text_en' => 'Copyright'
        ],
        '\f1fa' => [
            // deprecated?
            'class' => 'fa fa-at',
            'classes' => ['fa-at'],
            // unicode 40
            'content' => '\f1fa',
            'text_de' => 'Symbole: At',
            'text_en' => 'Symbols: At'
        ],
        '\f1fb' => [
            'class' => 'fa fa-eye-dropper',
            'classes' => ['fa-eye-dropper'],
            'content' => '\f1fb',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Pipette',
            'text_en' => 'Eye Dropper'
        ],
        '\f1fc' => [
            'class' => 'fa fa-paint-brush',
            'classes' => ['fa-paint-brush', 'icon-brush', 'icon-color-palette', 'icon-paint-brush', 'icon-palette'],
            'content' => '\f1fc',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Pinsel',
            'text_en' => 'Paint Brush'
        ],
        '\f1fd' => [
            'class' => 'fa fa-birthday-cake',
            'classes' => ['fa-birthday-cake'],
            'content' => '\f1fd',
            'text_de' => 'Geburtstagskuchen',
            'text_en' => 'Birthday Cake'
        ],
        '\f1fe' => [
            'class' => 'fa fa-chart-area',
            'classes' => ['fa-chart-area', 'icon-chart'],
            'content' => '\f1fe',
            'text_de' => 'Diagramm (Flächen-)',
            'text_en' => 'Chart (Area)'
        ],
        '\f200' => [
            'class' => 'fa fa-chart-pie',
            'classes' => ['fa-chart-pie', 'icon-pie'],
            'content' => '\f200',
            'text_de' => 'Diagramm (Torten-)',
            'text_en' => 'Chart (Pie)'
        ],
        '\f201' => [
            'class' => 'fa fa-chart-line',
            'classes' => ['fa-chart-line'],
            'content' => '\f201',
            'text_de' => 'Diagramm (Linien-)',
            'text_en' => 'Chart (Line)'
        ],
        '\f202' => [
            'class' => 'fab fa-lastfm',
            'classes' => ['fa-lastfm'],
            'content' => '\f202'
        ],
        '\f203' => [
            'class' => 'fab fa-lastfm-square',
            'classes' => ['fa-lastfm-square'],
            'content' => '\f203'
        ],
        '\f204' => [
            'class' => 'fa fa-toggle-off',
            'classes' => ['fa-toggle-off', 'icon-toggle-off'],
            'content' => '\f204',
            'text_de' => 'Umschalter (Aus)',
            'text_en' => 'Toggle (Off)'
        ],
        '\f205' => [
            'class' => 'fa fa-toggle-on',
            'classes' => ['fa-toggle-on', 'icon-toggle-on'],
            'content' => '\f205',
            'text_de' => 'Umschalter (An)',
            'text_en' => 'Toggle (On)'
        ],
        '\f206' => [
            'class' => 'fa fa-bicycle',
            'classes' => ['fa-bicycle'],
            'content' => '\f206',
            'context' => self::VEHICLES,
            'text_de' => 'Fahrrad',
            'text_en' => 'Bicycle'
        ],
        '\f207' => [
            'class' => 'fa fa-bus',
            'classes' => ['fa-bus'],
            'content' => '\f207',
            'context' => self::VEHICLES,
            'text_de' => 'Bus (altmodisch)',
            'text_en' => 'Bus (Classic)'
        ],
        '\f208' => [
            'class' => 'fab fa-ioxhost',
            'classes' => ['fa-ioxhost'],
            'content' => '\f208'
        ],
        '\f209' => [
            'class' => 'fab fa-angellist',
            'classes' => ['fa-angellist'],
            'content' => '\f209'
        ],
        '\f20a' => [
            'class' => 'fa fa-closed-captioning',
            'classes' => ['fa-closed-captioning'],
            'content' => '\f20a',
            'text_de' => 'Untertitel',
            'text_en' => 'Closed Captioning'
        ],
        '\f20b' => [
            'class' => 'fa fa-shekel-sign',
            'classes' => ['fa-shekel-sign'],
            'content' => '\f20b',
            'context' => self::FINANCE,
            'text_de' => 'Schekel',
            'text_en' => 'Shekel'
        ],
        '\f20d' => [
            'class' => 'fab fa-buysellads',
            'classes' => ['fa-buysellads'],
            'content' => '\f20d'
        ],
        '\f20e' => [
            'class' => 'fab fa-connectdevelop',
            'classes' => ['fa-connectdevelop'],
            'content' => '\f20e'
        ],
        '\f210' => [
            'class' => 'fab fa-dashcube',
            'classes' => ['fa-dashcube'],
            'content' => '\f210'
        ],
        '\f211' => [
            'class' => 'fab fa-forumbee',
            'classes' => ['fa-forumbee'],
            'content' => '\f211'
        ],
        '\f212' => [
            'class' => 'fab fa-leanpub',
            'classes' => ['fa-leanpub'],
            'content' => '\f212',
            'text_de' => 'Logos: Leanpub',
            'text_en' => 'Logos: Leanpub'
        ],
        '\f213' => [
            'class' => 'fab fa-sellsy',
            'classes' => ['fa-sellsy'],
            'content' => '\f213'
        ],
        '\f214' => [
            'class' => 'fab fa-shirtsinbulk',
            'classes' => ['fa-shirtsinbulk'],
            'content' => '\f214'
        ],
        '\f215' => [
            'class' => 'fab fa-simplybuilt',
            'classes' => ['fa-simplybuilt'],
            'content' => '\f215'
        ],
        '\f216' => [
            'class' => 'fab fa-skyatlas',
            'classes' => ['fa-skyatlas'],
            'content' => '\f216'
        ],
        '\f217' => [
            'class' => 'fa fa-cart-plus',
            'classes' => ['fa-cart-plus'],
            'content' => '\f217',
            'text_de' => 'Wagen (Plus)',
            'text_en' => 'Cart (Plus)'
        ],
        '\f218' => [
            'class' => 'fa fa-cart-arrow-down',
            'classes' => ['fa-cart-arrow-down'],
            'content' => '\f218',
            'text_de' => 'Wagen (Hinzufügen)',
            'text_en' => 'Cart (Add)'
        ],
        '\f21a' => [
            'class' => 'fa fa-ship',
            'classes' => ['fa-ship'],
            'content' => '\f21a',
            'context' => self::VEHICLES,
            'text_de' => 'Schiff',
            'text_en' => 'Ship'
        ],
        '\f21b' => [
            'class' => 'fa fa-user-secret',
            'classes' => ['fa-user-secret'],
            'content' => '\f21b',
            'text_de' => 'Personen: Spion:in',
            'text_en' => 'People: Spy'
        ],
        '\f21c' => [
            'class' => 'fa fa-motorcycle',
            'classes' => ['fa-motorcycle'],
            'content' => '\f21c',
            'context' => self::VEHICLES,
            'text_de' => 'Motorrad',
            'text_en' => 'Motorcycle'
        ],
        '\f21d' => [
            'class' => 'fa fa-street-view',
            'classes' => ['fa-street-view'],
            'content' => '\f21d',
            'text_de' => 'Straßenansicht',
            'text_en' => 'Street View'
        ],
        '\f21e' => [
            'class' => 'fa fa-heartbeat',
            'classes' => ['fa-heartbeat'],
            'content' => '\f21e',
            'text_de' => 'Herzschlag',
            'text_en' => 'Heartbeat'
        ],
        '\f221' => [
            'class' => 'fa fa-venus',
            'classes' => ['fa-venus'],
            'content' => '\f221',
            'text_de' => 'Venus',
            'text_en' => 'Venus'
        ],
        '\f222' => [
            'class' => 'fa fa-mars',
            'classes' => ['fa-mars'],
            'content' => '\f222',
            'text_de' => 'Mars',
            'text_en' => 'Mars'
        ],
        '\f223' => [
            'class' => 'fa fa-mercury',
            'classes' => ['fa-mercury'],
            'content' => '\f223',
            'text_de' => 'Merkur',
            'text_en' => 'Mercury'
        ],
        '\f224' => [
            'class' => 'fa fa-transgender',
            'classes' => ['fa-transgender'],
            'content' => '\f224'
        ],
        '\f225' => [
            'class' => 'fa fa-transgender-alt',
            'classes' => ['fa-transgender-alt'],
            'content' => '\f225',
            'text_de' => 'Transgender',
            'text_en' => 'Transgender'
        ],
        '\f226' => [
            'class' => 'fa fa-venus-double',
            'classes' => ['fa-venus-double'],
            'content' => '\f226',
            'text_de' => 'Lesbisch',
            'text_en' => 'Lesbian'
        ],
        '\f227' => [
            'class' => 'fa fa-mars-double',
            'classes' => ['fa-mars-double'],
            'content' => '\f227',
            'text_de' => 'Schwul',
            'text_en' => 'Gay'
        ],
        '\f228' => [
            'class' => 'fa fa-venus-mars',
            'classes' => ['fa-venus-mars'],
            'content' => '\f228',
            'text_de' => 'Hetero',
            'text_en' => 'Hetero'
        ],
        '\f229' => [
            'class' => 'fa fa-mars-stroke',
            'classes' => ['fa-mars-stroke'],
            'content' => '\f229'
        ],
        '\f22a' => [
            'class' => 'fa fa-mars-stroke-v',
            'classes' => ['fa-mars-stroke-v'],
            'content' => '\f22a'
        ],
        '\f22b' => [
            'class' => 'fa fa-mars-stroke-h',
            'classes' => ['fa-mars-stroke-h'],
            'content' => '\f22b'
        ],
        '\f22c' => [
            'class' => 'fa fa-neuter',
            'classes' => ['fa-neuter'],
            'content' => '\f22c'
        ],
        '\f22d' => [
            'class' => 'fa fa-genderless',
            'classes' => ['fa-genderless'],
            'content' => '\f22d'
        ],
        '\f231' => [
            'class' => 'fab fa-pinterest-p',
            'classes' => ['fa-pinterest-p'],
            'content' => '\f231',
            'text_de' => 'Logos: Pinterest (P)',
            'text_en' => 'Logos: Pinterest (P)'
        ],
        '\f232' => [
            'class' => 'fab fa-whatsapp',
            'classes' => ['fa-whatsapp'],
            'content' => '\f232',
            'text_de' => 'Logos: WhatsApp',
            'text_en' => 'Logos: WhatsApp'
        ],
        '\f233' => [
            'class' => 'fa fa-server',
            'classes' => ['fa-server'],
            'content' => '\f233',
            'text_de' => 'Server',
            'text_en' => 'Server'
        ],
        '\f234' => [
            'class' => 'fa fa-user-plus',
            'classes' => ['fa-user-plus'],
            'content' => '\f234',
            'text_de' => 'Personen: Person mit Plus',
            'text_en' => 'People: Person with Plus'
        ],
        '\f235' => [
            'class' => 'fa fa-user-times',
            'classes' => ['fa-user-times'],
            'content' => '\f235',
            'text_de' => 'Personen: Person mit X',
            'text_en' => 'People: Person with X'
        ],
        '\f236' => [
            'class' => 'fa fa-bed',
            'classes' => ['fa-bed'],
            'content' => '\f236',
            'text_de' => 'Bett',
            'text_en' => 'Bed'
        ],
        '\f237' => [
            'class' => 'fab fa-viacoin',
            'classes' => ['fa-viacoin'],
            'content' => '\f237'
        ],
        '\f238' => [
            'class' => 'fa fa-train',
            'classes' => ['fa-train'],
            'content' => '\f238',
            'context' => self::VEHICLES,
            'text_de' => 'Zug',
            'text_en' => 'Train'
        ],
        '\f239' => [
            'class' => 'fa fa-subway',
            'classes' => ['fa-subway'],
            'content' => '\f239',
            'context' => self::VEHICLES,
            'text_de' => 'U-Bahn',
            'text_en' => 'Subway'
        ],
        '\f23a' => [
            'class' => 'fab fa-medium',
            'classes' => ['fa-medium'],
            'content' => '\f23a'
        ],
        '\f23b' => [
            'class' => 'fab fa-y-combinator',
            'classes' => ['fa-y-combinator'],
            'content' => '\f23b'
        ],
        '\f23c' => [
            'class' => 'fab fa-optin-monster',
            'classes' => ['fa-optin-monster'],
            'content' => '\f23c'
        ],
        '\f23d' => [
            'class' => 'fab fa-opencart',
            'classes' => ['fa-opencart'],
            'content' => '\f23d'
        ],
        '\f23e' => [
            'class' => 'fab fa-expeditedssl',
            'classes' => ['fa-expeditedssl'],
            'content' => '\f23e'
        ],
        '\f240' => [
            'class' => 'fa fa-battery-full',
            'classes' => ['fa-battery-full'],
            'content' => '\f240',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Akku, voll',
            'text_en' => 'Battery, Full'
        ],
        '\f241' => [
            'class' => 'fa fa-battery-three-quarters',
            'classes' => ['fa-battery-three-quarters'],
            'content' => '\f241',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Akku, 3/4-voll',
            'text_en' => 'Battery, 3/4-Full'
        ],
        '\f242' => [
            'class' => 'fa fa-battery-half',
            'classes' => ['fa-battery-half'],
            'content' => '\f242',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Akku, 1/2-voll',
            'text_en' => 'Battery, 1/2'
        ],
        '\f243' => [
            'class' => 'fa fa-battery-quarter',
            'classes' => ['fa-battery-quarter'],
            'content' => '\f243',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Akku, 1/4-voll',
            'text_en' => 'Battery, 1/4-Full'
        ],
        '\f244' => [
            'class' => 'fa fa-battery-empty',
            'classes' => ['fa-battery-empty'],
            'content' => '\f244',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Akku, leer',
            'text_en' => 'Battery, Empty'
        ],
        '\f245' => [
            'class' => 'fa fa-mouse-pointer',
            'classes' => ['fa-mouse-pointer'],
            'content' => '\f245',
            'text_de' => 'Zeiger (Pfeil)',
            'text_en' => 'Pointer (Arrow)'
        ],
        '\f246' => [
            'class' => 'fa fa-i-cursor',
            'classes' => ['fa-i-cursor'],
            'content' => '\f246',
            'text_de' => 'Cursor',
            'text_en' => 'Cursor'
        ],
        '\f247' => [
            'class' => 'fa fa-object-group',
            'classes' => ['fa-object-group'],
            'content' => '\f247',
            'text_de' => 'Ausgewählte Objekte',
            'text_en' => 'Selected Objects'
        ],
        '\f248' => [
            'class' => 'fa fa-object-ungroup',
            'classes' => ['fa-object-ungroup'],
            'content' => '\f248',
            'text_de' => 'Auswahlboxen',
            'text_en' => 'Selection Boxes'
        ],
        '\f249' => [
            'class' => 'fa fa-sticky-note',
            'classes' => ['fa-sticky-note'],
            'content' => '\f249',
            'text_de' => 'Haftnotiz',
            'text_en' => 'Sticky Note'
        ],
        '\f24b' => [
            'class' => 'fab fa-cc-jcb',
            'classes' => ['fa-cc-jcb'],
            'content' => '\f24b',
            'context' => self::FINANCE,
            'text_de' => 'JCB',
            'text_en' => 'JCB'
        ],
        '\f24c' => [
            'class' => 'fab fa-cc-diners-club',
            'classes' => ['fa-cc-diners-club'],
            'content' => '\f24c',
            'context' => self::FINANCE,
            'text_de' => 'Diner\'s Club',
            'text_en' => 'Diner\'s Club'
        ],
        '\f24d' => [
            'class' => 'fa fa-clone',
            'classes' => ['fa-clone'],
            'content' => '\f24d',
            'text_de' => 'Klon',
            'text_en' => 'Clone'
        ],
        '\f24e' => [
            'class' => 'fa fa-balance-scale',
            'classes' => ['fa-balance-scale'],
            'content' => '\f24e',
            'text_de' => 'Waage (Ausgeglichen)',
            'text_en' => 'Scales (Balanced)'
        ],
        '\f251' => [
            'class' => 'fa fa-hourglass-start',
            'classes' => ['fa-hourglass-start'],
            'content' => '\f251',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Sanduhr, voll',
            'text_en' => 'Hourglass, Full'
        ],
        '\f252' => [
            'class' => 'fa fa-hourglass-half',
            'classes' => ['fa-hourglass-half'],
            'content' => '\f252',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Sanduhr, halb',
            'text_en' => 'Hourglass, Half'
        ],
        '\f253' => [
            'class' => 'fa fa-hourglass-end',
            'classes' => ['fa-hourglass-end'],
            'content' => '\f253',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Sanduhr, leer',
            'text_en' => 'Hourglass, Empty'
        ],
        '\f254' => [
            'class' => 'fa fa-hourglass',
            'classes' => ['fa-hourglass'],
            'content' => '\f254',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Sanduhr',
            'text_en' => 'Hourglass'
        ],
        '\f255' => [
            'class' => 'fa fa-hand-rock',
            'classes' => ['fa-hand-rock'],
            'content' => '\f255',
            'text_de' => 'Hand (Stein)',
            'text_en' => 'Hand (Rock)'
        ],
        '\f256' => [
            'class' => 'fa fa-hand-paper',
            'classes' => ['fa-hand-paper'],
            'content' => '\f256',
            'text_de' => 'Hand (Papier)',
            'text_en' => 'Hand (Paper)'
        ],
        '\f257' => [
            'class' => 'fa fa-hand-scissors',
            'classes' => ['fa-hand-scissors'],
            'content' => '\f257',
            'text_de' => 'Hand (Schere)',
            'text_en' => 'Hand (Scissors)'
        ],
        '\f258' => [
            'class' => 'fa fa-hand-lizard',
            'classes' => ['fa-hand-lizard'],
            'content' => '\f258',
            'text_de' => 'Hand (Echse)',
            'text_en' => 'Hand (Lizard)'
        ],
        '\f259' => [
            'class' => 'fa fa-hand-spock',
            'classes' => ['fa-hand-spock'],
            'content' => '\f259',
            'text_de' => 'Hand (Spock)',
            'text_en' => 'Hand (Spock)'
        ],
        '\f25a' => [
            'class' => 'fa fa-hand-pointer',
            'classes' => ['fa-hand-pointer'],
            'content' => '\f25a',
            'text_de' => 'Hand (Pfeil)',
            'text_en' => 'Hand (Arrow)'
        ],
        '\f25b' => [
            'class' => 'fa fa-hand-peace',
            'classes' => ['fa-hand-peace'],
            'content' => '\f25b',
            'text_de' => 'Hand (Frieden)',
            'text_en' => 'Hand (Peace)'
        ],
        '\f25c' => [
            'class' => 'fa fa-trademark',
            'classes' => ['fa-trademark'],
            'content' => '\f25c',
            'text_de' => 'Warenzeichen',
            'text_en' => 'Trademark'
        ],
        '\f25d' => [
            'class' => 'fa fa-registered',
            'classes' => ['fa-registered'],
            'content' => '\f25d',
            'text_de' => 'Eingetragen',
            'text_en' => 'Registered'
        ],
        '\f25e' => [
            'class' => 'fab fa-creative-commons',
            'classes' => ['fa-creative-commons'],
            'content' => '\f25e',
            'text_de' => 'Logos: Creative Commons',
            'text_en' => 'Logos: Creative Commons'
        ],
        '\f260' => [
            'class' => 'fab fa-gg',
            'classes' => ['fa-gg'],
            'content' => '\f260'
        ],
        '\f261' => [
            'class' => 'fab fa-gg-circle',
            'classes' => ['fa-gg-circle'],
            'content' => '\f261'
        ],
        '\f263' => [
            'class' => 'fab fa-odnoklassniki',
            'classes' => ['fa-odnoklassniki'],
            'content' => '\f263',
            'text_de' => 'Logos: Odnoklassniki',
            'text_en' => 'Logos: Odnoklassniki'
        ],
        '\f264' => [
            'class' => 'fab fa-odnoklassniki-square',
            'classes' => ['fa-odnoklassniki-square'],
            'content' => '\f264',
            'text_de' => 'Logos: Odnoklassniki quadratisches Logo',
            'text_en' => 'Logos: Odnoklassniki Square Logo'
        ],
        '\f265' => [
            'class' => 'fab fa-get-pocket',
            'classes' => ['fa-get-pocket'],
            'content' => '\f265',
            'text_de' => 'Logos: Pocket',
            'text_en' => 'Logos: Pocket'
        ],
        '\f266' => [
            'class' => 'fab fa-wikipedia-w',
            'classes' => ['fa-wikipedia-w'],
            'content' => '\f266'
        ],
        '\f267' => [
            'class' => 'fa fa-safari',
            'classes' => ['fa-safari'],
            'content' => '\f267'
        ],
        '\f268' => [
            'class' => 'fa fa-chrome',
            'classes' => ['fa-chrome'],
            'content' => '\f268'
        ],
        '\f269' => [
            'class' => 'fa fa-firefox',
            'classes' => ['fa-firefox'],
            'content' => '\f269'
        ],
        '\f26a' => [
            'class' => 'fa fa-opera',
            'classes' => ['fa-opera'],
            'content' => '\f26a'
        ],
        '\f26b' => [
            'class' => 'fa fa-internet-explorer',
            'classes' => ['fa-internet-explorer'],
            'content' => '\f26b'
        ],
        '\f26c' => [
            'class' => 'fa fa-tv',
            'classes' => ['fa-tv'],
            'content' => '\f26c',
            'text_de' => 'Fernseher',
            'text_en' => 'TV'
        ],
        '\f26d' => [
            'class' => 'fa fa-contao',
            'classes' => ['fa-contao'],
            'content' => '\f26d'
        ],
        '\f26e' => [
            'class' => 'fab fa-500px',
            'classes' => ['fa-500px'],
            'content' => '\f26e'
        ],
        '\f270' => [
            'class' => 'fa fa-amazon',
            'classes' => ['fa-amazon'],
            'content' => '\f270'
        ],
        '\f271' => [
            'class' => 'fa fa-calendar-plus',
            'classes' => ['fa-calendar-plus'],
            'content' => '\f271',
            'text_de' => 'Kalender (Plus)',
            'text_en' => 'Calendar (Plus)'
        ],
        '\f272' => [
            'class' => 'fa fa-calendar-minus',
            'classes' => ['fa-calendar-minus'],
            'content' => '\f272',
            'text_de' => 'Kalender (Minus)',
            'text_en' => 'Calendar (Minus)'
        ],
        '\f273' => [
            'class' => 'fa fa-calendar-times',
            'classes' => ['fa-calendar-times'],
            'content' => '\f273',
            'text_de' => 'Kalender (X-Zeichen)',
            'text_en' => 'Calendar (X-Mark)'
        ],
        '\f274' => [
            'class' => 'fa fa-calendar-check',
            'classes' => ['fa-calendar-check', 'icon-calendar-2', 'icon-calendar-check'],
            'content' => '\f274',
            'text_de' => 'Kalender (Häkchen)',
            'text_en' => 'Calendar (Checkmark)'
        ],
        '\f275' => [
            'class' => 'fa fa-industry',
            'classes' => ['fa-industry'],
            'content' => '\f275',
            'text_de' => 'Fabrik',
            'text_en' => 'Factory'
        ],
        '\f276' => [
            'class' => 'fa fa-map-pin',
            'classes' => ['fa-map-pin'],
            'content' => '\f276',
            'text_de' => 'Anstecknadel',
            'text_en' => 'Pin'
        ],
        '\f277' => [
            'class' => 'fa fa-map-signs',
            'classes' => ['fa-map-signs', 'icon-map-signs'],
            'content' => '\f277',
            'text_de' => 'Wegweiser',
            'text_en' => 'Sign Post'
        ],
        '\f279' => [
            'class' => 'fa fa-map',
            'classes' => ['fa-map'],
            'content' => '\f279',
            'text_de' => 'Karte',
            'text_en' => 'Map'
        ],
        '\f27a' => [
            'class' => 'fa fa-comment-alt',
            'classes' => ['fa-comment-alt'],
            'content' => '\f27a',
            'text_de' => 'Nachricht',
            'text_en' => 'Message'
        ],
        '\f27c' => [
            'class' => 'fab fa-houzz',
            'classes' => ['fa-houzz'],
            'content' => '\f27c'
        ],
        '\f27d' => [
            'class' => 'fab fa-vimeo-v',
            'classes' => ['fa-vimeo-v'],
            'content' => '\f27d',
            'text_de' => 'Logos: vimeo (V)',
            'text_en' => 'Logos: vimeo (V)'
        ],
        '\f27e' => [
            'class' => 'fab fa-black-tie',
            'classes' => ['fa-black-tie'],
            'content' => '\f27e',
            'text_de' => 'Logos: Schwarze Krawatte',
            'text_en' => 'Logos: Black Tie'
        ],
        '\f280' => [
            'class' => 'fab fa-fonticons',
            'classes' => ['fa-fonticons'],
            'content' => '\f280'
        ],
        '\f281' => [
            'class' => 'fab fa-reddit-alien',
            'classes' => ['fa-reddit-alien'],
            'content' => '\f281',
            'text_de' => 'Logos: reddit (Alien)',
            'text_en' => 'Logos: reddit (Alien)'
        ],
        '\f282' => [
            'class' => 'fab fa-edge',
            'classes' => ['fa-edge'],
            'content' => '\f282'
        ],
        '\f284' => [
            'class' => 'fab fa-codiepie',
            'classes' => ['fa-codiepie'],
            'content' => '\f284'
        ],
        '\f285' => [
            'class' => 'fab fa-modx',
            'classes' => ['fa-modx'],
            'content' => '\f285'
        ],
        '\f286' => [
            'class' => 'fab fa-fort-awesome',
            'classes' => ['fa-fort-awesome'],
            'content' => '\f286'
        ],
        '\f287' => [
            'class' => 'fab fa-usb',
            'classes' => ['fa-usb'],
            'content' => '\f287',
            'text_de' => 'USB',
            'text_en' => 'USB'
        ],
        '\f288' => [
            'class' => 'fab fa-product-hunt',
            'classes' => ['fa-product-hunt'],
            'content' => '\f288'
        ],
        '\f289' => [
            'class' => 'fab fa-mixcloud',
            'classes' => ['fa-mixcloud'],
            'content' => '\f289',
            'text_de' => 'Logos: Mixcloud',
            'text_en' => 'Logos: Mixcloud'
        ],
        '\f28a' => [
            'class' => 'fab fa-scribd',
            'classes' => ['fa-scribd'],
            'content' => '\f28a'
        ],
        '\f28b' => [
            'class' => 'fa fa-pause-circle',
            'classes' => ['fa-pause-circle', 'icon-pause-circle'],
            'content' => '\f28b',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Pause im Kreis',
            'text_en' => 'Pause in a Circle'
        ],
        '\f28d' => [
            'class' => 'fa fa-stop-circle',
            'classes' => ['fa-stop-circle', 'icon-stop-circle'],
            'content' => '\f28d',
            'context' => self::PLAYER_BUTTONS,
            'text_de' => 'Stopp im Kreis',
            'text_en' => 'Stop in a Circle'
        ],
        '\f290' => [
            'class' => 'fa fa-shopping-bag',
            'classes' => ['fa-shopping-bag'],
            'content' => '\f290',
            'text_de' => 'Tasche',
            'text_en' => 'Bag'
        ],
        '\f291' => [
            'class' => 'fa fa-shopping-basket',
            'classes' => ['fa-shopping-basket', 'icon-basket', 'icon-contract-2'],
            'content' => '\f291',
            'text_de' => 'Korb',
            'text_en' => 'Basket'
        ],
        '\f292' => [
            // deprecated
            'class' => 'fa fa-hashtag',
            'classes' => ['fa-hashtag'],
            // unicode 23
            'content' => '\f292',
            'text_de' => 'Raute',
            'text_en' => 'Hashtag'
        ],
        '\f293' => [
            'class' => 'fab fa-bluetooth',
            'classes' => ['fa-bluetooth'],
            'content' => '\f293',
            'text_de' => 'Bluetooth',
            'text_en' => 'Bluetooth'
        ],
        '\f294' => [
            'class' => 'fab fa-bluetooth-b',
            'classes' => ['fa-bluetooth-b'],
            'content' => '\f294',
            'text_de' => 'Bluetooth (B)',
            'text_en' => 'Bluetooth (B)'
        ],
        '\f295' => [
            // deprecated
            'class' => 'fa fa-percent',
            'classes' => ['fa-percent'],
            // unicode 25
            'content' => '\f295',
            'text_de' => 'Prozent',
            'text_en' => 'Percent'
        ],
        '\f296' => [
            'class' => 'fab fa-gitlab',
            'classes' => ['fa-gitlab'],
            'content' => '\f296',
            'text_de' => 'Logos: GitLab',
            'text_en' => 'Logos: GitLab'
        ],
        '\f297' => [
            'class' => 'fab fa-wpbeginner',
            'classes' => ['fa-wpbeginner'],
            'content' => '\f297'
        ],
        '\f298' => [
            'class' => 'fab fa-wpforms',
            'classes' => ['fa-wpforms'],
            'content' => '\f298'
        ],
        '\f299' => [
            'class' => 'fab fa-envira',
            'classes' => ['fa-envira'],
            'content' => '\f299'
        ],
        '\f29a' => [
            'class' => 'fa fa-universal-access',
            'classes' => ['fa-universal-access', 'icon-accessible', 'icon-universal', 'icon-universal-access'],
            'content' => '\f29a',
            'text_de' => 'Universeller Zugang',
            'text_en' => 'Universal Access'
        ],
        '\f29d' => [
            'class' => 'fa fa-blind',
            'classes' => ['fa-blind'],
            'content' => '\f29d',
            'text_de' => 'Personen: blinde Person',
            'text_en' => 'People: Blind Person'
        ],
        '\f29e' => [
            'class' => 'fa fa-audio-description',
            'classes' => ['fa-audio-description'],
            'content' => '\f29e',
            'text_de' => 'Audiobeschreibung',
            'text_en' => 'Audio Description'
        ],
        '\f2a0' => [
            'class' => 'fa fa-phone-volume',
            'classes' => ['fa-phone-volume'],
            'content' => '\f2a0',
            'text_de' => 'Telefon (Lautstärke)',
            'text_en' => 'Phone (Volume)'
        ],
        '\f2a1' => [
            'class' => 'fa fa-braille',
            'classes' => ['fa-braille'],
            'content' => '\f2a1',
            'text_de' => 'Blindenschrift',
            'text_en' => 'Braille'
        ],
        '\f2a2' => [
            'class' => 'fa fa-assistive-listening-systems',
            'classes' => ['fa-assistive-listening-systems'],
            'content' => '\f2a2',
            'text_de' => 'Höhrgerät',
            'text_en' => 'Hearing Aid'
        ],
        '\f2a3' => [
            'class' => 'fa fa-american-sign-language-interpreting',
            'classes' => ['fa-american-sign-language-interpreting'],
            'content' => '\f2a3',
            'text_de' => 'Zeichensprache',
            'text_en' => 'Sign Language'
        ],
        '\f2a4' => [
            'class' => 'fa fa-deaf',
            'classes' => ['fa-deaf'],
            'content' => '\f2a4',
            'text_de' => 'Taub',
            'text_en' => 'Deaf'
        ],
        '\f2a5' => [
            'class' => 'fab fa-glide',
            'classes' => ['fa-glide'],
            'content' => '\f2a5'
        ],
        '\f2a6' => [
            'class' => 'fab fa-glide-g',
            'classes' => ['fa-glide-g'],
            'content' => '\f2a6'
        ],
        '\f2a7' => [
            'class' => 'fa fa-sign-language',
            'classes' => ['fa-sign-language'],
            'content' => '\f2a7',
            'text_de' => 'Hände',
            'text_en' => 'Hands'
        ],
        '\f2a8' => [
            'class' => 'fa fa-low-vision',
            'classes' => ['fa-low-vision'],
            'content' => '\f2a8',
            'text_de' => 'Sehbehinderung',
            'text_en' => 'Visual Impairment'
        ],
        '\f2a9' => [
            'class' => 'fab fa-viadeo',
            'classes' => ['fa-viadeo'],
            'content' => '\f2a9',
            'text_de' => 'Logos: viadeo',
            'text_en' => 'Logos: viadeo'
        ],
        '\f2aa' => [
            'class' => 'fab fa-viadeo-square',
            'classes' => ['fa-viadeo-square'],
            'content' => '\f2aa',
            'text_de' => 'Logos: viadeo quadratisches Logo',
            'text_en' => 'Logos: viadeo Square Logo'
        ],
        '\f2ab' => [
            'class' => 'fab fa-snapchat',
            'classes' => ['fa-snapchat'],
            'content' => '\f2ab',
            'text_de' => 'Logos: Snapchat',
            'text_en' => 'Logos: Snapchat'
        ],
        '\f2ac' => [
            'class' => 'fab fa-snapchat-ghost',
            'classes' => ['fa-snapchat-ghost'],
            'content' => '\f2ac',
            'text_de' => 'Logos: Snapchat (Gespenst)',
            'text_en' => 'Logos: Snapchat (Ghost)'
        ],
        '\f2ad' => [
            'class' => 'fab fa-snapchat-square',
            'classes' => ['fa-snapchat-square'],
            'content' => '\f2ad',
            'text_de' => 'Logos: Snapchat quadratisches Logo',
            'text_en' => 'Logos: Snapchat Square Logo'
        ],
        '\f2ae' => [
            'class' => 'fab fa-pied-piper',
            'classes' => ['fa-pied-piper'],
            'content' => '\f2ae',
            // piedpipergroup.com: consulting => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f2b0' => [
            'class' => 'fab fa-first-order',
            'classes' => ['fa-first-order'],
            'content' => '\f2b0',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f2b1' => [
            'class' => 'fab fa-yoast',
            'classes' => ['fa-yoast'],
            'content' => '\f2b1'
        ],
        '\f2b2' => [
            'class' => 'fab fa-themeisle',
            'classes' => ['fa-themeisle'],
            'content' => '\f2b2'
        ],
        '\f2b3' => [
            'class' => 'fab fa-google-plus',
            'classes' => ['fa-google-plus'],
            'content' => '\f2b3'
        ],
        '\f2b4' => [
            'class' => 'fab fa-font-awesome',
            'classes' => ['fa-font-awesome'],
            'content' => '\f2b4'
        ],
        '\f2b5' => [
            'class' => 'fa fa-handshake',
            'classes' => ['fa-handshake', 'icon-handshake'],
            'content' => '\f2b5',
            'text_de' => 'Schüttelnde Hände (detailliert)',
            'text_en' => 'Handshake (Detailed)'
        ],
        '\f2b6' => [
            'class' => 'fa fa-envelope-open',
            'classes' => ['fa-envelope-open'],
            'content' => '\f2b6',
            'text_de' => 'Umschlag (offen)',
            'text_en' => 'Envelope (Open)'
        ],
        '\f2b8' => [
            'class' => 'fab fa-linode',
            'classes' => ['fa-linode'],
            'content' => '\f2b8'
        ],
        '\f2b9' => [
            'class' => 'fa fa-address-book',
            'classes' => ['fa-address-book', 'icon-address', 'icon-address-book'],
            'content' => '\f2b9',
            'text_de' => 'Adressbuch',
            'text_en' => 'Address Book'
        ],
        '\f2bb' => [
            'class' => 'fa fa-address-card',
            'classes' => ['fa-address-card', 'icon-vcard'],
            'content' => '\f2bb',
            'text_de' => 'Visitenkarte',
            'text_en' => 'Business Card'
        ],
        '\f2bd' => [
            'class' => 'fa fa-user-circle',
            'classes' => ['fa-user-circle', 'icon-user-circle'],
            'content' => '\f2bd',
            'text_de' => 'Personen: Person im Kreis',
            'text_en' => 'People: Person on a Circle'
        ],
        '\f2c1' => [
            'class' => 'fa fa-id-badge',
            'classes' => ['fa-id-badge'],
            'content' => '\f2c1',
            'text_de' => 'ID-Abzeichen',
            'text_en' => 'ID-Badge'
        ],
        '\f2c2' => [
            'class' => 'fa fa-id-card',
            'classes' => ['fa-id-card'],
            'content' => '\f2c2',
            'text_de' => 'Ausweis',
            'text_en' => 'ID-Card'
        ],
        '\f2c4' => [
            'class' => 'fab fa-quora',
            'classes' => ['fa-quora'],
            'content' => '\f2c4',
            'text_de' => 'Logos: Quora',
            'text_en' => 'Logos: Quora'
        ],
        '\f2c5' => [
            'class' => 'fab fa-free-code-camp',
            'classes' => ['fa-free-code-camp'],
            'content' => '\f2c5'
        ],
        '\f2c6' => [
            'class' => 'fab fa-telegram',
            'classes' => ['fa-telegram'],
            'content' => '\f2c6',
            'text_de' => 'Logos: Telegram',
            'text_en' => 'Logos: Telegram'
        ],
        '\f2c7' => [
            'class' => 'fa fa-thermometer-full',
            'classes' => ['fa-thermometer-full'],
            'content' => '\f2c7',
            'text_de' => 'Thermometer (voll)',
            'text_en' => 'Thermometer (Full)'
        ],
        '\f2c8' => [
            'class' => 'fa fa-thermometer-three-quarters',
            'classes' => ['fa-thermometer-three-quarters'],
            'content' => '\f2c8',
            'text_de' => 'Thermometer (drei-viertel)',
            'text_en' => 'Thermometer (Three-Quarters)'
        ],
        '\f2c9' => [
            'class' => 'fa fa-thermometer-half',
            'classes' => ['fa-thermometer-half'],
            'content' => '\f2c9',
            'text_de' => 'Thermometer (halb-voll)',
            'text_en' => 'Thermometer (Half)'
        ],
        '\f2ca' => [
            'class' => 'fa fa-thermometer-quarter',
            'classes' => ['fa-thermometer-quarter'],
            'content' => '\f2ca',
            'text_de' => 'Thermometer (viertel-voll)',
            'text_en' => 'Thermometer (One-Quarter)'
        ],
        '\f2cb' => [
            'class' => 'fa fa-thermometer-empty',
            'classes' => ['fa-thermometer-empty'],
            'content' => '\f2cb',
            'text_de' => 'Thermometer (leer)',
            'text_en' => 'Thermometer (Empty)'
        ],
        '\f2cc' => [
            'class' => 'fa fa-shower',
            'classes' => ['fa-shower'],
            'content' => '\f2cc',
            'context' => self::BATH,
            'text_de' => 'Dusche',
            'text_en' => 'Shower'
        ],
        '\f2cd' => [
            'class' => 'fa fa-bath',
            'classes' => ['fa-bath'],
            'content' => '\f2cd',
            'context' => self::BATH,
            'text_de' => 'Badewanne',
            'text_en' => 'Bath'
        ],
        '\f2ce' => [
            'class' => 'fa fa-podcast',
            'classes' => ['fa-podcast'],
            'content' => '\f2ce',
            'text_de' => 'Podcast',
            'text_en' => 'Podcast'
        ],
        '\f2d0' => [
            'class' => 'fa fa-window-maximize',
            'classes' => ['fa-window-maximize'],
            'content' => '\f2d0',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Maximieren',
            'text_en' => 'Maximize'
        ],
        '\f2d1' => [
            'class' => 'fa fa-window-minimize',
            'classes' => ['fa-window-minimize'],
            'content' => '\f2d1',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Minimieren',
            'text_en' => 'Minimize'
        ],
        '\f2d2' => [
            'class' => 'fa fa-window-restore',
            'classes' => ['fa-window-restore'],
            'content' => '\f2d2',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Wiederherstellen',
            'text_en' => 'Restore'
        ],
        '\f2d5' => [
            'class' => 'fab fa-bandcamp',
            'classes' => ['fa-bandcamp'],
            'content' => '\f2d5',
            'text_de' => 'Logos: Bandcamp',
            'text_en' => 'Logos: Bandcamp'
        ],
        '\f2d6' => [
            'class' => 'fab fa-grav',
            'classes' => ['fa-grav'],
            'content' => '\f2d6'
        ],
        '\f2d7' => [
            'class' => 'fab fa-etsy',
            'classes' => ['fa-etsy'],
            'content' => '\f2d7'
        ],
        '\f2d8' => [
            'class' => 'fab fa-imdb',
            'classes' => ['fa-imdb'],
            'content' => '\f2d8'
        ],
        '\f2d9' => [
            'class' => 'fab fa-ravelry',
            'classes' => ['fa-ravelry'],
            'content' => '\f2d9',
            'text_de' => 'Logos: Ravelry',
            'text_en' => 'Logos: Ravelry'
        ],
        '\f2da' => [
            'class' => 'fab fa-sellcast',
            'classes' => ['fa-sellcast'],
            'content' => '\f2da',
            'text_de' => 'Logos: SellCast',
            'text_en' => 'Logos: SellCast'
        ],
        '\f2db' => [
            'class' => 'fa fa-microchip',
            'classes' => ['fa-microchip'],
            'content' => '\f2db',
            'text_de' => 'Microchip',
            'text_en' => 'Microchip'
        ],
        '\f2dc' => [
            'class' => 'fa fa-snowflake',
            'classes' => ['fa-snowflake'],
            'content' => '\f2dc',
            'text_de' => 'Snowflake',
            'text_en' => 'Snowflake'
        ],
        '\f2dd' => [
            'class' => 'fab fa-superpowers',
            'classes' => ['fa-superpowers'],
            'content' => '\f2dd'
        ],
        '\f2de' => [
            'class' => 'fab fa-wpexplorer',
            'classes' => ['fa-wpexplorer'],
            'content' => '\f2de'
        ],
        '\f2e0' => [
            'class' => 'fab fa-meetup',
            'classes' => ['fa-meetup'],
            'content' => '\f2e0',
            'text_de' => 'Logos: Meetup',
            'text_en' => 'Logos: Meetup'
        ],
        '\f2e5' => [
            'class' => 'fa fa-utensil-spoon',
            'classes' => ['fa-utensil-spoon'],
            'content' => '\f2e5',
            'text_de' => 'Funktionsgegenstände: Löffel',
            'text_en' => 'Functional Items: Spoon'
        ],
        '\f2e7' => [
            'class' => 'fa fa-utensils',
            'classes' => ['fa-utensils'],
            'content' => '\f2e7',
            'text_de' => 'Funktionsgegenstände: Utensilien',
            'text_en' => 'Functional Items: Utensils'
        ],
        '\f2ea' => [
            'class' => 'fa fa-undo-alt',
            'classes' => ['fa-undo-alt'],
            'content' => '\f2ea',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Zurücksetzen, Dreieck-Pfeil',
            'text_en' => 'Reset, Triangle-Arrow'
        ],
        '\f2ed' => [
            // Bulky, striped
            'class' => 'fa fa-trash-alt',
            'classes' => ['fa-trash-alt'],
            'content' => '\f2ed'
        ],
        '\f2f1' => [
            'class' => 'fa fa-sync-alt',
            'classes' => ['fa-sync-alt'],
            'content' => '\f2f1',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Aktualisieren (Dreieck-Pfeile)',
            'text_en' => 'Refresh (Triangle-Arrows)'
        ],
        '\f2f2' => [
            'class' => 'fa fa-stopwatch',
            'classes' => ['fa-stopwatch'],
            'content' => '\f2f2',
            'context' => self::PRIMARY,
            'text_de' => 'Stoppuhr',
            'text_en' => 'Stopwatch'
        ],
        '\f2f5' => [
            'class' => 'fa fa-sign-out-alt',
            'classes' => ['fa-sign-out-alt', 'icon-exit'],
            'content' => '\f2f5',
            'context' => self::PRIMARY,
            'text_de' => 'Verlassen',
            'text_en' => 'Exit'
        ],
        '\f2f6' => [
            'class' => 'fa fa-sign-in-alt',
            'classes' => ['fa-sign-in-alt', 'icon-enter', 'icon-signup'],
            'content' => '\f2f6',
            'context' => self::PRIMARY,
            'text_de' => 'Eintreten',
            'text_en' => 'Enter'
        ],
        '\f2f9' => [
            'class' => 'fa fa-redo-alt',
            'classes' => ['fa-redo-alt'],
            'content' => '\f2f9',
            'context' => self::SYSTEM_FUNCTIONS,
            'text_de' => 'Wiederholen (Dreieck-Pfeil)',
            'text_en' => 'Redo (Triangle-Arrow)'
        ],
        '\f2fe' => [
            'class' => 'fa fa-poo',
            'classes' => ['fa-poo'],
            'content' => '\f2fe',
            // vulgar
            'context' => self::SUPPRESSED
        ],
        '\f302' => [
            'class' => 'fa fa-images',
            'classes' => ['fa-images'],
            'content' => '\f302',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Bilder',
            'text_en' => 'Pictures'
        ],
        '\f303' => [
            'class' => 'fa fa-pencil-alt',
            'classes' => ['fa-pencil-alt', 'icon-pencil-2', 'icon-pencil-alt'],
            'content' => '\f303',
            'text_de' => 'Funktionsgegenstände: Stift',
            'text_en' => 'Functional Items: Pencil'
        ],
        '\f304' => [
            'class' => 'fa fa-pen',
            'classes' => ['fa-pen'],
            'content' => '\f304',
            'text_de' => 'Funktionsgegenstände: Stift (vereinfacht)',
            'text_en' => 'Functional Items: Pencil (Simplified)'
        ],
        '\f305' => [
            'class' => 'fa fa-pen-alt',
            'classes' => ['fa-pen-alt'],
            'content' => '\f305',
            'text_de' => 'Funktionsgegenstände: Kugelschreiber',
            'text_en' => 'Functional Items: Pen'
        ],
        '\f309' => [
            'class' => 'fa fa-long-arrow-alt-down',
            'classes' => ['fa-long-arrow-alt-down'],
            'content' => '\f309',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach unten, schmal',
            'text_en' => 'Arrow, Down, Slender'
        ],
        '\f30a' => [
            'class' => 'fa fa-long-arrow-alt-left',
            'classes' => ['fa-long-arrow-alt-left'],
            'content' => '\f30a',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach links, schmal',
            'text_en' => 'Arrow, Left, Slender'
        ],
        '\f30b' => [
            'class' => 'fa fa-long-arrow-alt-right',
            'classes' => ['fa-long-arrow-alt-right'],
            'content' => '\f30b',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach rechts, schmal',
            'text_en' => 'Arrow, Right, Slender'
        ],
        '\f30c' => [
            'class' => 'fa fa-long-arrow-alt-up',
            'classes' => ['fa-long-arrow-alt-up'],
            'content' => '\f30c',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach oben, schmal',
            'text_en' => 'Arrow, Up, Slender'
        ],
        '\f31e' => [
            'class' => 'fa fa-expand-arrows-alt',
            'classes' => ['fa-expand-arrows-alt', 'icon-expand-2'],
            'content' => '\f31e',
            'text_de' => 'Vergrößern (Pfeile, 4x)',
            'text_en' => 'Expand (Arrows, 4x)'
        ],
        '\f328' => [
            'class' => 'fa fa-clipboard',
            'classes' => ['fa-clipboard', 'icon-clipboard'],
            'content' => '\f328',
            'text_de' => 'Klemmbrett',
            'text_en' => 'Clipboard'
        ],
        '\f337' => [
            'class' => 'fa fa-arrows-alt-h',
            'classes' => ['fa-arrows-alt-h'],
            'content' => '\f337',
            'text_de' => 'Pfeile (waagerechte Bewegung)',
            'text_en' => 'Arrows (Horizontal Movement)'
        ],
        '\f338' => [
            'class' => 'fa fa-arrows-alt-v',
            'classes' => ['fa-arrows-alt-v'],
            'content' => '\f338',
            'text_de' => 'Pfeile (senkrechte Bewegung)',
            'text_en' => 'Arrows (Vertical Movement)'
        ],
        '\f358' => [
            'class' => 'fa fa-arrow-alt-circle-down',
            'classes' => ['fa-arrow-alt-circle-down', 'icon-arrow-down-2'],
            'content' => '\f358',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach unten, schmal, im Kreis',
            'text_en' => 'Arrow, Down, Slender, on a Circle'
        ],
        '\f359' => [
            'class' => 'fa fa-arrow-alt-circle-left',
            'classes' => ['fa-arrow-alt-circle-left', 'icon-arrow-left-2', 'icon-backward-2', 'icon-reply'],
            'content' => '\f359',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach links, schmal, im Kreis',
            'text_en' => 'Arrow, Left, Slender, on a Circle'
        ],
        '\f35a' => [
            'class' => 'fa fa-arrow-alt-circle-right',
            'classes' => ['fa-arrow-alt-circle-right', 'icon-arrow-right-2', 'icon-forward-2', 'icon-register'],
            'content' => '\f35a',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach rechts, schmal, im Kreis',
            'text_en' => 'Arrow, Right, Slender, on a Circle'
        ],
        '\f35b' => [
            'class' => 'fa fa-arrow-alt-circle-up',
            'classes' => ['fa-arrow-alt-circle-up', 'icon-arrow-up-2'],
            'content' => '\f35b',
            'context' => self::POINTERS,
            'text_de' => 'Pfeil, nach oben, schmal, im Kreis',
            'text_en' => 'Arrow, Up, Slender, on a Circle'
        ],
        '\f35c' => [
            'class' => 'fa fa-font-awesome-alt',
            'classes' => ['fa-font-awesome-alt'],
            'content' => '\f35c'
        ],
        '\f35d' => [
            'class' => 'fa fa-external-link-alt',
            'classes' => ['fa-external-link-alt', 'icon-external-link-alt', 'icon-new-tab', 'icon-out-2'],
            'content' => '\f35d',
            'text_de' => 'Externer Link / Neuer Tab (dunn)',
            'text_en' => 'External Link / New Tab (Thin)'
        ],
        '\f360' => [
            'class' => 'fa fa-external-link-square-alt',
            'classes' => ['fa-external-link-square-alt', 'icon-new-tab-2', 'icon-out-3'],
            'content' => '\f360',
            'text_de' => 'Externer Link / Neuer Tab (sperrig)',
            'text_en' => 'External Link / New Tab (Blocky)'
        ],
        '\f362' => [
            'class' => 'fa fa-exchange-alt',
            'classes' => ['fa-exchange-alt'],
            'content' => '\f362',
            'text_de' => 'Handeln / Tauschen / Wechseln',
            'text_en' => 'Exchange / Switch / Trade'
        ],
        '\f368' => [
            'class' => 'fab fa-accessible-icon',
            'classes' => ['fa-accessible-icon'],
            'content' => '\f368',
            'text_de' => 'Zugänglich',
            'text_en' => 'Accessible'
        ],
        '\f369' => [
            'class' => 'fab fa-accusoft',
            'classes' => ['fa-accusoft'],
            'content' => '\f369'
        ],
        '\f36a' => [
            'class' => 'fab fa-adversal',
            'classes' => ['fa-adversal'],
            'content' => '\f36a'
        ],
        '\f36b' => [
            'class' => 'fab fa-affiliatetheme',
            'classes' => ['fa-affiliatetheme'],
            'content' => '\f36b'
        ],
        '\f36c' => [
            'class' => 'fab fa-algolia',
            'classes' => ['fa-algolia'],
            'content' => '\f36c'
        ],
        '\f36d' => [
            'class' => 'fab fa-amilia',
            'classes' => ['fa-amilia'],
            'content' => '\f36d'
        ],
        '\f36e' => [
            'class' => 'fab fa-angrycreative',
            'classes' => ['fa-angrycreative'],
            'content' => '\f36e'
        ],
        '\f36f' => [
            'class' => 'fab fa-app-store',
            'classes' => ['fa-app-store'],
            'content' => '\f36f',
            'text_de' => 'Logos: App Store',
            'text_en' => 'Logos: App Store'
        ],
        '\f370' => [
            'class' => 'fab fa-app-store-ios',
            'classes' => ['fa-app-store-ios'],
            'content' => '\f370',
            'text_de' => 'Logos: App Store quadratisches Logo',
            'text_en' => 'Logos: App Store Square Logo'
        ],
        '\f371' => [
            'class' => 'fab fa-apper',
            'classes' => ['fa-apper'],
            'content' => '\f371'
        ],
        '\f372' => [
            'class' => 'fab fa-asymmetrik',
            'classes' => ['fa-asymmetrik'],
            'content' => '\f372'
        ],
        '\f373' => [
            'class' => 'fab fa-audible',
            'classes' => ['fa-audible'],
            'content' => '\f373'
        ],
        '\f374' => [
            'class' => 'fab fa-avianex',
            'classes' => ['fa-avianex'],
            'content' => '\f374'
        ],
        '\f375' => [
            'class' => 'fab fa-aws',
            'classes' => ['fa-aws'],
            'content' => '\f375'
        ],
        '\f378' => [
            'class' => 'fab fa-bimobject',
            'classes' => ['fa-bimobject'],
            'content' => '\f378'
        ],
        '\f379' => [
            'class' => 'fab fa-bitcoin',
            'classes' => ['fa-bitcoin'],
            'content' => '\f379',
            'context' => self::FINANCE,
            'text_de' => 'Bitcoin, kreisförmiges Logo',
            'text_en' => 'Bitcoin, Circle Logo'
        ],
        '\f37a' => [
            'class' => 'fab fa-bity',
            'classes' => ['fa-bity'],
            'content' => '\f37a'
        ],
        '\f37b' => [
            'class' => 'fab fa-blackberry',
            'classes' => ['fa-blackberry'],
            'content' => '\f37b'
        ],
        '\f37c' => [
            'class' => 'fab fa-blogger',
            'classes' => ['fa-blogger'],
            'content' => '\f37c',
            'text_de' => 'Logos: Blogger',
            'text_en' => 'Logos: Blogger'
        ],
        '\f37d' => [
            'class' => 'fab fa-blogger-b',
            'classes' => ['fa-blogger-b'],
            'content' => '\f37d',
            'text_de' => 'Logos: Blogger (B)',
            'text_en' => 'Logos: Blogger (B)'
        ],
        '\f37f' => [
            'class' => 'fab fa-buromobelexperte',
            'classes' => ['fa-buromobelexperte'],
            'content' => '\f37f'
        ],
        '\f380' => [
            'class' => 'fab fa-centercode',
            'classes' => ['fa-centercode'],
            'content' => '\f380'
        ],
        '\f381' => [
            'class' => 'fa fa-cloud-download-alt',
            'classes' => ['fa-cloud-download-alt', 'icon-cloud-download', 'icon-cloud-download-alt'],
            'content' => '\f381',
            'text_de' => 'Herunterladen (Cloud)',
            'text_en' => 'Download (Cloud)'
        ],
        '\f382' => [
            'class' => 'fa fa-cloud-upload-alt',
            'classes' => ['fa-cloud-upload-alt', 'icon-cloud-upload'],
            'content' => '\f382',
            'text_de' => 'Hochladen (Cloud)',
            'text_en' => 'Upload (Cloud)'
        ],
        '\f383' => [
            'class' => 'fab fa-cloudscale',
            'classes' => ['fa-cloudscale'],
            'content' => '\f383'
        ],
        '\f384' => [
            'class' => 'fab fa-cloudsmith',
            'classes' => ['fa-cloudsmith'],
            'content' => '\f384'
        ],
        '\f385' => [
            'class' => 'fab fa-cloudversify',
            'classes' => ['fa-cloudversify'],
            'content' => '\f385'
        ],
        '\f388' => [
            'class' => 'fab fa-cpanel',
            'classes' => ['fa-cpanel'],
            'content' => '\f388'
        ],
        '\f38b' => [
            'class' => 'fab fa-css3-alt',
            'classes' => ['fa-css3-alt'],
            'content' => '\f38b'
        ],
        '\f38c' => [
            'class' => 'fab fa-cuttlefish',
            'classes' => ['fa-cuttlefish'],
            'content' => '\f38c'
        ],
        '\f38d' => [
            'class' => 'fab fa-d-and-d',
            'classes' => ['fa-d-and-d'],
            'content' => '\f38d'
        ],
        '\f38e' => [
            'class' => 'fab fa-deploydog',
            'classes' => ['fa-deploydog'],
            'content' => '\f38e'
        ],
        '\f38f' => [
            'class' => 'fab fa-deskpro',
            'classes' => ['fa-deskpro'],
            'content' => '\f38f'
        ],
        '\f391' => [
            'class' => 'fab fa-digital-ocean',
            'classes' => ['fa-digital-ocean'],
            'content' => '\f391'
        ],
        '\f392' => [
            'class' => 'fab fa-discord',
            'classes' => ['fa-discord'],
            'content' => '\f392',
            'text_de' => 'Logos: Discord',
            'text_en' => 'Logos: Discord'
        ],
        '\f393' => [
            'class' => 'fab fa-discourse',
            'classes' => ['fa-discourse'],
            'content' => '\f393'
        ],
        '\f394' => [
            'class' => 'fab fa-dochub',
            'classes' => ['fa-dochub'],
            'content' => '\f394'
        ],
        '\f395' => [
            'class' => 'fa fa-docker',
            'classes' => ['fa-docker'],
            'content' => '\f395'
        ],
        '\f396' => [
            'class' => 'fab fa-draft2digital',
            'classes' => ['fa-draft2digital'],
            'content' => '\f396',
            'text_de' => 'Logos: Draft2Digital',
            'text_en' => 'Logos: Draft2Digital'
        ],
        '\f397' => [
            'class' => 'fab fa-dribbble-square',
            'classes' => ['fa-dribbble-square'],
            'content' => '\f397',
            'text_de' => 'Logos: dribbble quadratisches Logo',
            'text_en' => 'Logos: dribbble Square Logo'
        ],
        '\f399' => [
            'class' => 'fa fa-dyalog',
            'classes' => ['fa-dyalog'],
            'content' => '\f399'
        ],
        '\f39a' => [
            'class' => 'fa fa-earlybirds',
            'classes' => ['fa-earlybirds'],
            'content' => '\f39a'
        ],
        '\f39d' => [
            'class' => 'fa fa-erlang',
            'classes' => ['fa-erlang'],
            'content' => '\f39d'
        ],
        '\f39e' => [
            'class' => 'fab fa-facebook-f',
            'classes' => ['fa-facebook-f'],
            'content' => '\f39e',
            'text_de' => 'Logos: Facebook',
            'text_en' => 'Logos: Facebook'
        ],
        '\f39f' => [
            'class' => 'fab fa-facebook-messenger',
            'classes' => ['fa-facebook-messenger'],
            'content' => '\f39f',
            'text_de' => 'Logos: Facebook Messenger',
            'text_en' => 'Logos: Facebook Messenger'
        ],
        '\f3a1' => [
            'class' => 'fa fa-firstdraft',
            'classes' => ['fa-firstdraft'],
            'content' => '\f3a1'
        ],
        '\f3a2' => [
            'class' => 'fa fa-fonticons-fi',
            'classes' => ['fa-fonticons-fi'],
            'content' => '\f3a2'
        ],
        '\f3a3' => [
            'class' => 'fab fa-fort-awesome-alt',
            'classes' => ['fa-fort-awesome-alt'],
            'content' => '\f3a3'
        ],
        '\f3a4' => [
            'class' => 'fa fa-freebsd',
            'classes' => ['fa-freebsd'],
            'content' => '\f3a4'
        ],
        '\f3a5' => [
            'class' => 'fa fa-gem',
            'classes' => ['fa-gem'],
            'content' => '\f3a5',
            'text_de' => 'Juwel',
            'text_en' => 'Gem / Jewel'
        ],
        '\f3a6' => [
            'class' => 'fa fa-gitkraken',
            'classes' => ['fa-gitkraken'],
            'content' => '\f3a6'
        ],
        '\f3a7' => [
            'class' => 'fab fa-gofore',
            'classes' => ['fa-gofore'],
            'content' => '\f3a7'
        ],
        '\f3a8' => [
            'class' => 'fab fa-goodreads',
            'classes' => ['fa-goodreads'],
            'content' => '\f3a8',
            'text_de' => 'Logos: goodreads',
            'text_en' => 'Logos: goodreads'
        ],
        '\f3a9' => [
            'class' => 'fab fa-goodreads-g',
            'classes' => ['fa-goodreads-g'],
            'content' => '\f3a9',
            'text_de' => 'Logos: goodreads (G)',
            'text_en' => 'Logos: goodreads (G)'
        ],
        '\f3aa' => [
            'class' => 'fab fa-google-drive',
            'classes' => ['fa-google-drive'],
            'content' => '\f3aa',
            'text_de' => 'Logos: Google Drive',
            'text_en' => 'Logos: Google Drive'
        ],
        '\f3ab' => [
            'class' => 'fab fa-google-play',
            'classes' => ['fa-google-play'],
            'content' => '\f3ab',
            'text_de' => 'Logos: Google Play',
            'text_en' => 'Logos: Google Play'
        ],
        '\f3ac' => [
            'class' => 'fa fa-gripfire',
            'classes' => ['fa-gripfire'],
            'content' => '\f3ac'
        ],
        '\f3ad' => [
            'class' => 'fa fa-grunt',
            'classes' => ['fa-grunt'],
            'content' => '\f3ad'
        ],
        '\f3ae' => [
            'class' => 'fa fa-gulp',
            'classes' => ['fa-gulp'],
            'content' => '\f3ae'
        ],
        '\f3af' => [
            'class' => 'fa fa-hacker-news-square',
            'classes' => ['fa-hacker-news-square'],
            'content' => '\f3af',
        ],
        '\f3b0' => [
            'class' => 'fab fa-hire-a-helper',
            'classes' => ['fa-hire-a-helper'],
            'content' => '\f3b0',
            'text_de' => 'Logos: HireAHelper',
            'text_en' => 'Logos: HireAHelper'
        ],
        '\f3b1' => [
            'class' => 'fa fa-hotjar',
            'classes' => ['fa-hotjar'],
            'content' => '\f3b1'
        ],
        '\f3b2' => [
            'class' => 'fa fa-hubspot',
            'classes' => ['fa-hubspot'],
            'content' => '\f3b2'
        ],
        '\f3b4' => [
            'class' => 'fab fa-itunes',
            'classes' => ['fa-itunes'],
            'content' => '\f3b4',
            'text_de' => 'Logos: iTunes',
            'text_en' => 'Logos: iTunes'
        ],
        '\f3b5' => [
            'class' => 'fab fa-itunes-note',
            'classes' => ['fa-itunes-note'],
            'content' => '\f3b5',
            'text_de' => 'Logos: iTunes (Musiknote)',
            'text_en' => 'Logos: iTunes (Note)'
        ],
        '\f3b6' => [
            'class' => 'fa fa-jenkins',
            'classes' => ['fa-jenkins'],
            'content' => '\f3b6'
        ],
        '\f3b7' => [
            'class' => 'fa fa-joget',
            'classes' => ['fa-joget'],
            'content' => '\f3b7'
        ],
        '\f3b8' => [
            'class' => 'fa fa-js',
            'classes' => ['fa-js'],
            'content' => '\f3b8'
        ],
        '\f3b9' => [
            'class' => 'fab fa-js-square',
            'classes' => ['fa-js-square'],
            'content' => '\f3b9'
        ],
        '\f3ba' => [
            'class' => 'fa fa-keycdn',
            'classes' => ['fa-keycdn'],
            'content' => '\f3ba'
        ],
        '\f3bb' => [
            'class' => 'fab fa-kickstarter',
            'classes' => ['fa-kickstarter'],
            'content' => '\f3bb',
            'text_de' => 'Logos: KICKSTARTER',
            'text_en' => 'Logos: KICKSTARTER'
        ],
        '\f3bc' => [
            'class' => 'fab fa-kickstarter-k',
            'classes' => ['fa-kickstarter-k'],
            'content' => '\f3bc',
            'text_de' => 'Logos: KICKSTARTER (K)',
            'text_en' => 'Logos: KICKSTARTER (K)'
        ],
        '\f3bd' => [
            'class' => 'fa fa-laravel',
            'classes' => ['fa-laravel'],
            'content' => '\f3bd'
        ],
        '\f3be' => [
            'class' => 'fa fa-level-down-alt',
            'classes' => ['fa-level-down-alt'],
            'content' => '\f3be',
            'text_de' => 'Ebene / Drehung (unten)',
            'text_en' => 'Level / Turn (Down)'
        ],
        '\f3bf' => [
            'class' => 'fa fa-level-up-alt',
            'classes' => ['fa-level-up-alt'],
            'content' => '\f3bf',
            'text_de' => 'Ebene / Drehung (oben)',
            'text_en' => 'Level / Turn (Up)'
        ],
        '\f3c0' => [
            'class' => 'fab fa-line',
            'classes' => ['fa-line'],
            'content' => '\f3c0'
        ],
        '\f3c1' => [
            'class' => 'fa fa-lock-open',
            'classes' => ['fa-lock-open', 'icon-open'],
            'content' => '\f3c1',
            'text_de' => 'Offen / Schloss (offen)',
            'text_en' => 'Lock (Open) / Open'
        ],
        '\f3c3' => [
            'class' => 'fa fa-lyft',
            'classes' => ['fa-lyft'],
            'content' => '\f3c3'
        ],
        '\f3c4' => [
            'class' => 'fa fa-magento',
            'classes' => ['fa-magento'],
            'content' => '\f3c4'
        ],
        '\f3c5' => [
            'class' => 'fa fa-map-marker-alt',
            'classes' => ['fa-map-marker-alt', 'icon-location'],
            'content' => '\f3c5',
            'text_de' => 'Standort-Pin (gepunktet)',
            'text_en' => 'Location-Pin (Dotted)'
        ],
        '\f3c6' => [
            'class' => 'fab fa-medapps',
            'classes' => ['fa-medapps'],
            'content' => '\f3c6'
        ],
        '\f3c7' => [
            'class' => 'fab fa-medium-m',
            'classes' => ['fa-medium-m'],
            'content' => '\f3c7'
        ],
        '\f3c8' => [
            'class' => 'fa fa-medrt',
            'classes' => ['fa-medrt'],
            'content' => '\f3c8'
        ],
        '\f3c9' => [
            'class' => 'fa fa-microphone-alt',
            'classes' => ['fa-microphone-alt'],
            'content' => '\f3c9',
            'text_de' => 'Mikrofon (geschlitzt)',
            'text_en' => 'Microphone (Slotted)'
        ],
        '\f3ca' => [
            'class' => 'fab fa-microsoft',
            'classes' => ['fa-microsoft'],
            'content' => '\f3ca'
        ],
        '\f3cb' => [
            'class' => 'fab fa-mix',
            'classes' => ['fa-mix'],
            'content' => '\f3cb'
        ],
        '\f3cc' => [
            'class' => 'fab fa-mizuni',
            'classes' => ['fa-mizuni'],
            'content' => '\f3cc'
        ],
        '\f3cd' => [
            'class' => 'fa fa-mobile-alt',
            'classes' => ['fa-mobile-alt'],
            'content' => '\f3cd',
            'context' => self::DEVICES,
            'text_de' => 'Mobile',
            'text_en' => 'Mobile'
        ],
        '\f3d0' => [
            'class' => 'fab fa-monero',
            'classes' => ['fa-monero'],
            'content' => '\f3d0',
            'text_de' => 'Logos: MONERO',
            'text_en' => 'Logos: MONERO'
        ],
        '\f3d1' => [
            'class' => 'fa fa-money-bill-alt',
            'classes' => ['fa-money-bill-alt'],
            'content' => '\f3d1',
            'text_de' => 'Geldschein (1)',
            'text_en' => 'Bill (1)'
        ],
        '\f3d2' => [
            'class' => 'fab fa-napster',
            'classes' => ['fa-napster'],
            'content' => '\f3d2',
            'text_de' => 'Logos: Napster',
            'text_en' => 'Logos: Napster'
        ],
        '\f3d3' => [
            'class' => 'fab fa-node-js',
            'classes' => ['fa-node-js'],
            'content' => '\f3d3'
        ],
        '\f3d4' => [
            'class' => 'fab fa-npm',
            'classes' => ['fa-npm'],
            'content' => '\f3d4'
        ],
        '\f3d5' => [
            'class' => 'fab fa-ns8',
            'classes' => ['fa-ns8'],
            'content' => '\f3d5'
        ],
        '\f3d6' => [
            'class' => 'fa fa-nutritionix',
            'classes' => ['fa-nutritionix'],
            'content' => '\f3d6'
        ],
        '\f3d7' => [
            'class' => 'fab fa-page4',
            'classes' => ['fa-page4'],
            'content' => '\f3d7'
        ],
        '\f3d8' => [
            'class' => 'fab fa-palfed',
            'classes' => ['fa-palfed'],
            'content' => '\f3d8'
        ],
        '\f3d9' => [
            'class' => 'fab fa-patreon',
            'classes' => ['fa-patreon'],
            'content' => '\f3d9',
            'text_de' => 'Logos: Patreon',
            'text_en' => 'Logos: Patreon'
        ],
        '\f3da' => [
            'class' => 'fab fa-periscope',
            'classes' => ['fa-periscope'],
            'content' => '\f3da'
        ],
        '\f3db' => [
            'class' => 'fab fa-phabricator',
            'classes' => ['fa-phabricator'],
            'content' => '\f3db'
        ],
        '\f3dc' => [
            'class' => 'fab fa-phoenix-framework',
            'classes' => ['fa-phoenix-framework'],
            'content' => '\f3dc'
        ],
        '\f3dd' => [
            'class' => 'fa fa-phone-slash',
            'classes' => ['fa-phone-slash'],
            'content' => '\f3dd',
            'context' => self::DEVICES,
            'text_de' => 'Telefon (Schrägstrich)',
            'text_en' => 'Telephone (Slash)'
        ],
        '\f3df' => [
            'class' => 'fab fa-playstation',
            'classes' => ['fa-playstation'],
            'content' => '\f3df'
        ],
        '\f3e0' => [
            'class' => 'fa fa-portrait',
            'classes' => ['fa-portrait'],
            'content' => '\f3e0',
            'text_de' => 'Porträt',
            'text_en' => 'Portrait'
        ],
        '\f3e1' => [
            'class' => 'fab fa-pushed',
            'classes' => ['fa-pushed'],
            'content' => '\f3e1'
        ],
        '\f3e2' => [
            'class' => 'fab fa-python',
            'classes' => ['fa-python'],
            'content' => '\f3e2'
        ],
        '\f3e3' => [
            'class' => 'fab fa-red-river',
            'classes' => ['fa-red-river'],
            'content' => '\f3e3'
        ],
        '\f3e4' => [
            'class' => 'fab fa-wpressr',
            'classes' => ['fa-wpressr'],
            'content' => '\f3e4'
        ],
        '\f3e5' => [
            'class' => 'fa fa-reply',
            'classes' => ['fa-reply'],
            'content' => '\f3e5',
            'context' => self::MAIL_FUNCTIONS,
            'text_de' => 'Antworten',
            'text_en' => 'Reply'
        ],
        '\f3e6' => [
            'class' => 'fab fa-replyd',
            'classes' => ['fa-replyd'],
            'content' => '\f3e6'
        ],
        '\f3e7' => [
            'class' => 'fab fa-resolving',
            'classes' => ['fa-resolving'],
            'content' => '\f3e7'
        ],
        '\f3e8' => [
            'class' => 'fab fa-rocketchat',
            'classes' => ['fa-rocketchat'],
            'content' => '\f3e8',
            'text_de' => 'Logos: rocket.chat',
            'text_en' => 'Logos: rocket.chat'
        ],
        '\f3e9' => [
            'class' => 'fab fa-rockrms',
            'classes' => ['fa-rockrms'],
            'content' => '\f3e9'
        ],
        '\f3ea' => [
            'class' => 'fab fa-schlix',
            'classes' => ['fa-schlix'],
            'content' => '\f3ea'
        ],
        '\f3eb' => [
            'class' => 'fab fa-searchengin',
            'classes' => ['fa-searchengin'],
            'content' => '\f3eb'
        ],
        '\f3ec' => [
            'class' => 'fab fa-servicestack',
            'classes' => ['fa-servicestack'],
            'content' => '\f3ec'
        ],
        '\f3ed' => [
            'class' => 'fa fa-shield-alt',
            'classes' => ['fa-shield-alt', 'icon-shield', 'icon-shield-alt'],
            'content' => '\f3ed',
            'text_de' => 'Schild',
            'text_en' => 'Shield'
        ],
        '\f3ee' => [
            'class' => 'fab fa-sistrix',
            'classes' => ['fa-sistrix'],
            'content' => '\f3ee'
        ],
        '\f3ef' => [
            'class' => 'fab fa-slack-hash',
            'classes' => ['fa-slack-hash'],
            'content' => '\f3ef'
        ],
        '\f3f3' => [
            'class' => 'fab fa-speakap',
            'classes' => ['fa-speakap'],
            'content' => '\f3f3',
            'text_de' => 'Logos: speakap',
            'text_en' => 'Logos: speakap'
        ],
        '\f3f5' => [
            'class' => 'fab fa-staylinked',
            'classes' => ['fa-staylinked'],
            'content' => '\f3f5'
        ],
        '\f3f6' => [
            'class' => 'fab fa-steam-symbol',
            'classes' => ['fa-steam-symbol'],
            'content' => '\f3f6'
        ],
        '\f3f7' => [
            'class' => 'fab fa-sticker-mule',
            'classes' => ['fa-sticker-mule'],
            'content' => '\f3f7'
        ],
        '\f3f8' => [
            'class' => 'fab fa-studiovinari',
            'classes' => ['fa-studiovinari'],
            'content' => '\f3f8'
        ],
        '\f3f9' => [
            'class' => 'fab fa-supple',
            'classes' => ['fa-supple'],
            'content' => '\f3f9'
        ],
        '\f3fa' => [
            'class' => 'fa fa-tablet-alt',
            'classes' => ['fa-tablet-alt'],
            'content' => '\f3fa',
            'context' => self::DEVICES,
            'text_de' => 'Tablet',
            'text_en' => 'Tablet'
        ],
        '\f3fd' => [
            // deprecated => f624
            'class' => 'fa fa-tachometer-alt',
            'classes' => ['fa-tachometer-alt', 'icon-dashboard', 'icon-tachometer-alt'],
            'content' => '\f3fd',
            'text_de' => 'Anzeige / Armaturenbrett / Drehzahlmesser',
            'text_en' => 'Dashboard / Gauge / Tachometer'
        ],
        '\f3fe' => [
            'class' => 'fab fa-telegram-plane',
            'classes' => ['fa-telegram-plane'],
            'content' => '\f3fe'
        ],
        '\f3ff' => [
            'class' => 'fa fa-ticket-alt',
            'classes' => ['fa-ticket-alt'],
            'content' => '\f3ff',
            'context' => self::TRAVEL,
            'text_de' => 'Fahrkarte',
            'text_en' => 'Ticket'
        ],
        '\f402' => [
            'class' => 'fa fa-uber',
            'classes' => ['fa-uber'],
            'content' => '\f402'
        ],
        '\f403' => [
            'class' => 'fab fa-uikit',
            'classes' => ['fa-uikit'],
            'content' => '\f403'
        ],
        '\f404' => [
            'class' => 'fab fa-uniregistry',
            'classes' => ['fa-uniregistry'],
            'content' => '\f404'
        ],
        '\f405' => [
            'class' => 'fab fa-untappd',
            'classes' => ['fa-untappd'],
            'content' => '\f405'
        ],
        '\f406' => [
            'class' => 'fa fa-user-alt',
            'classes' => ['fa-user-alt'],
            'content' => '\f406',
            'text_de' => 'Personen: Person, groß',
            'text_en' => 'People: Person, Large'
        ],
        '\f407' => [
            'class' => 'fab fa-ussunnah',
            'classes' => ['fa-ussunnah'],
            'content' => '\f407'
        ],
        '\f408' => [
            'class' => 'fab fa-vaadin',
            'classes' => ['fa-vaadin'],
            'content' => '\f408'
        ],
        '\f409' => [
            'class' => 'fab fa-viber',
            'classes' => ['fa-viber'],
            'content' => '\f409',
            'text_de' => 'Logos: Viber',
            'text_en' => 'Logos: Viber'
        ],
        '\f40a' => [
            'class' => 'fab fa-vimeo',
            'classes' => ['fa-vimeo'],
            'content' => '\f40a',
            'text_de' => 'vimeo',
            'text_en' => 'vimeo'
        ],
        '\f40b' => [
            'class' => 'fab fa-vnv',
            'classes' => ['fa-vnv'],
            'content' => '\f40b'
        ],
        '\f40c' => [
            'class' => 'fab fa-whatsapp-square',
            'classes' => ['fa-whatsapp-square'],
            'content' => '\f40c',
            'text_de' => 'Logos: WhatsApp quadratisches Logo',
            'text_en' => 'Logos: WhatsApp Square Logo'
        ],
        '\f40d' => [
            'class' => 'fab fa-whmcs',
            'classes' => ['fa-whmcs'],
            'content' => '\f40d'
        ],
        '\f410' => [
            'class' => 'fa fa-window-close',
            'classes' => ['fa-window-close'],
            'content' => '\f410',
            'text_de' => 'Fenster (Schließen)',
            'text_en' => 'Window (Close)'
        ],
        '\f411' => [
            'class' => 'fab fa-wordpress-simple',
            'classes' => ['fa-wordpress-simple'],
            'content' => '\f411'
        ],
        '\f412' => [
            'class' => 'fa fa-xbox',
            'classes' => ['fa-xbox'],
            'content' => '\f412'
        ],
        '\f413' => [
            'class' => 'fab fa-yandex',
            'classes' => ['fa-yandex'],
            'content' => '\f413'
        ],
        '\f414' => [
            'class' => 'fab fa-yandex-international',
            'classes' => ['fa-yandex-international'],
            'content' => '\f414'
        ],
        '\f415' => [
            'class' => 'fab fa-apple-pay',
            'classes' => ['fa-apple-pay'],
            'content' => '\f415',
            'context' => self::FINANCE,
            'text_de' => 'Apple Pay',
            'text_en' => 'Apple Pay'
        ],
        '\f416' => [
            'class' => 'fab fa-cc-apple-pay',
            'classes' => ['fa-cc-apple-pay'],
            'content' => '\f416',
            'context' => self::FINANCE,
            'text_de' => 'Apple Pay, Kreditkarte',
            'text_en' => 'Apple Pay, Credit Card'
        ],
        '\f417' => [
            'class' => 'fab fa-fly',
            'classes' => ['fa-fly'],
            'content' => '\f417'
        ],
        '\f419' => [
            'class' => 'fab fa-node',
            'classes' => ['fa-node'],
            'content' => '\f419'
        ],
        '\f41a' => [
            'class' => 'fab fa-osi',
            'classes' => ['fa-osi'],
            'content' => '\f41a'
        ],
        '\f41b' => [
            'class' => 'fa fa-react',
            'classes' => ['fa-react'],
            'content' => '\f41b'
        ],
        '\f41c' => [
            'class' => 'fab fa-autoprefixer',
            'classes' => ['fa-autoprefixer'],
            'content' => '\f41c'
        ],
        '\f41d' => [
            'class' => 'fa fa-less',
            'classes' => ['fa-less'],
            'content' => '\f41d'
        ],
        '\f41e' => [
            'class' => 'fa fa-sass',
            'classes' => ['fa-sass'],
            'content' => '\f41e'
        ],
        '\f41f' => [
            'class' => 'fa fa-vuejs',
            'classes' => ['fa-vuejs'],
            'content' => '\f41f'
        ],
        '\f420' => [
            'class' => 'fa fa-angular',
            'classes' => ['fa-angular'],
            'content' => '\f420'
        ],
        '\f421' => [
            'class' => 'fab fa-aviato',
            'classes' => ['fa-aviato'],
            'content' => '\f421'
        ],
        '\f422' => [
            'class' => 'fa fa-compress-alt',
            'classes' => ['fa-compress-alt'],
            'content' => '\f422',
            'text_de' => 'Verdichten / Zusammenziehen (Pfeile, 2x)',
            'text_en' => 'Compress / Contract (Arrows, 2x)'
        ],
        '\f423' => [
            'class' => 'fab fa-ember',
            'classes' => ['fa-ember'],
            'content' => '\f423'
        ],
        '\f424' => [
            'class' => 'fa fa-expand-alt',
            'classes' => ['fa-expand-alt'],
            'content' => '\f424',
            'text_de' => 'Ausdehnen / Vergrößern (Pfeile, 2x)',
            'text_en' => 'Compress / Contract (Arrows, 2x)'
        ],
        '\f425' => [
            'class' => 'fab fa-font-awesome-flag',
            'classes' => ['fa-font-awesome-flag'],
            'content' => '\f425'
        ],
        '\f426' => [
            'class' => 'fab fa-gitter',
            'classes' => ['fa-gitter'],
            'content' => '\f426',
            'text_de' => 'Logos: GITTER',
            'text_en' => 'Logos: GITTER'
        ],
        '\f427' => [
            'class' => 'fab fa-hooli',
            'classes' => ['fa-hooli'],
            'content' => '\f427'
        ],
        '\f428' => [
            'class' => 'fab fa-strava',
            'classes' => ['fa-strava'],
            'content' => '\f428'
        ],
        '\f429' => [
            'class' => 'fab fa-stripe',
            'classes' => ['fa-stripe'],
            'content' => '\f429',
            'context' => self::FINANCE,
            'text_de' => 'Stripe',
            'text_en' => 'Stripe'
        ],
        '\f42a' => [
            'class' => 'fab fa-stripe-s',
            'classes' => ['fa-stripe-s'],
            'content' => '\f42a',
            'context' => self::FINANCE,
            'text_de' => 'Stripe, S-Logo)',
            'text_en' => 'Stripe, S-Logo)'
        ],
        '\f42b' => [
            'class' => 'fab fa-typo3',
            'classes' => ['fa-typo3'],
            'content' => '\f42b',
            // typo3.org cms => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f42c' => [
            'class' => 'fab fa-amazon-pay',
            'classes' => ['fa-amazon-pay'],
            'content' => '\f42c',
            'context' => self::FINANCE,
            'text_de' => 'Amazon Pay',
            'text_en' => 'Amazon Pay'
        ],
        '\f42d' => [
            'class' => 'fab fa-cc-amazon-pay',
            'classes' => ['fa-cc-amazon-pay'],
            'content' => '\f42d',
            'context' => self::FINANCE,
            'text_de' => 'Amazon Pay, Kreditkarte',
            'text_en' => 'Amazon Pay, Credit Card'
        ],
        '\f42e' => [
            'class' => 'fab fa-ethereum',
            'classes' => ['fa-ethereum'],
            'content' => '\f42e',
            'context' => self::FINANCE,
            'text_de' => 'Ethereum',
            'text_en' => 'Ethereum'
        ],
        '\f42f' => [
            'class' => 'fab fa-korvue',
            'classes' => ['fa-korvue'],
            'content' => '\f42f'
        ],
        '\f430' => [
            'class' => 'fab fa-elementor',
            'classes' => ['fa-elementor'],
            'content' => '\f430'
        ],
        '\f431' => [
            'class' => 'fab fa-youtube-square',
            'classes' => ['fa-youtube-square'],
            'content' => '\f431',
            'context' => self::MEDIA,
            'text_de' => 'Youtube, quadratisches Logo',
            'text_en' => 'Youtube, Square Logo'
        ],
        '\f433' => [
            'class' => 'fa fa-baseball-ball',
            'classes' => ['fa-baseball-ball'],
            'content' => '\f433',
            'context' => self::ACTIVITIES,
            'text_de' => 'Baseball',
            'text_en' => 'Baseball'
        ],
        '\f434' => [
            'class' => 'fa fa-basketball-ball',
            'classes' => ['fa-basketball-ball'],
            'content' => '\f434',
            'context' => self::ACTIVITIES,
            'text_de' => 'Basketball',
            'text_en' => 'Basketball'
        ],
        '\f436' => [
            'class' => 'fa fa-bowling-ball',
            'classes' => ['fa-bowling-ball'],
            'content' => '\f436',
            'context' => self::ACTIVITIES,
            'text_de' => 'Bowling Ball',
            'text_en' => 'Bowling Ball'
        ],
        '\f439' => [
            'class' => 'fa fa-chess',
            'classes' => ['fa-chess'],
            'content' => '\f439',
            'context' => self::ACTIVITIES,
            'text_de' => 'Schach',
            'text_en' => 'Chess'
        ],
        '\f43a' => [
            'class' => 'fa fa-chess-bishop',
            'classes' => ['fa-chess-bishop'],
            'content' => '\f43a',
            'context' => self::ACTIVITIES,
            'text_de' => 'Schach, Läufer',
            'text_en' => 'Chess, Bishop'
        ],
        '\f43c' => [
            'class' => 'fa fa-chess-board',
            'classes' => ['fa-chess-board'],
            'content' => '\f43c',
            'context' => self::ACTIVITIES,
            'text_de' => 'Schach, Schachbrett',
            'text_en' => 'Chess, Chess Board'
        ],
        '\f43f' => [
            'class' => 'fa fa-chess-king',
            'classes' => ['fa-chess-king'],
            'content' => '\f43f',
            'context' => self::ACTIVITIES,
            'text_de' => 'Schach, König',
            'text_en' => 'Chess, King'
        ],
        '\f441' => [
            'class' => 'fa fa-chess-knight',
            'classes' => ['fa-chess-knight'],
            'content' => '\f441',
            'context' => self::ACTIVITIES,
            'text_de' => 'Schach, Springer',
            'text_en' => 'Chess, Knight'
        ],
        '\f443' => [
            'class' => 'fa fa-chess-pawn',
            'classes' => ['fa-chess-pawn'],
            'content' => '\f443',
            'context' => self::ACTIVITIES,
            'text_de' => 'Schach, Bauer',
            'text_en' => 'Chess, Pawn'
        ],
        '\f445' => [
            'class' => 'fa fa-chess-queen',
            'classes' => ['fa-chess-queen'],
            'content' => '\f445',
            'context' => self::ACTIVITIES,
            'text_de' => 'Schach, Königin',
            'text_en' => 'Chess, Queen'
        ],
        '\f447' => [
            'class' => 'fa fa-chess-rook',
            'classes' => ['fa-chess-rook'],
            'content' => '\f447',
            'context' => self::ACTIVITIES,
            'text_de' => 'Schach, Turm',
            'text_en' => 'Chess, Rook'
        ],
        '\f44b' => [
            'class' => 'fa fa-dumbbell',
            'classes' => ['fa-dumbbell'],
            'content' => '\f44b',
            'context' => self::ACTIVITIES,
            'text_de' => 'Hantel',
            'text_en' => 'Dumbbell'
        ],
        '\f44d' => [
            'class' => 'fab fa-flipboard',
            'classes' => ['fa-flipboard'],
            'content' => '\f44d'
        ],
        '\f44e' => [
            'class' => 'fa fa-football-ball',
            'classes' => ['fa-football-ball'],
            'content' => '\f44e',
            'context' => self::ACTIVITIES,
            'text_de' => 'Football, American',
            'text_en' => 'Football, American'
        ],
        '\f450' => [
            'class' => 'fa fa-golf-ball',
            'classes' => ['fa-golf-ball'],
            'content' => '\f450',
            'context' => self::ACTIVITIES,
            'text_de' => 'Golf',
            'text_en' => 'Golf'
        ],
        '\f452' => [
            'class' => 'fab fa-hips',
            'classes' => ['fa-hips'],
            'content' => '\f452'
        ],
        '\f453' => [
            'class' => 'fa fa-hockey-puck',
            'classes' => ['fa-hockey-puck'],
            'content' => '\f453',
            'context' => self::ACTIVITIES,
            'text_de' => 'Hockey-Puck',
            'text_en' => 'Hockey Puck'
        ],
        '\f457' => [
            'class' => 'fa fa-php',
            'classes' => ['fa-php'],
            'content' => '\f457'
        ],
        '\f458' => [
            'class' => 'fa fa-quidditch',
            'classes' => ['fa-quidditch'],
            'content' => '\f458',
            'context' => self::ACTIVITIES,
            'text_de' => 'Quidditch',
            'text_en' => 'Quidditch'
        ],
        '\f459' => [
            'class' => 'fab fa-quinscape',
            'classes' => ['fa-quinscape'],
            'content' => '\f459'
        ],
        '\f45c' => [
            'class' => 'fa fa-square-full',
            'classes' => ['fa-square-full'],
            'content' => '\f45c',
            'text_de' => 'Quadrat (gefüllt)',
            'text_en' => 'Square (Filled)'
        ],
        '\f45d' => [
            'class' => 'fa fa-table-tennis',
            'classes' => ['fa-table-tennis'],
            'content' => '\f45d',
            'context' => self::ACTIVITIES,
            'text_de' => 'Tischtennis',
            'text_en' => 'Table Tennis'
        ],
        '\f45f' => [
            'class' => 'fa fa-volleyball-ball',
            'classes' => ['fa-volleyball-ball'],
            'content' => '\f45f',
            'context' => self::ACTIVITIES,
            'text_de' => 'Volleyball',
            'text_en' => 'Volleyball'
        ],
        '\f461' => [
            'class' => 'fa fa-allergies',
            'classes' => ['fa-allergies'],
            'content' => '\f461',
            'text_de' => 'Hand (ungewaschen)',
            'text_en' => 'Hand (Unwashed)'
        ],
        '\f462' => [
            'class' => 'fa fa-band-aid',
            'classes' => ['fa-band-aid'],
            'content' => '\f462',
            'text_de' => 'Pflaster',
            'text_en' => 'Band-Aid'
        ],
        '\f466' => [
            'class' => 'fa fa-box',
            'classes' => ['fa-box'],
            'content' => '\f466',
            'text_de' => 'Box',
            'text_en' => 'Box'
        ],
        '\f468' => [
            'class' => 'fa fa-boxes',
            'classes' => ['fa-boxes'],
            'content' => '\f468',
            'text_de' => 'Boxen',
            'text_en' => 'Boxes'
        ],
        '\f469' => [
            'class' => 'fa fa-briefcase-medical',
            'classes' => ['fa-briefcase-medical'],
            'content' => '\f469',
            'text_de' => 'Aktentasche (medizinisch)',
            'text_en' => 'Briefcase (Medical)'
        ],
        '\f46a' => [
            'class' => 'fa fa-burn',
            'classes' => ['fa-burn'],
            'content' => '\f46a',
            'text_de' => 'Flamme (Brenner-)',
            'text_en' => 'Flame (Burner)'
        ],
        '\f46b' => [
            'class' => 'fa fa-capsules',
            'classes' => ['fa-capsules'],
            'content' => '\f46b',
            'text_de' => 'Kapseln',
            'text_en' => 'Capsules'
        ],
        '\f46c' => [
            'class' => 'fa fa-clipboard-check',
            'classes' => ['fa-clipboard-check'],
            'content' => '\f46c',
            'text_de' => 'Funktionsgegenstände: Klemmbrett mit Häkchen',
            'text_en' => 'Functional Items: Clipboard with Checkmark'
        ],
        '\f46d' => [
            'class' => 'fa fa-clipboard-list',
            'classes' => ['fa-clipboard-list'],
            'content' => '\f46d',
            'text_de' => 'Funktionsgegenstände: Klemmbrett mit Liste',
            'text_en' => 'Functional Items: Clipboard with List'
        ],
        '\f470' => [
            'class' => 'fa fa-diagnoses',
            'classes' => ['fa-diagnoses'],
            'content' => '\f470',
            'context' => self::MEDICAL,
            'text_de' => 'Diagnosen',
            'text_en' => 'Diagnoses'
        ],
        '\f471' => [
            'class' => 'fa fa-dna',
            'classes' => ['fa-dna'],
            'content' => '\f471',
            'context' => self::SCIENCE,
            'text_de' => 'DNS',
            'text_en' => 'DNA'
        ],
        '\f472' => [
            'class' => 'fa fa-dolly',
            'classes' => ['fa-dolly'],
            'content' => '\f472',
            'text_de' => 'Funktionsgegenstände: Dolly, Karren',
            'text_en' => 'Functional Items: Dolly, Delivery'
        ],
        '\f474' => [
            'class' => 'fa fa-dolly-flatbed',
            'classes' => ['fa-dolly-flatbed'],
            'content' => '\f474',
            'text_de' => 'Funktionsgegenstände: Dolly, Wagen',
            'text_en' => 'Functional Items: Dolly, Flatbed'
        ],
        '\f477' => [
            'class' => 'fa fa-file-medical',
            'classes' => ['fa-file-medical'],
            'content' => '\f477',
            'text_de' => 'Informatik: Datei, medizinisch',
            'text_en' => 'Computing: File, Medical'
        ],
        '\f478' => [
            'class' => 'fa fa-file-medical-alt',
            'classes' => ['fa-file-medical-alt'],
            'content' => '\f478',
            'text_de' => 'Informatik: Datei, medizinisch, Puls-Symbol',
            'text_en' => 'Computing: File, Medical, Pulse Symbol'
        ],
        '\f479' => [
            'class' => 'fa fa-first-aid',
            'classes' => ['fa-first-aid'],
            'content' => '\f479',
            'context' => self::MEDICAL,
            'text_de' => 'Erste-Hilfe-Kasten',
            'text_en' => 'First Aid Kit'
        ],
        '\f47d' => [
            'class' => 'fa fa-hospital-alt',
            'classes' => ['fa-hospital-alt'],
            'content' => '\f47d'
        ],
        '\f47e' => [
            'class' => 'fa fa-hospital-symbol',
            'classes' => ['fa-hospital-symbol'],
            'content' => '\f47e',
            'text_de' => 'Symbole: H im Kreis',
            'text_en' => 'Symbols: H on a Circle'
        ],
        '\f47f' => [
            'class' => 'fa fa-id-card-alt',
            'classes' => ['fa-id-card-alt'],
            'content' => '\f47f',
            'text_de' => 'Identifikationsschild',
            'text_en' => 'ID-Badge'
        ],
        '\f481' => [
            'class' => 'fa fa-notes-medical',
            'classes' => ['fa-notes-medical'],
            'content' => '\f481',
            'text_de' => 'Notizen (medizinisch)',
            'text_en' => 'Notes (Medical)'
        ],
        '\f482' => [
            'class' => 'fa fa-pallet',
            'classes' => ['fa-pallet'],
            'content' => '\f482',
            'text_de' => 'Pallet',
            'text_en' => 'Pallet'
        ],
        '\f484' => [
            'class' => 'fa fa-pills',
            'classes' => ['fa-pills'],
            'content' => '\f484',
            'text_de' => 'Pillen',
            'text_en' => 'Pills'
        ],
        '\f485' => [
            'class' => 'fa fa-prescription-bottle',
            'classes' => ['fa-prescription-bottle'],
            'content' => '\f485',
            'text_de' => 'Verschreibung (Flasche)',
            'text_en' => 'Prescription (Bottle)'
        ],
        '\f486' => [
            'class' => 'fa fa-prescription-bottle-alt',
            'classes' => ['fa-prescription-bottle-alt'],
            'content' => '\f486',
            'text_de' => 'Verschreibung (Flasche, Plus)',
            'text_en' => 'Prescription (Bottle, Plus)'
        ],
        '\f487' => [
            'class' => 'fa fa-procedures',
            'classes' => ['fa-procedures'],
            'content' => '\f487',
            'text_de' => 'Bett & Puls / Patientenakte',
            'text_en' => 'Bed & Pulse / Patient Record'
        ],
        '\f48b' => [
            'class' => 'fa fa-shipping-fast',
            'classes' => ['fa-shipping-fast'],
            'content' => '\f48b',
            'text_de' => 'Expresszustellung',
            'text_en' => 'Express Delivery'
        ],
        '\f48d' => [
            'class' => 'fa fa-smoking',
            'classes' => ['fa-smoking'],
            'content' => '\f48d',
            'text_de' => 'Rauchen',
            'text_en' => 'Smoking'
        ],
        '\f48e' => [
            'class' => 'fa fa-syringe',
            'classes' => ['fa-syringe'],
            'content' => '\f48e',
            'text_de' => 'Spritze',
            'text_en' => 'Syringe'
        ],
        '\f490' => [
            'class' => 'fa fa-tablets',
            'classes' => ['fa-tablets'],
            'content' => '\f490',
            'text_de' => 'Tabletten',
            'text_en' => 'Tablets'
        ],
        '\f491' => [
            'class' => 'fa fa-thermometer',
            'classes' => ['fa-thermometer'],
            'content' => '\f491',
            'text_de' => 'Corndog /Thermometer',
            'text_en' => 'Corndog / Thermometer'
        ],
        '\f492' => [
            'class' => 'fa fa-vial',
            'classes' => ['fa-vial'],
            'content' => '\f492',
            'text_de' => 'Röhrchen',
            'text_en' => 'Vial'
        ],
        '\f493' => [
            'class' => 'fa fa-vials',
            'classes' => ['fa-vials'],
            'content' => '\f493',
            'text_de' => 'Röhrchen (mehrzahl)',
            'text_en' => 'Vials'
        ],
        '\f494' => [
            'class' => 'fa fa-warehouse',
            'classes' => ['fa-warehouse'],
            'content' => '\f494',
            'text_de' => 'Lagerhalle',
            'text_en' => 'Warehouse'
        ],
        '\f496' => [
            'class' => 'fa fa-weight',
            'classes' => ['fa-weight'],
            'content' => '\f496',
            'text_de' => 'Wiege',
            'text_en' => 'Scale'
        ],
        '\f497' => [
            'class' => 'fa fa-x-ray',
            'classes' => ['fa-x-ray'],
            'content' => '\f497',
            'text_de' => 'Röntgenbild',
            'text_en' => 'X-Ray Image'
        ],
        '\f49e' => [
            'class' => 'fa fa-box-open',
            'classes' => ['fa-box-open'],
            'content' => '\f49e',
            'text_de' => 'Box (offen)',
            'text_en' => 'Box (Open)'
        ],
        '\f4ad' => [
            'class' => 'fa fa-comment-dots',
            'classes' => ['fa-comment-dots', 'icon-comment-dots'],
            'content' => '\f4ad',
            'text_de' => 'Kommentar / Sprechblase (Punkte)',
            'text_en' => 'Comment / Speech Bubble (Dots)'
        ],
        '\f4b3' => [
            'class' => 'fa fa-comment-slash',
            'classes' => ['fa-comment-slash'],
            'content' => '\f4b3',
            'text_de' => 'Kommentar / Sprechblase (Schrägstrich)',
            'text_en' => 'Comment / Speech Bubble (Slash)'
        ],
        '\f4b8' => [
            'class' => 'fa fa-couch',
            'classes' => ['fa-couch'],
            'content' => '\f4b8',
            'text_de' => 'Couch / Sofa / Zweisitzer',
            'text_en' => 'Couch / Loveseat / Sofa'
        ],
        '\f4b9' => [
            'class' => 'fa fa-donate',
            'classes' => ['fa-donate'],
            'content' => '\f4b9',
            'text_de' => 'Spende',
            'text_en' => 'Donation'
        ],
        '\f4ba' => [
            'class' => 'fa fa-dove',
            'classes' => ['fa-dove'],
            'content' => '\f4ba',
            'text_de' => 'Taube',
            'text_en' => 'Dove'
        ],
        '\f4bd' => [
            'class' => 'fa fa-hand-holding',
            'classes' => ['fa-hand-holding'],
            'content' => '\f4bd',
            'text_de' => 'vorgehaltener Hand',
            'text_en' => 'Cupping Hand'
        ],
        '\f4be' => [
            'class' => 'fa fa-hand-holding-heart',
            'classes' => ['fa-hand-holding-heart'],
            'content' => '\f4be',
            'context' => self::MEDICAL,
            'text_de' => 'vorgehaltener Hand mit Herz',
            'text_en' => 'Cupping Hand with Heart'
        ],
        '\f4c0' => [
            'class' => 'fa fa-hand-holding-usd',
            'classes' => ['fa-hand-holding-usd'],
            'content' => '\f4c0',
            'context' => self::FINANCE,
            'text_de' => 'vorgehaltener Hand mit Dollar',
            'text_en' => 'Cupping Hand with Dollar'
        ],
        '\f4c1' => [
            'class' => 'fa fa-hand-holding-water',
            'classes' => ['fa-hand-holding-water'],
            'content' => '\f4c1',
            'context' => self::MEDICAL,
            'text_de' => 'vorgehaltener Hand mit Tropfen',
            'text_en' => 'Cupping Hand with Droplet'
        ],
        '\f4c2' => [
            'class' => 'fa fa-hands',
            'classes' => ['fa-hands'],
            'content' => '\f4c2',
            'text_de' => 'Hände (schröpfend)',
            'text_en' => 'Hands (Cupping)'
        ],
        '\f4c4' => [
            'class' => 'fa fa-hands-helping',
            'classes' => ['fa-hands-helping'],
            'content' => '\f4c4',
            'text_de' => 'Hände (schüttelnd, geneigt)',
            'text_en' => 'Hands (Shaking, Inclined)'
        ],
        '\f4cd' => [
            'class' => 'fa fa-parachute-box',
            'classes' => ['fa-parachute-box'],
            'content' => '\f4cd',
            'text_de' => 'Luftabwurf',
            'text_en' => 'Airdrop'
        ],
        '\f4ce' => [
            'class' => 'fa fa-people-carry',
            'classes' => ['fa-people-carry'],
            'content' => '\f4ce',
            'text_de' => 'Tragen / Umziehen',
            'text_en' => 'Carry / Move'
        ],
        '\f4d3' => [
            'class' => 'fa fa-piggy-bank',
            'classes' => ['fa-piggy-bank'],
            'content' => '\f4d3',
            'text_de' => 'Sparschwein',
            'text_en' => 'Piggy Bank'
        ],
        '\f4d5' => [
            'class' => 'fab fa-readme',
            'classes' => ['fa-readme'],
            'content' => '\f4d5'
        ],
        '\f4d6' => [
            'class' => 'fa fa-ribbon',
            'classes' => ['fa-ribbon'],
            'content' => '\f4d6',
            'text_de' => 'Schleife',
            'text_en' => 'Ribbon'
        ],
        '\f4d7' => [
            'class' => 'fa fa-route',
            'classes' => ['fa-route'],
            'content' => '\f4d7',
            'text_de' => 'Route',
            'text_en' => 'Route'
        ],
        '\f4d8' => [
            'class' => 'fa fa-seedling',
            'classes' => ['fa-seedling'],
            'content' => '\f4d8',
            'text_de' => 'Sämling',
            'text_en' => 'Seedling'
        ],
        '\f4d9' => [
            'class' => 'fa fa-sign',
            'classes' => ['fa-sign'],
            'content' => '\f4d9',
            'text_de' => 'Hängeschild',
            'text_en' => 'Sign (Hanging)'
        ],
        '\f4da' => [
            'class' => 'fa fa-smile-wink',
            'classes' => ['fa-smile-wink'],
            'content' => '\f4da',
            'text_de' => 'Winken (Lächeln)',
            'text_en' => 'Winking (Smiling)'
        ],
        '\f4db' => [
            'class' => 'fa fa-tape',
            'classes' => ['fa-tape'],
            'content' => '\f4db',
            'text_de' => 'Klebeband',
            'text_en' => 'Tape'
        ],
        '\f4de' => [
            'class' => 'fa fa-truck-loading',
            'classes' => ['fa-truck-loading'],
            'content' => '\f4de',
            'text_de' => 'Laderampe',
            'text_en' => 'Loading Ramp'
        ],
        '\f4df' => [
            'class' => 'fa fa-truck-moving',
            'classes' => ['fa-truck-moving'],
            'content' => '\f4df',
            'text_de' => 'Lastwagen (groß)',
            'text_en' => 'Truck (Semi)'
        ],
        '\f4e2' => [
            'class' => 'fa fa-video-slash',
            'classes' => ['fa-video-slash'],
            'content' => '\f4e2',
            'text_de' => 'Video (Schrägstrich)',
            'text_en' => 'Video (Slash)'
        ],
        '\f4e3' => [
            'class' => 'fa fa-wine-glass',
            'classes' => ['fa-wine-glass'],
            'content' => '\f4e3',
            'text_de' => 'Weinglas (halbvoll)',
            'text_en' => 'Wine Glass (Half Full)'
        ],
        '\f4e4' => [
            'class' => 'fab fa-java',
            'classes' => ['fa-java'],
            'content' => '\f4e4'
        ],
        '\f4e5' => [
            'class' => 'fab fa-pied-piper-hat',
            'classes' => ['fa-pied-piper-hat'],
            'content' => '\f4e5',
            // piedpipergroup.com: consulting => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f4e6' => [
            'class' => 'fab fa-font-awesome-logo-full',
            'classes' => ['fa-font-awesome-logo-full'],
            'content' => '\f4e6'
        ],
        '\f4e7' => [
            'class' => 'fab fa-creative-commons-by',
            'classes' => ['fa-creative-commons-by'],
            'content' => '\f4e7',
            'text_de' => 'Logos: Creative Commons (Autor)',
            'text_en' => 'Logos: Creative Commons (Author)'
        ],
        '\f4e8' => [
            'class' => 'fab fa-creative-commons-nc',
            'classes' => ['fa-creative-commons-nc'],
            'content' => '\f4e8'
        ],
        '\f4e9' => [
            'class' => 'fab fa-creative-commons-nc-eu',
            'classes' => ['fa-creative-commons-nc-eu'],
            'content' => '\f4e9'
        ],
        '\f4ea' => [
            'class' => 'fab fa-creative-commons-nc-jp',
            'classes' => ['fa-creative-commons-nc-jp'],
            'content' => '\f4ea'
        ],
        '\f4eb' => [
            'class' => 'fab fa-creative-commons-nd',
            'classes' => ['fa-creative-commons-nd'],
            'content' => '\f4eb'
        ],
        '\f4ec' => [
            'class' => 'fab fa-creative-commons-pd',
            'classes' => ['fa-creative-commons-pd'],
            'content' => '\f4ec'
        ],
        '\f4ed' => [
            'class' => 'fab fa-creative-commons-pd-alt',
            'classes' => ['fa-creative-commons-pd-alt'],
            'content' => '\f4ed'
        ],
        '\f4ee' => [
            'class' => 'fab fa-creative-commons-remix',
            'classes' => ['fa-creative-commons-remix'],
            'content' => '\f4ee'
        ],
        '\f4ef' => [
            'class' => 'fab fa-creative-commons-sa',
            'classes' => ['fa-creative-commons-sa'],
            'content' => '\f4ef'
        ],
        '\f4f0' => [
            'class' => 'fab fa-creative-commons-sampling',
            'classes' => ['fa-creative-commons-sampling'],
            'content' => '\f4f0'
        ],
        '\f4f1' => [
            'class' => 'fab fa-creative-commons-sampling-plus',
            'classes' => ['fa-creative-commons-sampling-plus'],
            'content' => '\f4f1'
        ],
        '\f4f2' => [
            'class' => 'fab fa-creative-commons-share',
            'classes' => ['fa-creative-commons-share'],
            'content' => '\f4f2'
        ],
        '\f4f3' => [
            'class' => 'fab fa-creative-commons-zero',
            'classes' => ['fa-creative-commons-zero'],
            'content' => '\f4f3'
        ],
        '\f4f4' => [
            'class' => 'fab fa-ebay',
            'classes' => ['fa-ebay'],
            'content' => '\f4f4'
        ],
        '\f4f5' => [
            'class' => 'fab fa-keybase',
            'classes' => ['fa-keybase'],
            'content' => '\f4f5',
            'text_de' => 'Logos: Keybase',
            'text_en' => 'Logos: Keybase'
        ],
        '\f4f6' => [
            'class' => 'fab fa-mastodon',
            'classes' => ['fa-mastodon'],
            'content' => '\f4f6',
            'text_de' => 'Logos: mastodon',
            'text_en' => 'Logos: mastodon'
        ],
        '\f4f7' => [
            'class' => 'fab fa-r-project',
            'classes' => ['fa-r-project'],
            'content' => '\f4f7'
        ],
        '\f4f8' => [
            'class' => 'fab fa-researchgate',
            'classes' => ['fa-researchgate'],
            'content' => '\f4f8',
            'text_de' => 'Logos: ResearchGate',
            'text_en' => 'Logos: ResearchGate'
        ],
        '\f4f9' => [
            'class' => 'fab fa-teamspeak',
            'classes' => ['fa-teamspeak'],
            'content' => '\f4f9',
            'text_de' => 'Logos: TeamSpeak',
            'text_en' => 'Logos: TeamSpeak'
        ],
        '\f4fa' => [
            'class' => 'fa fa-user-alt-slash',
            'classes' => ['fa-user-alt-slash'],
            'content' => '\f4fa',
            'text_de' => 'Personen: Person, groß, Schrägstrich',
            'text_en' => 'People: Person, Large, Slash'
        ],
        '\f4fb' => [
            'class' => 'fa fa-user-astronaut',
            'classes' => ['fa-user-astronaut'],
            'content' => '\f4fb',
            'text_de' => 'Personen: Astronaut:in',
            'text_en' => 'People: Astronaut'
        ],
        '\f4fc' => [
            'class' => 'fa fa-user-check',
            'classes' => ['fa-user-check'],
            'content' => '\f4fc',
            'text_de' => 'Personen: Person mit Häkchen',
            'text_en' => 'People: Person with Checkmark'
        ],
        '\f4fd' => [
            'class' => 'fa fa-user-clock',
            'classes' => ['fa-user-clock'],
            'content' => '\f4fd',
            'text_de' => 'Personen: Person mit Uhr',
            'text_en' => 'People: Person with Clock'
        ],
        '\f4fe' => [
            'class' => 'fa fa-user-cog',
            'classes' => ['fa-user-cog'],
            'content' => '\f4fe',
            'text_de' => 'Personen: Person mit Zahnrad',
            'text_en' => 'People: Person with Gear'
        ],
        '\f4ff' => [
            'class' => 'fa fa-user-edit',
            'classes' => ['fa-user-edit', 'icon-user-edit'],
            'content' => '\f4ff',
            'text_de' => 'Personen: Person mit Stift',
            'text_en' => 'People: Person with Pencil'
        ],
        '\f500' => [
            'class' => 'fa fa-user-friends',
            'classes' => ['fa-user-friends'],
            'content' => '\f500',
            'text_de' => 'Personen: Gruppe',
            'text_en' => 'People: Group'
        ],
        '\f501' => [
            'class' => 'fa fa-user-graduate',
            'classes' => ['fa-user-graduate'],
            'content' => '\f501',
            'text_de' => 'Personen: Absolvent:in',
            'text_en' => 'People: Graduate'
        ],
        '\f502' => [
            'class' => 'fa fa-user-lock',
            'classes' => ['fa-user-lock', 'icon-user-lock'],
            'content' => '\f502',
            'text_de' => 'Personen: Person mit Schloss',
            'text_en' => 'People: Person with Lock'
        ],
        '\f503' => [
            'class' => 'fa fa-user-minus',
            'classes' => ['fa-user-minus'],
            'content' => '\f503',
            'text_de' => 'Personen: Person mit Minus',
            'text_en' => 'People: Person with Minus'
        ],
        '\f504' => [
            'class' => 'fa fa-user-ninja',
            'classes' => ['fa-user-ninja'],
            'content' => '\f504',
            'text_de' => 'Ninja',
            'text_en' => 'Ninja'
        ],
        '\f505' => [
            'class' => 'fa fa-user-shield',
            'classes' => ['fa-user-shield'],
            'content' => '\f505',
            'text_de' => 'Person (Schild)',
            'text_en' => 'User (Shield)'
        ],
        '\f506' => [
            'class' => 'fa fa-user-slash',
            'classes' => ['fa-user-slash'],
            'content' => '\f506',
            'text_de' => 'Person (Schrägstrich)',
            'text_en' => 'User (Slash)'
        ],
        '\f507' => [
            'class' => 'fa fa-user-tag',
            'classes' => ['fa-user-tag', 'icon-user-tag'],
            'content' => '\f507',
            'text_de' => 'Person (Etikett / Schlagwort)',
            'text_en' => 'User (Keyword / Tag)'
        ],
        '\f508' => [
            'class' => 'fa fa-user-tie',
            'classes' => ['fa-user-tie'],
            'content' => '\f508',
            'text_de' => 'Person (Krawatte)',
            'text_en' => 'User (Tie)'
        ],
        '\f509' => [
            'class' => 'fa fa-users-cog',
            'classes' => ['fa-users-cog', 'icon-users-cog'],
            'content' => '\f509',
            'text_de' => 'Personen: Personen mit Zahnrad',
            'text_en' => 'People: People with Gear'
        ],
        '\f50a' => [
            'class' => 'fa fa-first-order-alt',
            'classes' => ['fa-first-order-alt'],
            'content' => '\f50a',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f50b' => [
            'class' => 'fab fa-fulcrum',
            'classes' => ['fa-fulcrum'],
            'content' => '\f50b',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f50c' => [
            'class' => 'fa fa-galactic-republic',
            'classes' => ['fa-galactic-republic'],
            'content' => '\f50c',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f50d' => [
            'class' => 'fa fa-galactic-senate',
            'classes' => ['fa-galactic-senate'],
            'content' => '\f50d',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f50e' => [
            'class' => 'fa fa-jedi-order',
            'classes' => ['fa-jedi-order'],
            'content' => '\f50e',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f50f' => [
            'class' => 'fa fa-mandalorian',
            'classes' => ['fa-mandalorian'],
            'content' => '\f50f',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f510' => [
            'class' => 'fa fa-old-republic',
            'classes' => ['fa-old-republic'],
            'content' => '\f510',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f511' => [
            'class' => 'fa fa-phoenix-squadron',
            'classes' => ['fa-phoenix-squadron'],
            'content' => '\f511',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f512' => [
            'class' => 'fa fa-sith',
            'classes' => ['fa-sith'],
            'content' => '\f512',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f513' => [
            'class' => 'fa fa-trade-federation',
            'classes' => ['fa-trade-federation'],
            'content' => '\f513',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f514' => [
            'class' => 'fa fa-wolf-pack-battalion',
            'classes' => ['fa-wolf-pack-battalion'],
            'content' => '\f514',
            // star wars => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f515' => [
            'class' => 'fa fa-balance-scale-left',
            'classes' => ['fa-balance-scale-left'],
            'content' => '\f515',
            'context' => self::SECONDARY,
            'text_de' => 'Waage, links-geneigt',
            'text_en' => 'Scales, Left-Leaning'
        ],
        '\f516' => [
            'class' => 'fa fa-balance-scale-right',
            'classes' => ['fa-balance-scale-right'],
            'content' => '\f516',
            'context' => self::SECONDARY,
            'text_de' => 'Waage, rechts-geneigt',
            'text_en' => 'Scales, Right-Leaning'
        ],
        '\f517' => [
            'class' => 'fa fa-blender',
            'classes' => ['fa-blender'],
            'content' => '\f517',
            'context' => self::SECONDARY,
            'text_de' => 'Mixer',
            'text_en' => 'Blender'
        ],
        '\f518' => [
            'class' => 'fa fa-book-open',
            'classes' => ['fa-book-open'],
            'content' => '\f518',
            'context' => self::PRIMARY,
            'text_de' => 'Buch, offen',
            'text_en' => 'Book, Open'
        ],
        '\f519' => [
            'class' => 'fa fa-broadcast-tower',
            'classes' => ['fa-broadcast-tower'],
            'content' => '\f519',
            'context' => self::SECONDARY,
            'text_de' => 'Rundfunkturm',
            'text_en' => 'Broadcast Tower'
        ],
        '\f51a' => [
            'class' => 'fa fa-broom',
            'classes' => ['fa-broom'],
            'content' => '\f51a',
            'context' => self::SECONDARY,
            'text_de' => 'Besen',
            'text_en' => 'Broom'
        ],
        '\f51b' => [
            'class' => 'fa fa-chalkboard',
            'classes' => ['fa-chalkboard'],
            'content' => '\f51b',
            'context' => self::PRIMARY,
            'text_de' => 'Tafel',
            'text_en' => 'Chalkboard'
        ],
        '\f51c' => [
            'class' => 'fa fa-chalkboard-teacher',
            'classes' => ['fa-chalkboard-teacher'],
            'content' => '\f51c',
            'context' => self::PRIMARY,
            'text_de' => 'Person & Tafel',
            'text_en' => 'Person & Chalkboard'
        ],
        '\f51d' => [
            'class' => 'fa fa-church',
            'classes' => ['fa-church'],
            'content' => '\f51d',
            'text_de' => 'Kirche',
            'text_en' => 'Church'
        ],
        '\f51e' => [
            'class' => 'fa fa-coins',
            'classes' => ['fa-coins'],
            'content' => '\f51e',
            'context' => self::SECONDARY,
            'text_de' => 'Münzen',
            'text_en' => 'Coins'
        ],
        '\f51f' => [
            'class' => 'fa fa-compact-disc',
            'classes' => ['fa-compact-disc'],
            'content' => '\f51f',
            'context' => self::SECONDARY,
            'text_de' => 'CD',
            'text_en' => 'CD'
        ],
        '\f520' => [
            'class' => 'fa fa-crow',
            'classes' => ['fa-crow'],
            'content' => '\f520',
            'text_de' => 'Krähe',
            'text_en' => 'Crow'
        ],
        '\f521' => [
            'class' => 'fa fa-crown',
            'classes' => ['fa-crown'],
            'content' => '\f521',
            'text_de' => 'Krone',
            'text_en' => 'Crown'
        ],
        '\f522' => [
            'class' => 'fa fa-dice',
            'classes' => ['fa-dice'],
            'content' => '\f522',
            'context' => self::ACTIVITIES,
            'text_de' => 'Würfel',
            'text_en' => 'Dice'
        ],
        '\f523' => [
            'class' => 'fa fa-dice-five',
            'classes' => ['fa-dice-five'],
            'content' => '\f523',
            'context' => self::ACTIVITIES,
            'text_de' => 'Würfel, 5-Seite',
            'text_en' => 'Dice, 5-Side'
        ],
        '\f524' => [
            'class' => 'fa fa-dice-four',
            'classes' => ['fa-dice-four'],
            'content' => '\f524',
            'context' => self::ACTIVITIES,
            'text_de' => 'Würfel, 4-Seite',
            'text_en' => 'Dice, 4-Side'
        ],
        '\f525' => [
            'class' => 'fa fa-dice-one',
            'classes' => ['fa-dice-one'],
            'content' => '\f525',
            'context' => self::ACTIVITIES,
            'text_de' => 'Würfel, 1-Seite',
            'text_en' => 'Dice, 1-Side'
        ],
        '\f526' => [
            'class' => 'fa fa-dice-six',
            'classes' => ['fa-dice-six'],
            'content' => '\f526',
            'context' => self::ACTIVITIES,
            'text_de' => 'Würfel, 6-Seite',
            'text_en' => 'Dice, 6-Side'
        ],
        '\f527' => [
            'class' => 'fa fa-dice-three',
            'classes' => ['fa-dice-three'],
            'content' => '\f527',
            'context' => self::ACTIVITIES,
            'text_de' => 'Würfel, 3-Seite',
            'text_en' => 'Dice, 3-Side'
        ],
        '\f528' => [
            'class' => 'fa fa-dice-two',
            'classes' => ['fa-dice-two'],
            'content' => '\f528',
            'context' => self::ACTIVITIES,
            'text_de' => 'Würfel, 2-Seite',
            'text_en' => 'Dice, 2-Side'
        ],
        '\f529' => [
            'class' => 'fa fa-divide',
            'classes' => ['fa-divide'],
            'content' => '\f529',
            'context' => self::SECONDARY,
            'text_de' => 'Dividieren',
            'text_en' => 'Divide'
        ],
        '\f52a' => [
            'class' => 'fa fa-door-closed',
            'classes' => ['fa-door-closed'],
            'content' => '\f52a',
            'context' => self::PRIMARY,
            'text_de' => 'Tür, zu',
            'text_en' => 'Door, Closed'
        ],
        '\f52b' => [
            'class' => 'fa fa-door-open',
            'classes' => ['fa-door-open'],
            'content' => '\f52b',
            'context' => self::PRIMARY,
            'text_de' => 'Tür, offen',
            'text_en' => 'Door, Open'
        ],
        '\f52c' => [
            // deprecated?
            'class' => 'fa fa-equals',
            'classes' => ['fa-equals'],
            // unicode 3d
            'content' => '\f52c',
            'text_de' => 'Gleichheitszeichen',
            'text_en' => 'Equals Sign'
        ],
        '\f52d' => [
            'class' => 'fa fa-feather',
            'classes' => ['fa-feather'],
            'content' => '\f52d',
            'text_de' => 'Feder (rund)',
            'text_en' => 'Feather (Rounded)'
        ],
        '\f52e' => [
            'class' => 'fa fa-frog',
            'classes' => ['fa-frog'],
            'content' => '\f52e',
            'text_de' => 'Frosch',
            'text_en' => 'Frog'
        ],
        '\f52f' => [
            'class' => 'fa fa-gas-pump',
            'classes' => ['fa-gas-pump'],
            'content' => '\f52f',
            'text_de' => 'Gaspumpe',
            'text_en' => 'Gas Pump'
        ],
        '\f530' => [
            'class' => 'fa fa-glasses',
            'classes' => ['fa-glasses'],
            'content' => '\f530',
            'text_de' => 'Brille',
            'text_en' => 'Glasses'
        ],
        '\f531' => [
            // deprecated?
            'class' => 'fa fa-greater-than',
            'classes' => ['fa-greater-than'],
            // unicode 3e
            'content' => '\f531',
            'text_de' => 'Größer als',
            'text_en' => 'Greater Than'
        ],
        '\f532' => [
            'class' => 'fa fa-greater-than-equal',
            'classes' => ['fa-greater-than-equal'],
            'content' => '\f532',
            'text_de' => 'Größer Gleich',
            'text_en' => 'Greater Than Equal'
        ],
        '\f533' => [
            'class' => 'fa fa-helicopter',
            'classes' => ['fa-helicopter'],
            'content' => '\f533',
            'text_de' => 'Hubschrauber',
            'text_en' => 'Helicopter'
        ],
        '\f534' => [
            'class' => 'fa fa-infinity',
            'classes' => ['fa-infinity'],
            'content' => '\f534',
            'text_de' => 'Unendlichkeit',
            'text_en' => 'Infinity'
        ],
        '\f535' => [
            'class' => 'fa fa-kiwi-bird',
            'classes' => ['fa-kiwi-bird'],
            'content' => '\f535',
            'text_de' => 'Kiwi (Vogel)',
            'text_en' => 'Kiwi (Bird)'
        ],
        '\f536' => [
            // deprecated?
            'class' => 'fa fa-less-than',
            'classes' => ['fa-less-than'],
            // unicode 3c
            'content' => '\f536',
            'text_de' => 'Weniger als',
            'text_en' => 'Less Than'
        ],
        '\f537' => [
            'class' => 'fa fa-less-than-equal',
            'classes' => ['fa-less-than-equal'],
            'content' => '\f537',
            'text_de' => 'Weniger Gleich',
            'text_en' => 'Less Than Equal'
        ],
        '\f538' => [
            'class' => 'fa fa-memory',
            'classes' => ['fa-memory'],
            'content' => '\f538',
            'text_de' => 'Speicher',
            'text_en' => 'Memory'
        ],
        '\f539' => [
            'class' => 'fa fa-microphone-alt-slash',
            'classes' => ['fa-microphone-alt-slash'],
            'content' => '\f539',
            'context' => self::DEVICES,
            'text_de' => 'Mikrofon (Schrägstrich, geschlitzt)',
            'text_en' => 'Microphone (Slash, Slotted)'
        ],
        '\f53a' => [
            'class' => 'fa fa-money-bill-wave',
            'classes' => ['fa-money-bill-wave'],
            'content' => '\f53a',
            'text_de' => 'Geldschein (wehend)',
            'text_en' => 'Bill (Waving)'
        ],
        '\f53b' => [
            'class' => 'fa fa-money-bill-wave-alt',
            'classes' => ['fa-money-bill-wave-alt'],
            'content' => '\f53b',
            'text_de' => 'Geldschein (wehend, 1)',
            'text_en' => 'Bill (Waving, 1)'
        ],
        '\f53c' => [
            'class' => 'fa fa-money-check',
            'classes' => ['fa-money-check'],
            'content' => '\f53c',
            'text_de' => 'Scheck',
            'text_en' => 'Cheque'
        ],
        '\f53d' => [
            // Same but with a dollar sign
            'class' => 'fa fa-money-check-alt',
            'classes' => ['fa-money-check-alt'],
            'content' => '\f53d'
        ],
        '\f53e' => [
            'class' => 'fa fa-not-equal',
            'classes' => ['fa-not-equal'],
            'content' => '\f53e',
            'text_de' => 'Ungleich',
            'text_en' => 'Unequal'
        ],
        '\f53f' => [
            'class' => 'fa fa-palette',
            'classes' => ['fa-palette'],
            'content' => '\f53f',
            'text_de' => 'Palette',
            'text_en' => 'Palette'
        ],
        '\f540' => [
            'class' => 'fa fa-parking',
            'classes' => ['fa-parking'],
            'content' => '\f540',
            'text_de' => 'Parken',
            'text_en' => 'Parking'
        ],
        '\f541' => [
            'class' => 'fa fa-percentage',
            'classes' => ['fa-percentage'],
            'content' => '\f541',
            'text_de' => 'Prozentsatz',
            'text_en' => 'Percentage'
        ],
        '\f542' => [
            'class' => 'fa fa-project-diagram',
            'classes' => ['fa-project-diagram', 'icon-project-diagram'],
            'content' => '\f542',
            'text_de' => 'Projektdiagramm',
            'text_en' => 'Project Diagram'
        ],
        '\f543' => [
            'class' => 'fa fa-receipt',
            'classes' => ['fa-receipt'],
            'content' => '\f543',
            'text_de' => 'Kassenbon',
            'text_en' => 'Receipt'
        ],
        '\f544' => [
            'class' => 'fa fa-robot',
            'classes' => ['fa-robot'],
            'content' => '\f544',
            'text_de' => 'Roboter',
            'text_en' => 'Robot'
        ],
        '\f545' => [
            'class' => 'fa fa-ruler',
            'classes' => ['fa-ruler'],
            'content' => '\f545',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Lineal',
            'text_en' => 'Ruler'
        ],
        '\f546' => [
            'class' => 'fa fa-ruler-combined',
            'classes' => ['fa-ruler-combined'],
            'content' => '\f546',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Lineal, beide Seiten',
            'text_en' => 'Ruler, Both'
        ],
        '\f547' => [
            'class' => 'fa fa-ruler-horizontal',
            'classes' => ['fa-ruler-horizontal'],
            'content' => '\f547',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Lineal, obere Seite',
            'text_en' => 'Ruler, Top'
        ],
        '\f548' => [
            'class' => 'fa fa-ruler-vertical',
            'classes' => ['fa-ruler-vertical'],
            'content' => '\f548',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Lineal, linke Seite',
            'text_en' => 'Ruler, Left'
        ],
        '\f549' => [
            'class' => 'fa fa-school',
            'classes' => ['fa-school'],
            'content' => '\f549',
            'text_de' => 'Schule',
            'text_en' => 'School'
        ],
        '\f54a' => [
            'class' => 'fa fa-screwdriver',
            'classes' => ['fa-screwdriver'],
            'content' => '\f54a',
            'text_de' => 'Schraubenzieher',
            'text_en' => 'Screwdriver'
        ],
        '\f54b' => [
            'class' => 'fa fa-shoe-prints',
            'classes' => ['fa-shoe-prints'],
            'content' => '\f54b',
            'text_de' => 'Schuhabdrücke',
            'text_en' => 'Shoeprints'
        ],
        '\f54c' => [
            'class' => 'fa fa-skull',
            'classes' => ['fa-skull'],
            'content' => '\f54c',
            'text_de' => 'Schädel',
            'text_en' => 'Skull'
        ],
        '\f54d' => [
            'class' => 'fa fa-smoking-ban',
            'classes' => ['fa-smoking-ban'],
            'content' => '\f54d',
            'text_de' => 'Rauchen (verboten)',
            'text_en' => 'Smoking (Prohibited)'
        ],
        '\f54e' => [
            'class' => 'fa fa-store',
            'classes' => ['fa-store'],
            'content' => '\f54e',
            'text_de' => 'Marktstall',
            'text_en' => 'Marketstall'
        ],
        '\f54f' => [
            'class' => 'fa fa-store-alt',
            'classes' => ['fa-store-alt'],
            'content' => '\f54f',
            'text_de' => 'Laden',
            'text_en' => 'Store'
        ],
        '\f550' => [
            'class' => 'fa fa-stream',
            'classes' => ['fa-stream'],
            'content' => '\f550',
            'text_de' => 'Balken (waagerecht, versetzt)',
            'text_en' => 'Bars (Horizontal, Staggered)'
        ],
        '\f551' => [
            'class' => 'fa fa-stroopwafel',
            'classes' => ['fa-stroopwafel'],
            'content' => '\f551',
            'text_de' => 'Stroopwafel',
            'text_en' => 'Stroopwafel'
        ],
        '\f552' => [
            'class' => 'fa fa-toolbox',
            'classes' => ['fa-toolbox'],
            'content' => '\f552',
            'text_de' => 'Werkzeugkasten',
            'text_en' => 'Toolbox'
        ],
        '\f553' => [
            'class' => 'fa fa-tshirt',
            'classes' => ['fa-tshirt'],
            'content' => '\f553',
            'text_de' => 'T-Shirt',
            'text_en' => 'T-Shirt'
        ],
        '\f554' => [
            'class' => 'fa fa-walking',
            'classes' => ['fa-walking'],
            'content' => '\f554',
            'text_de' => 'Gehen',
            'text_en' => 'Walking'
        ],
        '\f555' => [
            'class' => 'fa fa-wallet',
            'classes' => ['fa-wallet'],
            'content' => '\f555',
            'text_de' => 'Funktionsgegenstände: Geldbörse',
            'text_en' => 'Functional Items: Wallet'
        ],
        '\f556' => [
            'class' => 'fa fa-angry',
            'classes' => ['fa-angry'],
            'content' => '\f556'
        ],
        '\f557' => [
            'class' => 'fa fa-archway',
            'classes' => ['fa-archway'],
            'content' => '\f557',
            'text_de' => 'Torbogen',
            'text_en' => 'Archway'
        ],
        '\f558' => [
            'class' => 'fa fa-atlas',
            'classes' => ['fa-atlas'],
            'content' => '\f558',
            'text_de' => 'Buch (Atlas)',
            'text_en' => 'Book (Atlas)'
        ],
        '\f559' => [
            'class' => 'fa fa-award',
            'classes' => ['fa-award'],
            'content' => '\f559',
            'text_de' => 'Auszeichnung',
            'text_en' => 'Award'
        ],
        '\f55a' => [
            'class' => 'fa fa-backspace',
            'classes' => ['fa-backspace'],
            'content' => '\f55a',
            'text_de' => 'Rücktaste',
            'text_en' => 'Backspace'
        ],
        '\f55b' => [
            'class' => 'fa fa-bezier-curve',
            'classes' => ['fa-bezier-curve'],
            'content' => '\f55b',
            'text_de' => 'Bézierkurve',
            'text_en' => 'Bézier Curve'
        ],
        '\f55c' => [
            'class' => 'fa fa-bong',
            'classes' => ['fa-bong'],
            'content' => '\f55c'
        ],
        '\f55d' => [
            'class' => 'fa fa-brush',
            'classes' => ['fa-brush'],
            'content' => '\f55d',
            'text_de' => 'Funktionsgegenstände: Wandpinsel',
            'text_en' => 'Functional Items: Wall Brush'
        ],
        '\f55e' => [
            'class' => 'fa fa-bus-alt',
            'classes' => ['fa-bus-alt'],
            'content' => '\f55e',
            'text_de' => 'Fahrzeuge: Bus (modern)',
            'text_en' => 'Vehicles: Bus (Modern)'
        ],
        '\f55f' => [
            'class' => 'fa fa-cannabis',
            'classes' => ['fa-cannabis'],
            'content' => '\f55f'
        ],
        '\f560' => [
            'class' => 'fa fa-check-double',
            'classes' => ['fa-check-double'],
            'content' => '\f560',
            'text_de' => 'Symbole: Häkchen (x2)',
            'text_en' => 'Symbols: Checkmark (x2)'
        ],
        '\f561' => [
            'class' => 'fa fa-cocktail',
            'classes' => ['fa-cocktail'],
            'content' => '\f561',
            'text_de' => 'Martini-Glas (Zitrus)',
            'text_en' => 'Martini Glass (Citrus)'
        ],
        '\f562' => [
            'class' => 'fa fa-concierge-bell',
            'classes' => ['fa-concierge-bell'],
            'content' => '\f562',
            'text_de' => 'Klingel',
            'text_en' => 'Bell (Counter)'
        ],
        '\f563' => [
            'class' => 'fa fa-cookie',
            'classes' => ['fa-cookie'],
            'content' => '\f563'
        ],
        '\f564' => [
            'class' => 'fa fa-cookie-bite',
            'classes' => ['fa-cookie-bite'],
            'content' => '\f564',
            'text_de' => 'Keks',
            'text_en' => 'Cookie'
        ],
        '\f565' => [
            'class' => 'fa fa-crop-alt',
            'classes' => ['fa-crop-alt'],
            'content' => '\f565',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Zuschneiden, 2D',
            'text_en' => 'Crop, 2D'
        ],
        '\f566' => [
            'class' => 'fa fa-digital-tachograph',
            'classes' => ['fa-digital-tachograph'],
            'content' => '\f566',
            'text_de' => 'Fahrtenschreiber',
            'text_en' => 'Tachograph'
        ],
        '\f567' => [
            'class' => 'fa fa-dizzy',
            'classes' => ['fa-dizzy'],
            'content' => '\f567'
        ],
        '\f568' => [
            'class' => 'fa fa-drafting-compass',
            'classes' => ['fa-drafting-compass'],
            'content' => '\f568',
            'text_de' => 'Zeichenkompass',
            'text_en' => 'Drafting Compass'
        ],
        '\f569' => [
            'class' => 'fa fa-drum',
            'classes' => ['fa-drum'],
            'content' => '\f569',
            'text_de' => 'Trommel',
            'text_en' => 'Drum'
        ],
        '\f56a' => [
            'class' => 'fa fa-drum-steelpan',
            'classes' => ['fa-drum-steelpan'],
            'content' => '\f56a',
            'text_de' => 'Trommel (Steelpan)',
            'text_en' => 'Drum (Steelpan)'
        ],
        '\f56b' => [
            'class' => 'fa fa-feather-alt',
            'classes' => ['fa-feather-alt'],
            'content' => '\f56b',
            'text_de' => 'Feder (spitz)',
            'text_en' => 'Feather (Pointed)'
        ],
        '\f56c' => [
            'class' => 'fa fa-file-contract',
            'classes' => ['fa-file-contract'],
            'content' => '\f56c',
            'text_de' => 'Vertrag',
            'text_en' => 'Contract'
        ],
        '\f56d' => [
            'class' => 'fa fa-file-download',
            'classes' => ['fa-file-download'],
            'content' => '\f56d',
            'text_de' => 'Herunterladen (Datei)',
            'text_en' => 'Download (File)'
        ],
        '\f56e' => [
            'class' => 'fa fa-file-export',
            'classes' => ['fa-file-export'],
            'content' => '\f56e',
            'text_de' => 'Export',
            'text_en' => 'Export'
        ],
        '\f56f' => [
            'class' => 'fa fa-file-import',
            'classes' => ['fa-file-import'],
            'content' => '\f56f',
            'text_de' => 'Importieren',
            'text_en' => 'Import'
        ],
        '\f570' => [
            'class' => 'fa fa-file-invoice',
            'classes' => ['fa-file-invoice'],
            'content' => '\f570',
            'text_de' => 'Rechnung',
            'text_en' => 'Invoice'
        ],
        '\f571' => [
            'class' => 'fa fa-file-invoice-dollar',
            'classes' => ['fa-file-invoice-dollar'],
            'content' => '\f571',
            'text_de' => 'Rechnung (Geld)',
            'text_en' => 'Invoice (Money)'
        ],
        '\f572' => [
            'class' => 'fa fa-file-prescription',
            'classes' => ['fa-file-prescription'],
            'content' => '\f572',
            'text_de' => 'Verschreibung (Datei)',
            'text_en' => 'Prescription (File)'
        ],
        '\f573' => [
            'class' => 'fa fa-file-signature',
            'classes' => ['fa-file-signature'],
            'content' => '\f573',
            'text_de' => 'Unterschrift (Datei)',
            'text_en' => 'Signature (File)'
        ],
        '\f574' => [
            'class' => 'fa fa-file-upload',
            'classes' => ['fa-file-upload'],
            'content' => '\f574',
            'text_de' => 'Hochladen (Datei)',
            'text_en' => 'Upload (File)'
        ],
        '\f575' => [
            'class' => 'fa fa-fill',
            'classes' => ['fa-fill'],
            'content' => '\f575',
            'text_de' => 'Füllen',
            'text_en' => 'Fill'
        ],
        '\f576' => [
            'class' => 'fa fa-fill-drip',
            'classes' => ['fa-fill-drip'],
            'content' => '\f576',
            'text_de' => 'Füllen (Tropfen)',
            'text_en' => 'Fill (Drop)'
        ],
        '\f577' => [
            'class' => 'fa fa-fingerprint',
            'classes' => ['fa-fingerprint'],
            'content' => '\f577',
            'text_de' => 'Fingerabdruck',
            'text_en' => 'Fingerprint'
        ],
        '\f578' => [
            'class' => 'fa fa-fish',
            'classes' => ['fa-fish'],
            'content' => '\f578',
            'text_de' => 'Fisch',
            'text_en' => 'Fish'
        ],
        '\f579' => [
            'class' => 'fa fa-flushed',
            'classes' => ['fa-flushed'],
            'content' => '\f579'
        ],
        '\f57a' => [
            'class' => 'fa fa-frown-open',
            'classes' => ['fa-frown-open'],
            'content' => '\f57a'
        ],
        '\f57b' => [
            'class' => 'fa fa-glass-martini-alt',
            'classes' => ['fa-glass-martini-alt'],
            'content' => '\f57b',
            'text_de' => 'Martini-Glas (gefüllt)',
            'text_en' => 'Martini Glass (Filled)'
        ],
        '\f57c' => [
            'class' => 'fa fa-globe-africa',
            'classes' => ['fa-globe-africa'],
            'content' => '\f57c',
            'text_de' => 'Globus (Afrika)',
            'text_en' => 'Globe (Africa)'
        ],
        '\f57d' => [
            'class' => 'fa fa-globe-americas',
            'classes' => ['fa-globe-americas'],
            'content' => '\f57d',
            'text_de' => 'Globus (Amerika)',
            'text_en' => 'Globe (Americas)'
        ],
        '\f57e' => [
            'class' => 'fa fa-globe-asia',
            'classes' => ['fa-globe-asia'],
            'content' => '\f57e',
            'text_de' => 'Globus (Asien)',
            'text_en' => 'Globe (Asia)'
        ],
        '\f57f' => [
            'class' => 'fa fa-grimace',
            'classes' => ['fa-grimace'],
            'content' => '\f57f'
        ],
        '\f580' => [
            'class' => 'fa fa-grin',
            'classes' => ['fa-grin'],
            'content' => '\f580'
        ],
        '\f581' => [
            'class' => 'fa fa-grin-alt',
            'classes' => ['fa-grin-alt'],
            'content' => '\f581'
        ],
        '\f582' => [
            'class' => 'fa fa-grin-beam',
            'classes' => ['fa-grin-beam'],
            'content' => '\f582'
        ],
        '\f583' => [
            'class' => 'fa fa-grin-beam-sweat',
            'classes' => ['fa-grin-beam-sweat'],
            'content' => '\f583'
        ],
        '\f584' => [
            'class' => 'fa fa-grin-hearts',
            'classes' => ['fa-grin-hearts'],
            'content' => '\f584'
        ],
        '\f585' => [
            'class' => 'fa fa-grin-squint',
            'classes' => ['fa-grin-squint'],
            'content' => '\f585'
        ],
        '\f586' => [
            'class' => 'fa fa-grin-squint-tears',
            'classes' => ['fa-grin-squint-tears'],
            'content' => '\f586'
        ],
        '\f587' => [
            'class' => 'fa fa-grin-stars',
            'classes' => ['fa-grin-stars'],
            'content' => '\f587'
        ],
        '\f588' => [
            'class' => 'fa fa-grin-tears',
            'classes' => ['fa-grin-tears'],
            'content' => '\f588'
        ],
        '\f589' => [
            'class' => 'fa fa-grin-tongue',
            'classes' => ['fa-grin-tongue'],
            'content' => '\f589'
        ],
        '\f58a' => [
            'class' => 'fa fa-grin-tongue-squint',
            'classes' => ['fa-grin-tongue-squint'],
            'content' => '\f58a'
        ],
        '\f58b' => [
            'class' => 'fa fa-grin-tongue-wink',
            'classes' => ['fa-grin-tongue-wink'],
            'content' => '\f58b'
        ],
        '\f58c' => [
            'class' => 'fa fa-grin-wink',
            'classes' => ['fa-grin-wink'],
            'content' => '\f58c'
        ],
        '\f58d' => [
            'class' => 'fa fa-grip-horizontal',
            'classes' => ['fa-grip-horizontal'],
            'content' => '\f58d',
            'text_de' => 'Galerie (waagerecht)',
            'text_en' => 'Gallery (horizontal)'
        ],
        '\f58e' => [
            'class' => 'fa fa-grip-vertical',
            'classes' => ['fa-grip-vertical'],
            'content' => '\f58e',
            'text_de' => 'Galerie (senkrecht)',
            'text_en' => 'Gallery (vertical)'
        ],
        '\f58f' => [
            'class' => 'fa fa-headphones-alt',
            'classes' => ['fa-headphones-alt'],
            'content' => '\f58f',
            'context' => self::DEVICES,
            'text_de' => 'Funktionsgegenstände: Kopfhörer, verstellbar)',
            'text_en' => 'Functional Items: Headphones, Adjustable)'
        ],
        '\f590' => [
            'class' => 'fa fa-headset',
            'classes' => ['fa-headset'],
            'content' => '\f590',
            'text_de' => 'Funktionsgegenstände: Headset',
            'text_en' => 'Functional Items: Headset'
        ],
        '\f591' => [
            'class' => 'fa fa-highlighter',
            'classes' => ['fa-highlighter'],
            'content' => '\f591',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Textmarker',
            'text_en' => 'Highlighter'
        ],
        '\f592' => [
            'class' => 'fab fa-hornbill',
            'classes' => ['fa-hornbill'],
            'content' => '\f592'
        ],
        '\f593' => [
            'class' => 'fa fa-hot-tub',
            'classes' => ['fa-hot-tub'],
            'content' => '\f593',
            'text_de' => 'Whirlpool',
            'text_en' => 'Hot Tub'
        ],
        '\f594' => [
            'class' => 'fa fa-hotel',
            'classes' => ['fa-hotel'],
            'content' => '\f594',
            'text_de' => 'Hotel',
            'text_en' => 'Hotel'
        ],
        '\f595' => [
            'class' => 'fa fa-joint',
            'classes' => ['fa-joint'],
            'content' => '\f595'
        ],
        '\f596' => [
            'class' => 'fa fa-kiss',
            'classes' => ['fa-kiss'],
            'content' => '\f596'
        ],
        '\f597' => [
            'class' => 'fa fa-kiss-beam',
            'classes' => ['fa-kiss-beam'],
            'content' => '\f597'
        ],
        '\f598' => [
            'class' => 'fa fa-kiss-wink-heart',
            'classes' => ['fa-kiss-wink-heart'],
            'content' => '\f598'
        ],
        '\f599' => [
            'class' => 'fa fa-laugh',
            'classes' => ['fa-laugh'],
            'content' => '\f599'
        ],
        '\f59a' => [
            'class' => 'fa fa-laugh-beam',
            'classes' => ['fa-laugh-beam'],
            'content' => '\f59a'
        ],
        '\f59b' => [
            'class' => 'fa fa-laugh-squint',
            'classes' => ['fa-laugh-squint'],
            'content' => '\f59b'
        ],
        '\f59c' => [
            'class' => 'fa fa-laugh-wink',
            'classes' => ['fa-laugh-wink'],
            'content' => '\f59c'
        ],
        '\f59d' => [
            'class' => 'fa fa-luggage-cart',
            'classes' => ['fa-luggage-cart'],
            'content' => '\f59d',
            'text_de' => 'Funktionsgegenstände: Wagen (Koffer)',
            'text_en' => 'Functional Items: Cart (Luggage)'
        ],
        '\f59e' => [
            'class' => 'fab fa-mailchimp',
            'classes' => ['fa-mailchimp'],
            'content' => '\f59e'
        ],
        '\f59f' => [
            'class' => 'fa fa-map-marked',
            'classes' => ['fa-map-marked'],
            'content' => '\f59f',
            'text_de' => 'Karte (Standort-Pin)',
            'text_en' => 'Map (Location-Pin)'
        ],
        '\f5a0' => [
            'class' => 'fa fa-map-marked-alt',
            'classes' => ['fa-map-marked-alt'],
            'content' => '\f5a0',
            'text_de' => 'Karte (Standort-Pin, gepunktet)',
            'text_en' => 'Map (Location-Pin, Dotted)'
        ],
        '\f5a1' => [
            'class' => 'fa fa-marker',
            'classes' => ['fa-marker'],
            'content' => '\f5a1',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Filzstift',
            'text_en' => 'Marker'
        ],
        '\f5a2' => [
            'class' => 'fa fa-medal',
            'classes' => ['fa-medal'],
            'content' => '\f5a2',
            'text_de' => 'Medaille',
            'text_en' => 'Medal'
        ],
        '\f5a3' => [
            'class' => 'fa fa-megaport',
            'classes' => ['fa-megaport'],
            'content' => '\f5a3'
        ],
        '\f5a4' => [
            'class' => 'fa fa-meh-blank',
            'classes' => ['fa-meh-blank'],
            'content' => '\f5a4'
        ],
        '\f5a5' => [
            'class' => 'fa fa-meh-rolling-eyes',
            'classes' => ['fa-meh-rolling-eyes'],
            'content' => '\f5a5'
        ],
        '\f5a6' => [
            'class' => 'fa fa-monument',
            'classes' => ['fa-monument'],
            'content' => '\f5a6',
            'text_de' => 'Denkmal',
            'text_en' => 'Monument'
        ],
        '\f5a7' => [
            'class' => 'fa fa-mortar-pestle',
            'classes' => ['fa-mortar-pestle'],
            'content' => '\f5a7',
            'text_de' => 'Funktionsgegenstände: Mörtel & Stampfe',
            'text_en' => 'Functional Items: Mortar & Pestle'
        ],
        '\f5a8' => [
            'class' => 'fab fa-nimblr',
            'classes' => ['fa-nimblr'],
            'content' => '\f5a8'
        ],
        '\f5aa' => [
            'class' => 'fa fa-paint-roller',
            'classes' => ['fa-paint-roller'],
            'content' => '\f5aa',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Farbroller',
            'text_en' => 'Roller'
        ],
        '\f5ab' => [
            'class' => 'fa fa-passport',
            'classes' => ['fa-passport'],
            'content' => '\f5ab',
            'context' => self::TRAVEL,
            'text_de' => 'Reisepass',
            'text_en' => 'Passport'
        ],
        '\f5ac' => [
            'class' => 'fa fa-pen-fancy',
            'classes' => ['fa-pen-fancy'],
            'content' => '\f5ac',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Füller',
            'text_en' => 'Fountain Pen'
        ],
        '\f5ad' => [
            'class' => 'fa fa-pen-nib',
            'classes' => ['fa-pen-nib'],
            'content' => '\f5ad',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Füller, Spitze',
            'text_en' => 'Fountain Pe, Tip'
        ],
        '\f5ae' => [
            'class' => 'fa fa-pencil-ruler',
            'classes' => ['fa-pencil-ruler'],
            'content' => '\f5ae',
            'context' => self::PAINT_FUNCTIONS,
            'text_de' => 'Stift & Lineal',
            'text_en' => 'Pencil & Ruler'
        ],
        '\f5af' => [
            'class' => 'fa fa-plane-arrival',
            'classes' => ['fa-plane-arrival'],
            'content' => '\f5af',
            'context' => self::TRAVEL,
            'text_de' => 'Ankunft',
            'text_en' => 'Arrival'
        ],
        '\f5b0' => [
            'class' => 'fa fa-plane-departure',
            'classes' => ['fa-plane-departure'],
            'content' => '\f5b0',
            'context' => self::TRAVEL,
            'text_de' => 'Abreise',
            'text_en' => 'Departure'
        ],
        '\f5b1' => [
            'class' => 'fa fa-prescription',
            'classes' => ['fa-prescription'],
            'content' => '\f5b1',
            'context' => self::MEDICAL,
            'text_de' => 'Verschreibung',
            'text_en' => 'Prescription'
        ],
        '\f5b2' => [
            'class' => 'fab fa-rev',
            'classes' => ['fa-rev'],
            'content' => '\f5b2'
        ],
        '\f5b3' => [
            'class' => 'fa fa-sad-cry',
            'classes' => ['fa-sad-cry'],
            'content' => '\f5b3',
            'text_de' => 'Weinen',
            'text_en' => 'Crying'
        ],
        '\f5b4' => [
            'class' => 'fa fa-sad-tear',
            'classes' => ['fa-sad-tear'],
            'content' => '\f5b4',
            'text_de' => 'Traurig (Träne)',
            'text_en' => 'Sad (Tear)'
        ],
        '\f5b5' => [
            'class' => 'fab fa-shopware',
            'classes' => ['fa-shopware'],
            'content' => '\f5b5'
        ],
        '\f5b6' => [
            'class' => 'fa fa-shuttle-van',
            'classes' => ['fa-shuttle-van'],
            'content' => '\f5b6',
            'text_de' => 'Sprinter',
            'text_en' => 'Van'
        ],
        '\f5b7' => [
            'class' => 'fa fa-signature',
            'classes' => ['fa-signature'],
            'content' => '\f5b7',
            'text_de' => 'Unterschrift',
            'text_en' => 'Signature'
        ],
        '\f5b8' => [
            'class' => 'fa fa-smile-beam',
            'classes' => ['fa-smile-beam'],
            'content' => '\f5b8',
            'text_de' => 'Strahlend',
            'text_en' => 'Beaming'
        ],
        '\f5ba' => [
            'class' => 'fa fa-solar-panel',
            'classes' => ['fa-solar-panel'],
            'content' => '\f5ba',
            'text_de' => 'Solarpanel',
            'text_en' => 'Solar Panel'
        ],
        '\f5bb' => [
            'class' => 'fa fa-spa',
            'classes' => ['fa-spa'],
            'content' => '\f5bb',
            'text_de' => 'Spa',
            'text_en' => 'Spa'
        ],
        '\f5bc' => [
            'class' => 'fa fa-splotch',
            'classes' => ['fa-splotch'],
            'content' => '\f5bc',
            'text_de' => 'Fleck',
            'text_en' => 'Splotch'
        ],
        '\f5bd' => [
            'class' => 'fa fa-spray-can',
            'classes' => ['fa-spray-can'],
            'content' => '\f5bd',
            'text_de' => 'Sprühdose',
            'text_en' => 'Spray Can'
        ],
        '\f5be' => [
            'class' => 'fab fa-squarespace',
            'classes' => ['fa-squarespace'],
            'content' => '\f5be'
        ],
        '\f5bf' => [
            'class' => 'fa fa-stamp',
            'classes' => ['fa-stamp'],
            'content' => '\f5bf',
            'text_de' => 'Stempel',
            'text_en' => 'Stamp'
        ],
        '\f5c0' => [
            'class' => 'fa fa-star-half-alt',
            'classes' => ['fa-star-half-alt'],
            'content' => '\f5c0',
            'text_de' => 'Stern (halb, begrenzt)',
            'text_en' => 'Star (Half, Bordered)'
        ],
        '\f5c1' => [
            'class' => 'fa fa-suitcase-rolling',
            'classes' => ['fa-suitcase-rolling'],
            'content' => '\f5c1',
            'text_de' => 'Koffer (Reise)',
            'text_en' => 'Suitcase (Travel)'
        ],
        '\f5c2' => [
            'class' => 'fa fa-surprise',
            'classes' => ['fa-surprise'],
            'content' => '\f5c2'
        ],
        '\f5c3' => [
            'class' => 'fa fa-swatchbook',
            'classes' => ['fa-swatchbook'],
            'content' => '\f5c3',
            'text_de' => 'Musterbuch',
            'text_en' => 'Sample Book'
        ],
        '\f5c4' => [
            'class' => 'fa fa-swimmer',
            'classes' => ['fa-swimmer'],
            'content' => '\f5c4',
            'text_de' => 'Schwimmen',
            'text_en' => 'Swimming'
        ],
        '\f5c5' => [
            'class' => 'fa fa-swimming-pool',
            'classes' => ['fa-swimming-pool'],
            'content' => '\f5c5',
            'text_de' => 'Schwimmbad',
            'text_en' => 'Swimming Pool'
        ],
        '\f5c6' => [
            'class' => 'fab fa-themeco',
            'classes' => ['fa-themeco'],
            'content' => '\f5c6'
        ],
        '\f5c7' => [
            'class' => 'fa fa-tint-slash',
            'classes' => ['fa-tint-slash'],
            'content' => '\f5c7',
            'text_de' => 'Tropfen (Schrägstrich)',
            'text_en' => 'Drop (Slash)'
        ],
        '\f5c8' => [
            'class' => 'fa fa-tired',
            'classes' => ['fa-tired'],
            'content' => '\f5c8',
            'text_de' => 'Aufgelöst',
            'text_en' => 'Distraught'
        ],
        '\f5c9' => [
            'class' => 'fa fa-tooth',
            'classes' => ['fa-tooth'],
            'content' => '\f5c9',
            'text_de' => 'Zahn',
            'text_en' => 'Tooth'
        ],
        '\f5ca' => [
            'class' => 'fa fa-umbrella-beach',
            'classes' => ['fa-umbrella-beach'],
            'content' => '\f5ca',
            'text_de' => 'Schirm (Strand)',
            'text_en' => 'Umbrella (Beach)'
        ],
        '\f5cb' => [
            'class' => 'fa fa-vector-square',
            'classes' => ['fa-vector-square'],
            'content' => '\f5cb',
            'text_de' => 'Quadrat (Vektor)',
            'text_en' => 'Square (Vector)'
        ],
        '\f5cc' => [
            'class' => 'fab fa-weebly',
            'classes' => ['fa-weebly'],
            'content' => '\f5cc'
        ],
        '\f5cd' => [
            'class' => 'fa fa-weight-hanging',
            'classes' => ['fa-weight-hanging'],
            'content' => '\f5cd',
            'text_de' => 'Gewicht',
            'text_en' => 'Weight'
        ],
        '\f5ce' => [
            'class' => 'fa fa-wine-glass-alt',
            'classes' => ['fa-wine-glass-alt'],
            'content' => '\f5ce',
            'text_de' => 'Weinglas (leer)',
            'text_en' => 'Wine Glass (Empty)'
        ],
        '\f5cf' => [
            'class' => 'fab fa-wix',
            'classes' => ['fa-wix'],
            'content' => '\f5cf'
        ],
        '\f5d0' => [
            'class' => 'fa fa-air-freshener',
            'classes' => ['fa-air-freshener'],
            'content' => '\f5d0',
            'text_de' => 'Perfüm',
            'text_en' => 'Perfume'
        ],
        '\f5d1' => [
            'class' => 'fa fa-apple-alt',
            'classes' => ['fa-apple-alt'],
            'content' => '\f5d1',
            'text_de' => 'Apfel',
            'text_en' => 'Apple'
        ],
        '\f5d2' => [
            'class' => 'fa fa-atom',
            'classes' => ['fa-atom'],
            'content' => '\f5d2',
            'text_de' => 'Atom',
            'text_en' => 'Atom'
        ],
        '\f5d7' => [
            'class' => 'fa fa-bone',
            'classes' => ['fa-bone'],
            'content' => '\f5d7',
            'text_de' => 'Knochen',
            'text_en' => 'Bone'
        ],
        '\f5da' => [
            'class' => 'fa fa-book-reader',
            'classes' => ['fa-book-reader'],
            'content' => '\f5da',
            'text_de' => 'Leser',
            'text_en' => 'Reader'
        ],
        '\f5dc' => [
            'class' => 'fa fa-brain',
            'classes' => ['fa-brain'],
            'content' => '\f5dc',
            'text_de' => 'Hirn',
            'text_en' => 'Brain'
        ],
        '\f5de' => [
            'class' => 'fa fa-car-alt',
            'classes' => ['fa-car-alt'],
            'content' => '\f5de',
            'text_de' => 'Auto (hinten)',
            'text_en' => 'Car (Rear-View)'
        ],
        '\f5df' => [
            'class' => 'fa fa-car-battery',
            'classes' => ['fa-car-battery'],
            'content' => '\f5df',
            'text_de' => 'Autobatterie',
            'text_en' => 'Car Battery'
        ],
        '\f5e1' => [
            'class' => 'fa fa-car-crash',
            'classes' => ['fa-car-crash'],
            'content' => '\f5e1',
            'text_de' => 'Auto (Unfall)',
            'text_en' => 'Car (Crash)'
        ],
        '\f5e4' => [
            'class' => 'fa fa-car-side',
            'classes' => ['fa-car-side'],
            'content' => '\f5e4',
            'text_de' => 'Auto (seitlich)',
            'text_en' => 'Car (Sidelong)'
        ],
        '\f5e7' => [
            'class' => 'fa fa-charging-station',
            'classes' => ['fa-charging-station'],
            'content' => '\f5e7',
            'text_de' => 'Ladestation',
            'text_en' => 'Charging Station'
        ],
        '\f5eb' => [
            'class' => 'fa fa-directions',
            'classes' => ['fa-directions'],
            'content' => '\f5eb',
            'text_de' => 'Richtungen: Rechtskurve',
            'text_en' => 'Directions: Right Turn'
        ],
        '\f5ee' => [
            'class' => 'fa fa-draw-polygon',
            'classes' => ['fa-draw-polygon'],
            'content' => '\f5ee',
            'text_de' => 'Polygon zeichnen',
            'text_en' => 'Polygon Drawing'
        ],
        '\f5f1' => [
            'class' => 'fab fa-ello',
            'classes' => ['fa-ello'],
            'content' => '\f5f1'
        ],
        '\f5f7' => [
            'class' => 'fab fa-hackerrank',
            'classes' => ['fa-hackerrank'],
            'content' => '\f5f7'
        ],
        '\f5fa' => [
            // Research platform
            'class' => 'fab fa-kaggle',
            'classes' => ['fa-kaggle'],
            'content' => '\f5fa',
            'text_de' => 'Logos: Kaggle',
            'text_en' => 'Logos: Kaggle'
        ],
        '\f5fc' => [
            'class' => 'fa fa-laptop-code',
            'classes' => ['fa-laptop-code'],
            'content' => '\f5fc',
            'context' => self::DEVICES,
            'text_de' => 'Laptop (Code)',
            'text_en' => 'Laptop (Code)'
        ],
        '\f5fd' => [
            'class' => 'fa fa-layer-group',
            'classes' => ['fa-layer-group'],
            'content' => '\f5fd',
            'text_de' => 'Schichten / Stapel (3D)',
            'text_en' => 'Layers / Stack (3D)'
        ],
        '\f604' => [
            'class' => 'fa fa-lungs',
            'classes' => ['fa-lungs'],
            'content' => '\f604',
            'text_de' => 'Lunge',
            'text_en' => 'Lungs'
        ],
        '\f60f' => [
            'class' => 'fab fa-markdown',
            'classes' => ['fa-markdown'],
            'content' => '\f60f'
        ],
        '\f610' => [
            'class' => 'fa fa-microscope',
            'classes' => ['fa-microscope'],
            'content' => '\f610',
            'text_de' => 'Mikroskop',
            'text_en' => 'Microscope'
        ],
        '\f612' => [
            'class' => 'fab fa-neos',
            'classes' => ['fa-neos'],
            'content' => '\f612'
        ],
        '\f613' => [
            'class' => 'fa fa-oil-can',
            'classes' => ['fa-oil-can'],
            'content' => '\f613',
            'text_de' => 'Funktionsgegenstände: Ölkanne',
            'text_en' => 'Functional Items: Oil Can'
        ],
        '\f619' => [
            'class' => 'fa fa-poop',
            'classes' => ['fa-poop'],
            'content' => '\f619'
        ],
        '\f61f' => [
            'class' => 'fa fa-shapes',
            'classes' => ['fa-shapes'],
            'content' => '\f61f',
            'text_de' => 'Formen',
            'text_en' => 'Shapes'
        ],
        '\f621' => [
            'class' => 'fa fa-star-of-life',
            'classes' => ['fa-star-of-life'],
            'content' => '\f621',
            'text_de' => 'Symbole: Sternchen',
            'text_en' => 'Symbols: Asterisk'
        ],
        '\f62e' => [
            'class' => 'fa fa-teeth',
            'classes' => ['fa-teeth'],
            'content' => '\f62e',
            'text_de' => 'Zähne',
            'text_en' => 'Teeth'
        ],
        '\f62f' => [
            'class' => 'fa fa-teeth-open',
            'classes' => ['fa-teeth-open'],
            'content' => '\f62f',
            'text_de' => 'Zähne (offen)',
            'text_en' => 'Teeth (Open)'
        ],
        '\f630' => [
            'class' => 'fa fa-theater-masks',
            'classes' => ['fa-theater-masks'],
            'content' => '\f630',
            'text_de' => 'Masken',
            'text_en' => 'Masks'
        ],
        '\f637' => [
            'class' => 'fa fa-traffic-light',
            'classes' => ['fa-traffic-light'],
            'content' => '\f637',
            'text_de' => 'Ampel',
            'text_en' => 'Traffic Light'
        ],
        '\f63b' => [
            'class' => 'fa fa-truck-monster',
            'classes' => ['fa-truck-monster'],
            'content' => '\f63b',
            'text_de' => 'Monster Truck',
            'text_en' => 'Truck (Monster)'
        ],
        '\f63c' => [
            'class' => 'fa fa-truck-pickup',
            'classes' => ['fa-truck-pickup'],
            'content' => '\f63c',
            'text_de' => 'Pickup-Truck',
            'text_en' => 'Truck (Pickup)'
        ],
        '\f63f' => [
            // Collaboration platform
            'class' => 'fab fa-zhihu',
            'classes' => ['fa-zhihu'],
            'content' => '\f63f',
            'text_de' => 'Logos: Zhihu',
            'text_en' => 'Logos: Zhihu'
        ],
        '\f641' => [
            'class' => 'fa fa-ad',
            'classes' => ['fa-ad'],
            'content' => '\f641',
            'text_de' => 'Ad',
            'text_en' => 'Ad'
        ],
        '\f642' => [
            'class' => 'fa fa-alipay',
            'classes' => ['fa-alipay'],
            'content' => '\f642'
        ],
        '\f644' => [
            'class' => 'fa fa-ankh',
            'classes' => ['fa-ankh'],
            'content' => '\f644',
            'text_de' => 'Anch',
            'text_en' => 'Ankh'
        ],
        '\f647' => [
            'class' => 'fa fa-bible',
            'classes' => ['fa-bible'],
            'content' => '\f647'
        ],
        '\f64a' => [
            'class' => 'fa fa-business-time',
            'classes' => ['fa-business-time'],
            'content' => '\f64a',
            'text_de' => 'Geschäftszeiten',
            'text_en' => 'Business Hours'
        ],
        '\f64f' => [
            'class' => 'fa fa-city',
            'classes' => ['fa-city'],
            'content' => '\f64f',
            'text_de' => 'Stadt',
            'text_en' => 'City'
        ],
        '\f651' => [
            'class' => 'fa fa-comment-dollar',
            'classes' => ['fa-comment-dollar'],
            'content' => '\f651',
            'text_de' => 'Kommentar (Geld)',
            'text_en' => 'Comment (Money)'
        ],
        '\f653' => [
            'class' => 'fa fa-comments-dollar',
            'classes' => ['fa-comments-dollar'],
            'content' => '\f653',
            'text_de' => 'Kommentare (Geld)',
            'text_en' => 'Comments (Money)'
        ],
        '\f654' => [
            'class' => 'fa fa-cross',
            'classes' => ['fa-cross'],
            'content' => '\f654'
        ],
        '\f655' => [
            'class' => 'fa fa-dharmachakra',
            'classes' => ['fa-dharmachakra'],
            'content' => '\f655',
            'text_de' => 'Dharmachakra',
            'text_en' => 'Dharmachakra'
        ],
        '\f658' => [
            'class' => 'fa fa-envelope-open-text',
            'classes' => ['fa-envelope-open-text', 'icon-envelope-open-text'],
            'content' => '\f658',
            'text_de' => 'Umschlag (offen, Inhalt)',
            'text_en' => 'Envelope (Open, Content)'
        ],
        '\f65d' => [
            'class' => 'fa fa-folder-minus',
            'classes' => ['fa-folder-minus'],
            'content' => '\f65d',
            'text_de' => 'Ordner (Minus)',
            'text_en' => 'Folder (Minus)'
        ],
        '\f65e' => [
            'class' => 'fa fa-folder-plus',
            'classes' => ['fa-folder-plus'],
            'content' => '\f65e',
            'text_de' => 'Ordner (Plus)',
            'text_en' => 'Folder (Plus)'
        ],
        '\f662' => [
            'class' => 'fa fa-funnel-dollar',
            'classes' => ['fa-funnel-dollar'],
            'content' => '\f662',
            'text_de' => 'Filter / Trichter (Geld)',
            'text_en' => 'Filter / Funnel (Money)'
        ],
        '\f664' => [
            'class' => 'fa fa-gopuram',
            'classes' => ['fa-gopuram'],
            'content' => '\f664',
            'text_de' => 'Gopuram',
            'text_en' => 'Gopuram'
        ],
        '\f665' => [
            'class' => 'fa fa-hamsa',
            'classes' => ['fa-hamsa'],
            'content' => '\f665',
            'text_de' => 'Hand der Fatima',
            'text_en' => 'Hamsa'
        ],
        '\f666' => [
            'class' => 'fa fa-bahai',
            'classes' => ['fa-bahai'],
            'content' => '\f666'
        ],
        '\f669' => [
            'class' => 'fa fa-jedi',
            'classes' => ['fa-jedi'],
            'content' => '\f669'
        ],
        '\f66a' => [
            'class' => 'fa fa-journal-whills',
            'classes' => ['fa-journal-whills'],
            'content' => '\f66a'
        ],
        '\f66b' => [
            'class' => 'fa fa-kaaba',
            'classes' => ['fa-kaaba'],
            'content' => '\f66b',
            'text_de' => 'Kaaba',
            'text_en' => 'Kaaba'
        ],
        '\f66d' => [
            'class' => 'fa fa-khanda',
            'classes' => ['fa-khanda'],
            'content' => '\f66d',
            'text_de' => 'Khanda',
            'text_en' => 'Khanda'
        ],
        '\f66f' => [
            'class' => 'fa fa-landmark',
            'classes' => ['fa-landmark'],
            'content' => '\f66f',
            'text_de' => 'Wahrzeichen',
            'text_en' => 'Landmark'
        ],
        '\f674' => [
            'class' => 'fa fa-mail-bulk',
            'classes' => ['fa-mail-bulk'],
            'content' => '\f674',
            'text_de' => 'Massenversand',
            'text_en' => 'Bulk Mail'
        ],
        '\f676' => [
            'class' => 'fa fa-menorah',
            'classes' => ['fa-menorah'],
            'content' => '\f676'
        ],
        '\f678' => [
            'class' => 'fa fa-mosque',
            'classes' => ['fa-mosque'],
            'content' => '\f678',
            'text_de' => 'Moschee',
            'text_en' => 'Mosque'
        ],
        '\f679' => [
            'class' => 'fa fa-om',
            'classes' => ['fa-om'],
            'content' => '\f679',
            'text_de' => 'Om',
            'text_en' => 'Om'
        ],
        '\f67b' => [
            'class' => 'fa fa-pastafarianism',
            'classes' => ['fa-pastafarianism'],
            'content' => '\f67b',
            'text_de' => 'Pastafarianismus',
            'text_en' => 'Pastafarianism'
        ],
        '\f67c' => [
            'class' => 'fa fa-peace',
            'classes' => ['fa-peace'],
            'content' => '\f67c',
            'text_de' => 'Frieden',
            'text_en' => 'Peace'
        ],
        '\f67f' => [
            'class' => 'fa fa-place-of-worship',
            'classes' => ['fa-place-of-worship'],
            'content' => '\f67f',
            'text_de' => 'Anbetungsstätte',
            'text_en' => 'Place of Worship'
        ],
        '\f681' => [
            'class' => 'fa fa-poll',
            'classes' => ['fa-poll'],
            'content' => '\f681',
            'text_de' => 'Diagramm (Balken, senkrecht)',
            'text_en' => 'Graph (Bar, Vertical)'
        ],
        '\f682' => [
            'class' => 'fa fa-poll-h',
            'classes' => ['fa-poll-h'],
            'content' => '\f682',
            'text_de' => 'Diagramm (Balken, waagerecht)',
            'text_en' => 'Graph (Bar, Horizontal)'
        ],
        '\f683' => [
            'class' => 'fa fa-pray',
            'classes' => ['fa-pray'],
            'content' => '\f683'
        ],
        '\f684' => [
            'class' => 'fa fa-praying-hands',
            'classes' => ['fa-praying-hands'],
            'content' => '\f684',
            'text_de' => 'Hände (betend)',
            'text_en' => 'Hands (Praying)'
        ],
        '\f687' => [
            'class' => 'fa fa-quran',
            'classes' => ['fa-quran'],
            'content' => '\f687'
        ],
        '\f688' => [
            'class' => 'fa fa-search-dollar',
            'classes' => ['fa-search-dollar'],
            'content' => '\f688',
            'text_de' => 'Suche (Geld)',
            'text_en' => 'Search (Money)'
        ],
        '\f689' => [
            'class' => 'fa fa-search-location',
            'classes' => ['fa-search-location'],
            'content' => '\f689',
            'text_de' => 'Suche (Ort)',
            'text_en' => 'Search (Location)'
        ],
        '\f696' => [
            'class' => 'fa fa-socks',
            'classes' => ['fa-socks'],
            'content' => '\f696',
            'text_de' => 'Socken',
            'text_en' => 'Socks'
        ],
        '\f698' => [
            'class' => 'fa fa-square-root-alt',
            'classes' => ['fa-square-root-alt'],
            'content' => '\f698',
            'text_de' => 'Quadratwurzel',
            'text_en' => 'Square Root'
        ],
        '\f699' => [
            'class' => 'fa fa-star-and-crescent',
            'classes' => ['fa-star-and-crescent'],
            'content' => '\f699'
        ],
        '\f69a' => [
            'class' => 'fa fa-star-of-david',
            'classes' => ['fa-star-of-david'],
            'content' => '\f69a',
            'text_de' => 'Davidstern',
            'text_en' => 'Star of David'
        ],
        '\f69b' => [
            'class' => 'fa fa-synagogue',
            'classes' => ['fa-synagogue'],
            'content' => '\f69b',
            'text_de' => 'Synagoge',
            'text_en' => 'Synagogue'
        ],
        '\f69d' => [
            'class' => 'fa fa-the-red-yeti',
            'classes' => ['fa-the-red-yeti'],
            'content' => '\f69d'
        ],
        '\f6a0' => [
            'class' => 'fa fa-torah',
            'classes' => ['fa-torah'],
            'content' => '\f6a0'
        ],
        '\f6a1' => [
            'class' => 'fa fa-torii-gate',
            'classes' => ['fa-torii-gate'],
            'content' => '\f6a1',
            'text_de' => 'Torii Tor',
            'text_en' => 'Torii Gate'
        ],
        '\f6a7' => [
            'class' => 'fa fa-vihara',
            'classes' => ['fa-vihara'],
            'content' => '\f6a7',
            'text_de' => 'Vihara',
            'text_en' => 'Vihara'
        ],
        '\f6a9' => [
            'class' => 'fa fa-volume-mute',
            'classes' => ['fa-volume-mute'],
            'content' => '\f6a9',
            'context' => self::SYSTEM_ICONS,
            'text_de' => 'Lautstärke, stumm',
            'text_en' => 'Volume, Mute'
        ],
        '\f6ad' => [
            'class' => 'fa fa-yin-yang',
            'classes' => ['fa-yin-yang'],
            'content' => '\f6ad',
            'text_de' => 'Yin-Yang',
            'text_en' => 'Yin-Yang'
        ],
        '\f6af' => [
            'class' => 'fa fa-acquisitions-incorporated',
            'classes' => ['fa-acquisitions-incorporated'],
            'content' => '\f6af'
        ],
        '\f6b6' => [
            'class' => 'fa fa-blender-phone',
            'classes' => ['fa-blender-phone'],
            'content' => '\f6b6',
            'context' => self::DEVICES,
            'text_de' => 'Mixer-Telefon',
            'text_en' => 'Blender Phone'
        ],
        '\f6b7' => [
            'class' => 'fa fa-book-dead',
            'classes' => ['fa-book-dead'],
            'content' => '\f6b7',
            'text_de' => 'Buch (gefählich / Piraten)',
            'text_en' => 'Book (Dangerous / Pirates)'
        ],
        '\f6bb' => [
            'class' => 'fa fa-campground',
            'classes' => ['fa-campground'],
            'content' => '\f6bb',
            'text_de' => 'Campingplatz',
            'text_en' => 'Campground'
        ],
        '\f6be' => [
            'class' => 'fa fa-cat',
            'classes' => ['fa-cat'],
            'content' => '\f6be',
            'text_de' => 'Tiere: Katze',
            'text_en' => 'Animals: Cat'
        ],
        '\f6c0' => [
            'class' => 'fa fa-chair',
            'classes' => ['fa-chair'],
            'content' => '\f6c0',
            'text_de' => 'Möbel: Stuhl',
            'text_en' => 'Furniture: Chair'
        ],
        '\f6c3' => [
            'class' => 'fa fa-cloud-moon',
            'classes' => ['fa-cloud-moon'],
            'content' => '\f6c3',
            'text_de' => 'Wetter: teilweise bewölkt, über Nacht ',
            'text_en' => 'Weather: Partly Cloudy, Overnight'
        ],
        '\f6c4' => [
            'class' => 'fa fa-cloud-sun',
            'classes' => ['fa-cloud-sun'],
            'content' => '\f6c4',
            'text_de' => 'Wetter: teilweise bewölkt',
            'text_en' => 'Weather: Partly Cloudy'
        ],
        '\f6c9' => [
            'class' => 'fa fa-critical-role',
            'classes' => ['fa-critical-role'],
            'content' => '\f6c9'
        ],
        '\f6ca' => [
            'class' => 'fa fa-d-and-d-beyond',
            'classes' => ['fa-d-and-d-beyond'],
            'content' => '\f6ca'
        ],
        '\f6cc' => [
            'class' => 'fab fa-dev',
            'classes' => ['fa-dev'],
            'content' => '\f6cc'
        ],
        '\f6cf' => [
            'class' => 'fa fa-dice-d20',
            'classes' => ['fa-dice-d20'],
            'content' => '\f6cf',
            'text_de' => 'Würfel (D20)',
            'text_en' => 'Dice (D20)'
        ],
        '\f6d1' => [
            'class' => 'fa fa-dice-d6',
            'classes' => ['fa-dice-d6'],
            'content' => '\f6d1',
            'text_de' => 'Würfel (3D)',
            'text_en' => 'Cube / Dice (3D)'
        ],
        '\f6d3' => [
            'class' => 'fa fa-dog',
            'classes' => ['fa-dog'],
            'content' => '\f6d3',
            'text_de' => 'Hund',
            'text_en' => 'Dog'
        ],
        '\f6d5' => [
            'class' => 'fa fa-dragon',
            'classes' => ['fa-dragon'],
            'content' => '\f6d5',
            'text_de' => 'Dragon',
            'text_en' => 'Dragon'
        ],
        '\f6d7' => [
            'class' => 'fa fa-drumstick-bite',
            'classes' => ['fa-drumstick-bite'],
            'content' => '\f6d7',
            'text_de' => 'Hähnchenschenkel',
            'text_en' => 'Drumstick (Chicken)'
        ],
        '\f6d9' => [
            'class' => 'fa fa-dungeon',
            'classes' => ['fa-dungeon'],
            'content' => '\f6d9',
            'text_de' => 'Verlies',
            'text_en' => 'Dungeon'
        ],
        '\f6dc' => [
            'class' => 'fa fa-fantasy-flight-games',
            'classes' => ['fa-fantasy-flight-games'],
            'content' => '\f6dc'
        ],
        '\f6dd' => [
            'class' => 'fa fa-file-csv',
            'classes' => ['fa-file-csv'],
            'content' => '\f6dd',
            'text_de' => 'Datei (CSV)',
            'text_en' => 'File (CSV)'
        ],
        '\f6de' => [
            'class' => 'fa fa-fist-raised',
            'classes' => ['fa-fist-raised'],
            'content' => '\f6de',
            'text_de' => 'Faust',
            'text_en' => 'Fist'
        ],
        '\f6e2' => [
            'class' => 'fa fa-ghost',
            'classes' => ['fa-ghost'],
            'content' => '\f6e2',
            'text_de' => 'Gespenst',
            'text_en' => 'Ghost'
        ],
        '\f6e3' => [
            'class' => 'fa fa-hammer',
            'classes' => ['fa-hammer'],
            'content' => '\f6e3',
            'text_de' => 'Hammer',
            'text_en' => 'Hammer'
        ],
        '\f6e6' => [
            'class' => 'fa fa-hanukiah',
            'classes' => ['fa-hanukiah'],
            'content' => '\f6e6',
            'text_de' => 'Hanukiah',
            'text_en' => 'Hanukiah'
        ],
        '\f6e8' => [
            'class' => 'fa fa-hat-wizard',
            'classes' => ['fa-hat-wizard'],
            'content' => '\f6e8',
            'text_de' => 'Hut (Zauberer)',
            'text_en' => 'Hat (Wizard)'
        ],
        '\f6ec' => [
            'class' => 'fa fa-hiking',
            'classes' => ['fa-hiking'],
            'content' => '\f6ec',
            'text_de' => 'Wandern',
            'text_en' => 'Hiking'
        ],
        '\f6ed' => [
            'class' => 'fa fa-hippo',
            'classes' => ['fa-hippo'],
            'content' => '\f6ed',
            'text_de' => 'Nilpferd',
            'text_en' => 'Hippopotamus'
        ],
        '\f6f0' => [
            'class' => 'fa fa-horse',
            'classes' => ['fa-horse'],
            'content' => '\f6f0',
            'text_de' => 'Pferd',
            'text_en' => 'Horse'
        ],
        '\f6f1' => [
            'class' => 'fa fa-house-damage',
            'classes' => ['fa-house-damage'],
            'content' => '\f6f1',
            'text_de' => 'Haus (Riss)',
            'text_en' => 'House (Cracked)'
        ],
        '\f6f2' => [
            // Ukrainian currency
            'class' => 'fa fa-hryvnia',
            'classes' => ['fa-hryvnia'],
            'content' => '\f6f2',
            'text_de' => 'Hryvnia',
            'text_en' => 'Hryvnia'
        ],
        '\f6fa' => [
            'class' => 'fa fa-mask',
            'classes' => ['fa-mask'],
            'content' => '\f6fa',
            'text_de' => 'Maske',
            'text_en' => 'Mask'
        ],
        '\f6fc' => [
            'class' => 'fa fa-mountain',
            'classes' => ['fa-mountain'],
            'content' => '\f6fc',
            'text_de' => 'Berg',
            'text_en' => 'Mountain'
        ],
        '\f6ff' => [
            'class' => 'fa fa-network-wired',
            'classes' => ['fa-network-wired'],
            'content' => '\f6ff',
            'text_de' => 'LAN / Netzwerk',
            'text_en' => 'LAN / Network'
        ],
        '\f700' => [
            'class' => 'fa fa-otter',
            'classes' => ['fa-otter'],
            'content' => '\f700',
            'text_de' => 'Otter',
            'text_en' => 'Otter'
        ],
        '\f704' => [
            'class' => 'fa fa-penny-arcade',
            'classes' => ['fa-penny-arcade'],
            'content' => '\f704'
        ],
        '\f70b' => [
            'class' => 'fa fa-ring',
            'classes' => ['fa-ring'],
            'content' => '\f70b',
            'text_de' => 'Ring',
            'text_en' => 'Ring'
        ],
        '\f70c' => [
            'class' => 'fa fa-running',
            'classes' => ['fa-running'],
            'content' => '\f70c',
            'text_de' => 'Laufen',
            'text_en' => 'Running'
        ],
        '\f70e' => [
            'class' => 'fa fa-scroll',
            'classes' => ['fa-scroll'],
            'content' => '\f70e',
            'text_de' => 'Schriftrolle',
            'text_en' => 'Scroll'
        ],
        '\f714' => [
            'class' => 'fa fa-skull-crossbones',
            'classes' => ['fa-skull-crossbones'],
            'content' => '\f714',
            'text_de' => 'Piraten',
            'text_en' => 'Pirates'
        ],
        '\f715' => [
            'class' => 'fa fa-slash',
            'classes' => ['fa-slash'],
            'content' => '\f715',
            'text_de' => 'Schrägstrich',
            'text_en' => 'Slash'
        ],
        '\f717' => [
            'class' => 'fa fa-spider',
            'classes' => ['fa-spider'],
            'content' => '\f717',
            'text_de' => 'Spinne',
            'text_en' => 'Spider'
        ],
        '\f71e' => [
            'class' => 'fa fa-toilet-paper',
            'classes' => ['fa-toilet-paper'],
            'content' => '\f71e',
            'context' => self::BATH,
            'text_de' => 'Klopapier',
            'text_en' => 'Toilet Paper'
        ],
        '\f722' => [
            'class' => 'fa fa-tractor',
            'classes' => ['fa-tractor'],
            'content' => '\f722',
            'text_de' => 'Traktor',
            'text_en' => 'Tractor'
        ],
        '\f728' => [
            'class' => 'fa fa-user-injured',
            'classes' => ['fa-user-injured'],
            'content' => '\f728',
            'text_de' => 'Person (verletzt)',
            'text_en' => 'User (Injured)'
        ],
        '\f729' => [
            'class' => 'fa fa-vr-cardboard',
            'classes' => ['fa-vr-cardboard'],
            'content' => '\f729',
            'text_de' => 'VR-Pappmaske',
            'text_en' => 'VR Cardboard Mask'
        ],
        '\f72e' => [
            'class' => 'fa fa-wind',
            'classes' => ['fa-wind'],
            'content' => '\f72e',
            'text_de' => 'Wind',
            'text_en' => 'Wind'
        ],
        '\f72f' => [
            'class' => 'fa fa-wine-bottle',
            'classes' => ['fa-wine-bottle'],
            'content' => '\f72f',
            'text_de' => 'Wein',
            'text_en' => 'Wine'
        ],
        '\f730' => [
            'class' => 'fab fa-wizards-of-the-coast',
            'classes' => ['fa-wizards-of-the-coast'],
            'content' => '\f730'
        ],
        '\f731' => [
            'class' => 'fab fa-think-peaks',
            'classes' => ['fa-think-peaks'],
            'content' => '\f731'
        ],
        '\f73b' => [
            'class' => 'fa fa-cloud-meatball',
            'classes' => ['fa-cloud-meatball'],
            'content' => '\f73b',
            'text_de' => 'Wetter: Regen, Fleichbällchen',
            'text_en' => 'Weather: Rain, Meatballs'
        ],
        '\f73c' => [
            'class' => 'fa fa-cloud-moon-rain',
            'classes' => ['fa-cloud-moon-rain'],
            'content' => '\f73c',
            'text_de' => 'Wetter: teilweise bewölkt, Regen, über Nacht',
            'text_en' => 'Weather: Partly Cloudy, Rain, Overnight'
        ],
        '\f73d' => [
            'class' => 'fa fa-cloud-rain',
            'classes' => ['fa-cloud-rain'],
            'content' => '\f73d',
            'text_de' => 'Wetter: Regen',
            'text_en' => 'Weather: Rain'
        ],
        '\f740' => [
            'class' => 'fa fa-cloud-showers-heavy',
            'classes' => ['fa-cloud-showers-heavy'],
            'content' => '\f740',
            'text_de' => 'Wetter: Regen, Stark',
            'text_en' => 'Weather: Rain, Heavy'
        ],
        '\f743' => [
            'class' => 'fa fa-cloud-sun-rain',
            'classes' => ['fa-cloud-sun-rain'],
            'content' => '\f743',
            'text_de' => 'Wetter: teilweise bewölkt, Regen',
            'text_en' => 'Weather: Partly Cloudy, Rain'
        ],
        '\f747' => [
            'class' => 'fa fa-democrat',
            'classes' => ['fa-democrat'],
            'content' => '\f747'
        ],
        '\f74d' => [
            'class' => 'fa fa-flag-usa',
            'classes' => ['fa-flag-usa'],
            'content' => '\f74d',
            'text_de' => 'Flagge (USA)',
            'text_en' => 'Flag (USA)'
        ],
        '\f753' => [
            'class' => 'fa fa-meteor',
            'classes' => ['fa-meteor'],
            'content' => '\f753',
            'text_de' => 'Meteor',
            'text_en' => 'Meteor'
        ],
        '\f756' => [
            'class' => 'fa fa-person-booth',
            'classes' => ['fa-person-booth'],
            'content' => '\f756',
            'text_de' => 'Wahlkabine / Wahllokal',
            'text_en' => 'Voting Booth / Voting Location'
        ],
        '\f75a' => [
            'class' => 'fa fa-poo-storm',
            'classes' => ['fa-poo-storm'],
            'content' => '\f75a'
        ],
        '\f75b' => [
            'class' => 'fa fa-rainbow',
            'classes' => ['fa-rainbow'],
            'content' => '\f75b',
            'text_de' => 'Regenbogen',
            'text_en' => 'Rainbow'
        ],
        '\f75d' => [
            'class' => 'fa fa-reacteurope',
            'classes' => ['fa-reacteurope'],
            'content' => '\f75d'
        ],
        '\f75e' => [
            'class' => 'fa fa-republican',
            'classes' => ['fa-republican'],
            'content' => '\f75e'
        ],
        '\f75f' => [
            'class' => 'fa fa-smog',
            'classes' => ['fa-smog'],
            'content' => '\f75f',
            'text_de' => 'Smog',
            'text_en' => 'Smog'
        ],
        '\f769' => [
            'class' => 'fa fa-temperature-high',
            'classes' => ['fa-temperature-high'],
            'content' => '\f769',
            'text_de' => 'Temperatur (hoch)',
            'text_en' => 'Temperature (High)'
        ],
        '\f76b' => [
            'class' => 'fa fa-temperature-low',
            'classes' => ['fa-temperature-low'],
            'content' => '\f76b',
            'text_de' => 'Temperatur (niedrig)',
            'text_en' => 'Temperature (Low)'
        ],
        '\f772' => [
            'class' => 'fa fa-vote-yea',
            'classes' => ['fa-vote-yea'],
            'content' => '\f772',
            'text_de' => 'Abstimmen / Wahlen',
            'text_en' => 'Elections / Vote'
        ],
        '\f773' => [
            'class' => 'fa fa-water',
            'classes' => ['fa-water'],
            'content' => '\f773',
            'text_de' => 'Wasser',
            'text_en' => 'Water'
        ],
        '\f77a' => [
            'class' => 'fab fa-artstation',
            'classes' => ['fa-artstation'],
            'content' => '\f77a',
            'text_de' => 'Logos: ArtStation',
            'text_en' => 'Logos: ArtStation'
        ],
        '\f77b' => [
            'class' => 'fab fa-atlassian',
            'classes' => ['fa-atlassian'],
            'content' => '\f77b',
            'text_de' => 'Logos: Atlassian',
            'text_en' => 'Logos: Atlassian'
        ],
        '\f77c' => [
            'class' => 'fa fa-baby',
            'classes' => ['fa-baby'],
            'content' => '\f77c',
            'text_de' => 'Baby',
            'text_en' => 'Baby'
        ],
        '\f77d' => [
            'class' => 'fa fa-baby-carriage',
            'classes' => ['fa-baby-carriage'],
            'content' => '\f77d',
            'text_de' => 'Kinderwagen',
            'text_en' => 'Baby Carriage'
        ],
        '\f780' => [
            'class' => 'fa fa-biohazard',
            'classes' => ['fa-biohazard'],
            'content' => '\f780',
            'text_de' => 'Biogefährdung',
            'text_en' => 'Biohazard'
        ],
        '\f781' => [
            'class' => 'fa fa-blog',
            'classes' => ['fa-blog'],
            'content' => '\f781',
            'text_de' => 'Blog',
            'text_en' => 'Blog'
        ],
        '\f783' => [
            'class' => 'fa fa-calendar-day',
            'classes' => ['fa-calendar-day'],
            'content' => '\f783',
            'text_de' => 'Kalender (Tag)',
            'text_en' => 'Calendar (Day)'
        ],
        '\f784' => [
            'class' => 'fa fa-calendar-week',
            'classes' => ['fa-calendar-week'],
            'content' => '\f784',
            'text_de' => 'Kalender (Woche)',
            'text_en' => 'Calendar (Week)'
        ],
        '\f785' => [
            'class' => 'fab fa-canadian-maple-leaf',
            'classes' => ['fa-canadian-maple-leaf'],
            'content' => '\f785'
        ],
        '\f786' => [
            'class' => 'fa fa-candy-cane',
            'classes' => ['fa-candy-cane'],
            'content' => '\f786',
            'text_de' => 'Zuckerstange',
            'text_en' => 'Candy Cane'
        ],
        '\f787' => [
            'class' => 'fa fa-carrot',
            'classes' => ['fa-carrot'],
            'content' => '\f787',
            'text_de' => 'Möhre',
            'text_en' => 'Carrot'
        ],
        '\f788' => [
            'class' => 'fa fa-cash-register',
            'classes' => ['fa-cash-register'],
            'content' => '\f788',
            'text_de' => 'Kasse',
            'text_en' => 'Cash Register'
        ],
        '\f789' => [
            'class' => 'fa fa-centos',
            'classes' => ['fa-centos'],
            'content' => '\f789'
        ],
        '\f78c' => [
            'class' => 'fa fa-compress-arrows-alt',
            'classes' => ['fa-compress-arrows-alt'],
            'content' => '\f78c',
            'text_de' => 'Verdichten / Zusammenziehen (Pfeile, 4x)',
            'text_en' => 'Compress / Contract (Arrows, 4x)'
        ],
        '\f78d' => [
            'class' => 'fab fa-confluence',
            'classes' => ['fa-confluence'],
            'content' => '\f78d',
            'text_de' => 'Logos: Confluence',
            'text_en' => 'Logos: Confluence'
        ],
        '\f790' => [
            'class' => 'fab fa-dhl',
            'classes' => ['fa-dhl'],
            'content' => '\f790'
        ],
        '\f791' => [
            'class' => 'fab fa-diaspora',
            'classes' => ['fa-diaspora'],
            'content' => '\f791'
        ],
        '\f793' => [
            'class' => 'fa fa-dumpster',
            'classes' => ['fa-dumpster'],
            'content' => '\f793',
            'text_de' => 'Müllcontainer',
            'text_en' => 'Dumpster'
        ],
        '\f794' => [
            'class' => 'fa fa-dumpster-fire',
            'classes' => ['fa-dumpster-fire'],
            'content' => '\f794',
            'text_de' => 'Müllcontainer (lödernd)',
            'text_en' => 'Dumpster Fire'
        ],
        '\f796' => [
            'class' => 'fa fa-ethernet',
            'classes' => ['fa-ethernet'],
            'content' => '\f796',
            'text_de' => 'Ethernet',
            'text_en' => 'Ethernet'
        ],
        '\f797' => [
            'class' => 'fab fa-fedex',
            'classes' => ['fa-fedex'],
            'content' => '\f797'
        ],
        '\f798' => [
            'class' => 'fab fa-fedora',
            'classes' => ['fa-fedora'],
            'content' => '\f798'
        ],
        '\f799' => [
            // Collaborative design platform
            'class' => 'fab fa-figma',
            'classes' => ['fa-figma'],
            'content' => '\f799',
            'text_de' => 'Logos: Figma',
            'text_en' => 'Logos: Figma'
        ],
        '\f79c' => [
            'class' => 'fa fa-gifts',
            'classes' => ['fa-gifts'],
            'content' => '\f79c',
            'text_de' => 'Geschenke',
            'text_en' => 'Gifts'
        ],
        '\f79f' => [
            'class' => 'fa fa-glass-cheers',
            'classes' => ['fa-glass-cheers'],
            'content' => '\f79f',
            'text_de' => 'Glas (Prost)',
            'text_en' => 'Glass (Cheers)'
        ],
        '\f7a0' => [
            'class' => 'fa fa-glass-whiskey',
            'classes' => ['fa-glass-whiskey'],
            'content' => '\f7a0',
            'text_de' => 'Glas (Whiskey)',
            'text_en' => 'Glass (Whiskey)'
        ],
        '\f7a2' => [
            'class' => 'fa fa-globe-europe',
            'classes' => ['fa-globe-europe'],
            'content' => '\f7a2',
            'text_de' => 'Globus (Europa)',
            'text_en' => 'Globe (Europe)'
        ],
        '\f7a4' => [
            'class' => 'fa fa-grip-lines',
            'classes' => ['fa-grip-lines'],
            'content' => '\f7a4',
            'text_de' => 'Striche (Waagerecht)',
            'text_en' => 'Lines (Senkrecht)'
        ],
        '\f7a5' => [
            'class' => 'fa fa-grip-lines-vertical',
            'classes' => ['fa-grip-lines-vertical'],
            'content' => '\f7a5',
            'text_de' => 'Striche (Senkrecht)',
            'text_en' => 'Lines (Vertical)'
        ],
        '\f7a6' => [
            'class' => 'fa fa-guitar',
            'classes' => ['fa-guitar'],
            'content' => '\f7a6',
            'text_de' => 'Guitarre',
            'text_en' => 'Guitar'
        ],
        '\f7a9' => [
            'class' => 'fa fa-heart-broken',
            'classes' => ['fa-heart-broken'],
            'content' => '\f7a9',
            'context' => self::EXPRESSIONS_GESTURES,
            'text_de' => 'gebrochenes Herz',
            'text_en' => 'Broken Heart'
        ],
        '\f7aa' => [
            'class' => 'fa fa-holly-berry',
            'classes' => ['fa-holly-berry'],
            'content' => '\f7aa',
            'text_de' => 'Stechpalme',
            'text_en' => 'Holly'
        ],
        '\f7ab' => [
            'class' => 'fa fa-horse-head',
            'classes' => ['fa-horse-head'],
            'content' => '\f7ab',
            'text_de' => 'Pferd (Kopf)',
            'text_en' => 'Horse (Head)'
        ],
        '\f7ad' => [
            'class' => 'fa fa-icicles',
            'classes' => ['fa-icicles'],
            'content' => '\f7ad',
            'text_de' => 'Eiszapfen',
            'text_en' => 'Icicles'
        ],
        '\f7ae' => [
            'class' => 'fa fa-igloo',
            'classes' => ['fa-igloo'],
            'content' => '\f7ae',
            'text_de' => 'Igloo',
            'text_en' => 'Igloo'
        ],
        '\f7af' => [
            // CRMish
            'class' => 'fab fa-intercom',
            'classes' => ['fa-intercom'],
            'content' => '\f7af',
            'text_de' => 'Logos: INTERCOM',
            'text_en' => 'Logos: INTERCOM'
        ],
        '\f7b0' => [
            // Online whiteboard
            'class' => 'fab fa-invision',
            'classes' => ['fa-invision'],
            'content' => '\f7b0',
            'text_de' => 'Logos: invision',
            'text_en' => 'Logos: invision'
        ],
        '\f7b1' => [
            // Tickets
            'class' => 'fab fa-jira',
            'classes' => ['fa-jira'],
            'content' => '\f7b1',
            'text_de' => 'Logos: Jira',
            'text_en' => 'Logos: Jira'
        ],
        '\f7b3' => [
            // Research platform
            'class' => 'fab fa-mendeley',
            'classes' => ['fa-mendeley'],
            'content' => '\f7b3',
            'text_de' => 'Logos: Mendeley',
            'text_en' => 'Logos: Mendeley'
        ],
        '\f7b5' => [
            'class' => 'fa fa-mitten',
            'classes' => ['fa-mitten'],
            'content' => '\f7b5',
            'text_de' => 'Fäustling',
            'text_en' => 'Mitten'
        ],
        '\f7b6' => [
            'class' => 'fa fa-mug-hot',
            'classes' => ['fa-mug-hot'],
            'content' => '\f7b6',
            'text_de' => 'Tasse (heiß)',
            'text_en' => 'Mug (hot)'
        ],
        '\f7b9' => [
            'class' => 'fa fa-radiation',
            'classes' => ['fa-radiation'],
            'content' => '\f7b9',
            'text_de' => 'Strahlung',
            'text_en' => 'Radiation'
        ],
        '\f7ba' => [
            'class' => 'fa fa-radiation-alt',
            'classes' => ['fa-radiation-alt'],
            'content' => '\f7ba',
            'text_de' => 'Strahlung (Kreis)',
            'text_en' => 'Radiation (Circle)'
        ],
        '\f7bb' => [
            'class' => 'fab fa-raspberry-pi',
            'classes' => ['fa-raspberry-pi'],
            'content' => '\f7bb'
        ],
        '\f7bc' => [
            'class' => 'fab fa-redhat',
            'classes' => ['fa-redhat'],
            'content' => '\f7bc'
        ],
        '\f7bd' => [
            'class' => 'fa fa-restroom',
            'classes' => ['fa-restroom'],
            'content' => '\f7bd',
            'text_de' => 'WC',
            'text_en' => 'Restroom'
        ],
        '\f7bf' => [
            'class' => 'fa fa-satellite',
            'classes' => ['fa-satellite'],
            'content' => '\f7bf',
            'text_de' => 'Satellit',
            'text_en' => 'Satellite'
        ],
        '\f7c0' => [
            'class' => 'fa fa-satellite-dish',
            'classes' => ['fa-satellite-dish'],
            'content' => '\f7c0',
            'text_de' => 'Satellitenschüssel',
            'text_en' => 'Satellite Dish'
        ],
        '\f7c2' => [
            'class' => 'fa fa-sd-card',
            'classes' => ['fa-sd-card'],
            'content' => '\f7c2',
            'text_de' => 'SD-Card',
            'text_en' => 'SD-Card'
        ],
        '\f7c4' => [
            'class' => 'fa fa-sim-card',
            'classes' => ['fa-sim-card'],
            'content' => '\f7c4',
            'text_de' => 'SIM-Card',
            'text_en' => 'SIM-Card'
        ],
        '\f7c5' => [
            'class' => 'fa fa-skating',
            'classes' => ['fa-skating'],
            'content' => '\f7c5',
            'text_de' => 'Schlittschuhlaufen',
            'text_en' => 'Ice Skating'
        ],
        '\f7c6' => [
            'class' => 'fab fa-sketch',
            'classes' => ['fa-sketch'],
            'content' => '\f7c6'
        ],
        '\f7c9' => [
            'class' => 'fa fa-skiing',
            'classes' => ['fa-skiing'],
            'content' => '\f7c9',
            'context' => self::ACTIVITIES,
            'text_de' => 'Skifahren',
            'text_en' => 'Skiing'
        ],
        '\f7ca' => [
            'class' => 'fa fa-skiing-nordic',
            'classes' => ['fa-skiing-nordic'],
            'content' => '\f7ca',
            'context' => self::ACTIVITIES,
            'text_de' => 'Langlauf',
            'text_en' => 'Cross Country Skiing'
        ],
        '\f7cc' => [
            'class' => 'fa fa-sleigh',
            'classes' => ['fa-sleigh'],
            'content' => '\f7cc',
            'context' => self::VEHICLES,
            'text_de' => 'Schlitten',
            'text_en' => 'Sleigh'
        ],
        '\f7cd' => [
            'class' => 'fa fa-sms',
            'classes' => ['fa-sms'],
            'content' => '\f7cd',
            'text_de' => 'Kommentar (SMS)',
            'text_en' => 'Comment (SMS)'
        ],
        '\f7ce' => [
            'class' => 'fa fa-snowboarding',
            'classes' => ['fa-snowboarding'],
            'content' => '\f7ce',
            'context' => self::ACTIVITIES,
            'text_de' => 'Snowboarden',
            'text_en' => 'Snowboarding'
        ],
        '\f7d0' => [
            'class' => 'fa fa-snowman',
            'classes' => ['fa-snowman'],
            'content' => '\f7d0',
            'text_de' => 'Schneemann',
            'text_en' => 'Snowman'
        ],
        '\f7d2' => [
            'class' => 'fa fa-snowplow',
            'classes' => ['fa-snowplow'],
            'content' => '\f7d2',
            'text_de' => 'Schneepflug',
            'text_en' => 'Snow Plow'
        ],
        '\f7d3' => [
            'class' => 'fab fa-sourcetree',
            'classes' => ['fa-sourcetree'],
            'content' => '\f7d3'
        ],
        '\f7d6' => [
            'class' => 'fab fa-suse',
            'classes' => ['fa-suse'],
            'content' => '\f7d6'
        ],
        '\f7d7' => [
            'class' => 'fa fa-tenge',
            'classes' => ['fa-tenge'],
            'content' => '\f7d7',
            'context' => self::FINANCE,
            'text_de' => 'Tenge',
            'text_en' => 'Tenge'
        ],
        '\f7d8' => [
            'class' => 'fa fa-toilet',
            'classes' => ['fa-toilet'],
            'content' => '\f7d8',
            'context' => self::BATH,
            'text_de' => 'Toilette',
            'text_en' => 'Toilet'
        ],
        '\f7d9' => [
            'class' => 'fa fa-tools',
            'classes' => ['fa-tools'],
            'content' => '\f7d9',
            'context' => self::PRIMARY,
            'text_de' => 'Werkzeug',
            'text_en' => 'Tools'
        ],
        '\f7da' => [
            'class' => 'fa fa-tram',
            'classes' => ['fa-tram'],
            'content' => '\f7da',
            'context' => self::VEHICLES,
            'text_de' => 'Cable Car',
            'text_en' => 'Seilbahn'
        ],
        '\f7df' => [
            'class' => 'fab fa-ubuntu',
            'classes' => ['fa-ubuntu'],
            'content' => '\f7df',
            // ubuntu.com linux derivitive => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f7e0' => [
            'class' => 'fab fa-ups',
            'classes' => ['fa-ups'],
            'content' => '\f7e0',
            // ups.com parcel delivery => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f7e1' => [
            'class' => 'fab fa-usps',
            'classes' => ['fa-usps'],
            'content' => '\f7e1',
            // usps.com parcel delivery => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f7e3' => [
            'class' => 'fab fa-yarn',
            'classes' => ['fa-yarn'],
            'content' => '\f7e3'
        ],
        '\f7e4' => [
            'class' => 'fa fa-fire-alt',
            'classes' => ['fa-fire-alt'],
            'content' => '\f7e4',
            'text_de' => 'Flamme',
            'text_en' => 'Flame'
        ],
        '\f7e5' => [
            'class' => 'fa fa-bacon',
            'classes' => ['fa-bacon'],
            'content' => '\f7e5',
            'context' => self::CONSUMABLES,
            'text_de' => 'Speck',
            'text_en' => 'Bacon'
        ],
        '\f7e6' => [
            'class' => 'fa fa-book-medical',
            'classes' => ['fa-book-medical'],
            'content' => '\f7e6',
            'text_de' => 'Buch (medizinisch)',
            'text_en' => 'Book (Medical)'
        ],
        '\f7ec' => [
            'class' => 'fa fa-bread-slice',
            'classes' => ['fa-bread-slice'],
            'content' => '\f7ec',
            'context' => self::CONSUMABLES,
            'text_de' => 'Brot, Scheibe',
            'text_en' => 'Bread, Slice'
        ],
        '\f7ef' => [
            'class' => 'fa fa-cheese',
            'classes' => ['fa-cheese'],
            'content' => '\f7ef',
            'context' => self::CONSUMABLES,
            'text_de' => 'Käse',
            'text_en' => 'Cheese'
        ],
        '\f7f2' => [
            'class' => 'fa fa-clinic-medical',
            'classes' => ['fa-clinic-medical'],
            'content' => '\f7f2',
            'text_de' => 'Artztpraxis',
            'text_en' => 'Clinic'
        ],
        '\f7f5' => [
            'class' => 'fa fa-comment-medical',
            'classes' => ['fa-comment-medical'],
            'content' => '\f7f5',
            'text_de' => 'Kommentar (medizinisch)',
            'text_en' => 'Comment (Medical)'
        ],
        '\f7f7' => [
            'class' => 'fa fa-crutch',
            'classes' => ['fa-crutch'],
            'content' => '\f7f7',
            'text_de' => 'Krücke',
            'text_en' => 'Crutch'
        ],
        '\f7fa' => [
            'class' => 'fa fa-disease',
            'classes' => ['fa-disease'],
            'content' => '\f7fa',
            'text_de' => 'Krankheit',
            'text_en' => 'Disease'
        ],
        '\f7fb' => [
            'class' => 'fa fa-egg',
            'classes' => ['fa-egg'],
            'content' => '\f7fb',
            'context' => self::CONSUMABLES,
            'text_de' => 'Ei',
            'text_en' => 'Egg'
        ],
        '\f805' => [
            'class' => 'fa fa-hamburger',
            'classes' => ['fa-hamburger'],
            'content' => '\f805',
            'context' => self::CONSUMABLES,
            'text_de' => 'Hamburger',
            'text_en' => 'Hamburger'
        ],
        '\f806' => [
            'class' => 'fa fa-hand-middle-finger',
            'classes' => ['fa-hand-middle-finger'],
            'content' => '\f806',
            'text_de' => 'Stinkefinger',
            'text_en' => 'Bird (Flipped)'
        ],
        '\f807' => [
            'class' => 'fa fa-hard-hat',
            'classes' => ['fa-hard-hat'],
            'content' => '\f807',
            'text_de' => 'Schutzhelm',
            'text_en' => 'Hard Hat'
        ],
        '\f80d' => [
            'class' => 'fa fa-hospital-user',
            'classes' => ['fa-hospital-user'],
            'content' => '\f80d',
            'text_de' => 'Krankenhaus Besucher',
            'text_en' => 'Hospital Visitor'
        ],
        '\f80f' => [
            'class' => 'fa fa-hotdog',
            'classes' => ['fa-hotdog'],
            'content' => '\f80f',
            'context' => self::CONSUMABLES,
            'text_de' => 'Würstchen',
            'text_en' => 'Hotdog'
        ],
        '\f810' => [
            'class' => 'fa fa-ice-cream',
            'classes' => ['fa-ice-cream'],
            'content' => '\f810',
            'context' => self::CONSUMABLES,
            'text_de' => 'Waffeleis',
            'text_en' => 'Ice Cream Cone'
        ],
        '\f812' => [
            'class' => 'fa fa-laptop-medical',
            'classes' => ['fa-laptop-medical'],
            'content' => '\f812',
            'context' => self::DEVICES,
            'text_de' => 'Laptop (medizinisch)',
            'text_en' => 'Laptop (Medical)'
        ],
        '\f815' => [
            'class' => 'fa fa-pager',
            'classes' => ['fa-pager'],
            'content' => '\f815',
            'context' => self::DEVICES,
            'text_de' => 'Piepser',
            'text_en' => 'Pager'
        ],
        '\f816' => [
            'class' => 'fa fa-pepper-hot',
            'classes' => ['fa-pepper-hot'],
            'content' => '\f816',
            'context' => self::CONSUMABLES,
            'text_de' => 'Chili',
            'text_en' => 'Chili'
        ],
        '\f818' => [
            'class' => 'fa fa-pizza-slice',
            'classes' => ['fa-pizza-slice'],
            'content' => '\f818',
            'context' => self::CONSUMABLES,
            'text_de' => 'Pizza, Stück',
            'text_en' => 'Pizza, Slice'
        ],
        '\f829' => [
            'class' => 'fa fa-trash-restore',
            'classes' => ['fa-trash-restore'],
            'content' => '\f829',
            'text_de' => 'Papierkorb (Wiederherstellen)',
            'text_en' => 'Trash (Restore)'
        ],
        '\f82a' => [
            // Bulky
            'class' => 'fa fa-trash-restore-alt',
            'classes' => ['fa-trash-restore-alt'],
            'content' => '\f82a'
        ],
        '\f82f' => [
            'class' => 'fa fa-user-nurse',
            'classes' => ['fa-user-nurse'],
            'content' => '\f82f',
            'context' => self::EXPRESSIONS_GESTURES,
            'text_de' => 'Krankenschwester',
            'text_en' => 'Nurse'
        ],
        '\f834' => [
            'class' => 'fab fa-airbnb',
            'classes' => ['fa-airbnb'],
            'content' => '\f834',
            // airbnb.com lodgings => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f835' => [
            'class' => 'fab fa-battle-net',
            'classes' => ['fa-battle-net'],
            'content' => '\f835',
            // battle.net game shop => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f836' => [
            'class' => 'fab fa-bootstrap',
            'classes' => ['fa-bootstrap'],
            'content' => '\f836',
            // getbootstrap.com js framework => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f837' => [
            'class' => 'fab fa-buffer',
            'classes' => ['fa-buffer'],
            'content' => '\f837',
            // buffer.com social media marketing toolkit => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f838' => [
            'class' => 'fab fa-chromecast',
            'classes' => ['fa-chromecast'],
            'content' => '\f838',
            // google wifi tv software => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f839' => [
            'class' => 'fab fa-evernote',
            'classes' => ['fa-evernote'],
            'content' => '\f839',
            'context' => self::COMMUNICATION,
            'text_de' => 'Evernote',
            'text_en' => 'Evernote'
        ],
        '\f83a' => [
            'class' => 'fab fa-itch-io',
            'classes' => ['fa-itch-io'],
            'content' => '\f83a',
            // itch.io game repository => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f83b' => [
            'class' => 'fab fa-salesforce',
            'classes' => ['fa-salesforce'],
            'content' => '\f83b',
            'context' => self::MANAGEMENT,
            'text_de' => 'salesforce',
            'text_en' => 'salesforce'
        ],
        '\f83c' => [
            'class' => 'fab fa-speaker-deck',
            'classes' => ['fa-speaker-deck'],
            'content' => '\f83c',
            'context' => self::MEDIA,
            'text_de' => 'Speaker Deck',
            'text_en' => 'Speaker Deck'
        ],
        '\f83d' => [
            'class' => 'fab fa-symfony',
            'classes' => ['fa-symfony'],
            'content' => '\f83d',
            // symfony.com php development framework => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f83e' => [
            'class' => 'fa fa-wave-square',
            'classes' => ['fa-wave-square'],
            'content' => '\f83e',
            'context' => self::SCIENCE,
            'text_de' => 'Rechteckschwingung',
            'text_en' => 'Square Wave'
        ],
        '\f83f' => [
            'class' => 'fab fa-waze',
            'classes' => ['fa-waze'],
            'content' => '\f83f',
            // waze.com navigation software => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f840' => [
            'class' => 'fab fa-yammer',
            'classes' => ['fa-yammer'],
            'content' => '\f840',
            'context' => self::COMMUNICATION,
            'text_de' => 'Yammer',
            'text_en' => 'Yammer'
        ],
        '\f841' => [
            'class' => 'fab fa-git-alt',
            'classes' => ['fa-git-alt'],
            'content' => '\f841',
            // git-scm.com scm platform => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f842' => [
            'class' => 'fab fa-stackpath',
            'classes' => ['fa-stackpath'],
            'content' => '\f842',
            // stackpath.com edge computing platform => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f84a' => [
            'class' => 'fa fa-biking',
            'classes' => ['fa-biking'],
            'content' => '\f84a',
            'context' => self::ACTIVITIES,
            'text_de' => 'Radfahren',
            'text_en' => 'Biking'
        ],
        '\f84c' => [
            'class' => 'fa fa-border-all',
            'classes' => ['fa-border-all'],
            'content' => '\f84c',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Border-Stil: voll',
            'text_en' => 'Border Style: Full'
        ],
        '\f850' => [
            'class' => 'fa fa-border-none',
            'classes' => ['fa-border-none'],
            'content' => '\f850',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Border-Stil: keine',
            'text_en' => 'Border Style: None'
        ],
        '\f853' => [
            'class' => 'fa fa-border-style',
            'classes' => ['fa-border-style'],
            'content' => '\f853',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Border-Stil: außen',
            'text_en' => 'Border Style: Outer'
        ],
        '\f863' => [
            'class' => 'fa fa-fan',
            'classes' => ['fa-fan'],
            'content' => '\f863',
            'context' => self::SECONDARY,
            'text_de' => 'Windrad',
            'text_en' => 'Pinwheel'
        ],
        '\f86d' => [
            'class' => 'fa fa-icons',
            'classes' => ['fa-icons'],
            'content' => '\f86d',
            'context' => self::PRIMARY,
            'text_de' => 'Symbole',
            'text_en' => 'Icons'
        ],
        '\f879' => [
            'class' => 'fa fa-phone-alt',
            'classes' => ['fa-phone-alt'],
            'content' => '\f879',
            'context' => self::DEVICES,
            'text_de' => 'Telefon, links-gerichtet',
            'text_en' => 'Telephone, Left-Facing'
        ],
        '\f87b' => [
            'class' => 'fa fa-phone-square-alt',
            'classes' => ['fa-phone-square-alt'],
            'content' => '\f87b',
            'context' => self::DEVICES,
            'text_de' => 'Telefon, links-gerichtet, im Quadrat',
            'text_en' => 'Telephone, Left-Facing, on a Square'
        ],
        '\f87c' => [
            'class' => 'fa fa-photo-video',
            'classes' => ['fa-photo-video'],
            'content' => '\f87c',
            'context' => self::PRIMARY,
            'text_de' => 'Medien',
            'text_en' => 'Media'
        ],
        '\f87d' => [
            'class' => 'fa fa-remove-format',
            'classes' => ['fa-remove-format'],
            'content' => '\f87d',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Formattierung mit Schrägstrich',
            'text_en' => 'Formatting with Slash'
        ],
        '\f881' => [
            'class' => 'fa fa-sort-alpha-down-alt',
            'classes' => ['fa-sort-alpha-down-alt'],
            'content' => '\f881',
            'context' => self::SORTS,
            'text_de' => 'Sort, alphabetisch, absteigend, verkehrt',
            'text_en' => 'Sort, Alphabetical, Descending, Reversed'
        ],
        '\f882' => [
            'class' => 'fa fa-sort-alpha-up-alt',
            'classes' => ['fa-sort-alpha-up-alt'],
            'content' => '\f882',
            'context' => self::SORTS,
            'text_de' => 'Sort, alphabetisch, aufsteigend, verkehrt',
            'text_en' => 'Sort, Alphabetical, Ascending, Reversed'
        ],
        '\f884' => [
            'class' => 'fa fa-sort-amount-down-alt',
            'classes' => ['fa-sort-amount-down-alt'],
            'content' => '\f884',
            'context' => self::SORTS,
            'text_de' => 'Sort, nach Größe, aufsteigend',
            'text_en' => 'Sort, Amount, Ascending'
        ],
        '\f885' => [
            'class' => 'fa fa-sort-amount-up-alt',
            'classes' => ['fa-sort-amount-up-alt'],
            'content' => '\f885',
            'context' => self::SORTS,
            'text_de' => 'Sort, nach Größe, absteigend',
            'text_en' => 'Sort, Amount, Descending'
        ],
        '\f886' => [
            'class' => 'fa fa-sort-numeric-down-alt',
            'classes' => ['fa-sort-numeric-down-alt'],
            'content' => '\f886',
            'context' => self::SORTS,
            'text_de' => 'Sort, nummerisch, absteigend, verkehrt',
            'text_en' => 'Sort, Numerical, Descending, Reverse'
        ],
        '\f887' => [
            'class' => 'fa fa-sort-numeric-up-alt',
            'classes' => ['fa-sort-numeric-up-alt'],
            'content' => '\f887',
            'context' => self::SORTS,
            'text_de' => 'Sort, nummerisch, aufsteigend, verkehrt',
            'text_en' => 'Numerical, Ascending, Reverse'
        ],
        '\f891' => [
            'class' => 'fa fa-spell-check',
            'classes' => ['fa-spell-check'],
            'content' => '\f891',
            'context' => self::WP_FUNCTIONS,
            'text_de' => 'Rechtschreibprüfung',
            'text_en' => 'Spell Check'
        ],
        '\f897' => [
            'class' => 'fa fa-voicemail',
            'classes' => ['fa-voicemail'],
            'content' => '\f897',
            'context' => self::PRIMARY,
            'text_de' => 'Sprachnachrichten',
            'text_en' => 'Voicemail'
        ],
        '\f89e' => [
            'class' => 'fa fa-cotton-bureau',
            'classes' => ['fa-cotton-bureau'],
            'content' => '\f89e',
            // cottonbureau.com apparel => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f8a6' => [
            'class' => 'fab fa-buy-n-large',
            'classes' => ['fa-buy-n-large'],
            'content' => '\f8a6',
            // fictitious walmart knock-off => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f8c0' => [
            'class' => 'fa fa-hat-cowboy',
            'classes' => ['fa-hat-cowboy'],
            'content' => '\f8c0',
            'context' => self::APPAREL,
            'text_de' => 'Cowboy-Hut',
            'text_en' => 'Cowboy Hat'
        ],
        '\f8c1' => [
            'class' => 'fa fa-hat-cowboy-side',
            'classes' => ['fa-hat-cowboy-side'],
            'content' => '\f8c1',
            'context' => self::APPAREL,
            'text_de' => 'Cowboy-Hut, seitlich',
            'text_en' => 'Cowboy Hat, Sidelong'
        ],
        '\f8ca' => [
            'class' => 'fab fa-mdb',
            'classes' => ['fa-mdb'],
            'content' => '\f8ca',
            // mdbootstrap.com ui kits => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f8cc' => [
            'class' => 'fa fa-mouse',
            'classes' => ['fa-mouse'],
            'content' => '\f8cc',
            'context' => self::DEVICES,
            'text_de' => 'Computer-Maus',
            'text_en' => 'Computer Mouse'
        ],
        '\f8d2' => [
            'class' => 'fab fa-orcid',
            'classes' => ['fa-orcid'],
            'content' => '\f8d2',
            'context' => self::RESEARCH,
            'text_de' => 'ORCID',
            'text_en' => 'ORCID'
        ],
        '\f8d9' => [
            'class' => 'fa fa-record-vinyl',
            'classes' => ['fa-record-vinyl'],
            'content' => '\f8d9',
            'context' => self::SECONDARY,
            'text_de' => 'Schallplatte',
            'text_en' => 'Record'
        ],
        '\f8e1' => [
            'class' => 'fab fa-swift',
            'classes' => ['fa-swift'],
            'content' => '\f8e1',
            // swift.org programming language => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f8e8' => [
            'class' => 'fab fa-umbraco',
            'classes' => ['fa-umbraco'],
            'content' => '\f8e8',
            // umbraco.com cms => irrelevant
            'context' => self::SUPPRESSED
        ],
        '\f8ff' => [
            'class' => 'fa fa-caravan',
            'classes' => ['fa-caravan'],
            'content' => '\f8ff',
            'context' => self::VEHICLES,
            'text_de' => 'Wohnwagen',
            'text_en' => 'Camper'
        ],
    ];

    /**
     * Checks the stylesheet responsible for content assignment for new content.
     *
     * @return void
     */
    public static function checkIcons(): void
    {
        $path  = JPATH_ROOT . '/media/system/css/joomla-fontawesome.css';
        $file  = fopen($path, 'r');
        $icons = self::ICONS;

        while (($line = fgets($file)) !== false) {

            // Look for a relevant selector
            if (!preg_match('/(\.([\w-]+):before) {/', $line, $matches) or !$selector = $matches[2]) {
                continue;
            }

            // Look for a content assignment
            if (!$line = fgets($file) or !preg_match('/^  content: "(\\\\[a-f0-9]+)";$/', $line, $matches)) {
                continue;
            }

            $content = $matches[1];

            if (!isset($icons[$content])) {
                Application::message("New icon available: $content => $selector");
            } elseif (!in_array($selector, $icons[$content]['classes'])) {
                Application::message("Selector $selector missing from icon $content.");
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getAll(): array
    {
        return self::ICONS;
    }

    /**
     * @inheritDoc
     */
    public static function getOptions(): array
    {
        $contextualized = [];
        $options        = [];
        $nameKey        = 'text_' . Application::getTag();

        foreach (self::ICONS as $content => $icon) {

            if (!isset($icon['context'])) {
                echo "<pre>$content without context.</pre>";
                continue;
            }

            if ($icon['context'] === self::SUPPRESSED) {
                continue;
            }

            if (!$text = $icon[$nameKey] ?? '') {
                echo "<pre>$content without text.</pre>";
            }

            if (empty($contextualized[$icon['context']])) {
                $contextualized[$icon['context']] = [];
            }

            $contextualized[$icon['context']][$text] = $icon;
        }

        ksort($contextualized);


        foreach ($contextualized as $icons) {
            ksort($icons);

            foreach ($icons as $text => $icon) {
                $options[$text] = (object) ['text' => $text, 'value' => $icon['class']];
            }
        }

        return $options;
    }
}