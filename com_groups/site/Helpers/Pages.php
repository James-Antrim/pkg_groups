<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use Joomla\CMS\{Language\LanguageHelper, Layout\LayoutHelper, Router\Route, Uri\Uri};
use stdClass;
use THM\Groups\Adapters\{Application, Database as DB, HTML, Text};
use THM\Groups\Tables\{Content as CTable, Pages as PTable};

/**
 * Class for the handling of content related information.
 */
class Pages
{
    use Persistent;

    public const FEATURED = 1, UNFEATURED = 0;

    public const FEATURED_STATES = [
        self::FEATURED   => [
            'class'  => 'fa fa-check',
            'column' => 'featured',
            'task'   => 'unfeature',
            'tip'    => 'TOGGLE_TIP_FEATURED'
        ],
        self::UNFEATURED => [
            'class'  => 'fa fa-times',
            'column' => 'featured',
            'task'   => 'feature',
            'tip'    => 'TOGGLE_TIP_UNFEATURED'
        ]
    ];

    public const ARCHIVED = 2, PUBLISHED = 1, TRASHED = -2, HIDDEN = 0;

    public const STATES = [
        self::ARCHIVED  => [
            'class'  => 'fa fa-archive',
            'column' => 'state',
            'task'   => 'hide',
            'tip'    => 'TOGGLE_TIP_ARCHIVED'
        ],
        self::PUBLISHED => [
            'class'  => 'fa fa-check',
            'column' => 'state',
            'task'   => 'hide',
            'tip'    => 'TOGGLE_TIP_PUBLISHED'
        ],
        self::TRASHED   => [
            'class'  => 'fa fa-trash',
            'column' => 'state',
            'task'   => 'publish',
            'tip'    => 'TOGGLE_TIP_TRASHED'
        ],
        self::HIDDEN    => [
            'class'  => 'fa fa-times',
            'column' => 'state',
            'task'   => 'publish',
            'tip'    => 'TOGGLE_TIP_HIDDEN'
        ],
    ];

    public const CHECKED_IN = 0, CHECKED_OUT = 1;

    public const CHECKED_STATES = [
        self::CHECKED_IN  => ['class' => '', 'column' => '', 'task' => '', 'tip' => ''],
        self::CHECKED_OUT => [
            'class'  => 'fa fa-lock',
            'column' => 'checked_out',
            'task'   => 'checkin',
            'tip'    => 'TOGGLE_TIP_CHECKED_OUT'
        ]
    ];

    private const URL = 1, PATH = 2, QUERY = 3;

    /**
     * Gets the content alias.
     *
     * @param   int  $contentID
     *
     * @return string
     */
    public static function alias(int $contentID): string
    {
        $table = new CTable();
        if ($table->load($contentID)) {
            return $table->alias;
        }
        return '';
    }

    /**
     * Get references to the localizations of the specified content.
     *
     * @param   int  $contentID
     *
     * @return  array
     */
    public static function localizations(int $contentID): array
    {
        // To avoid doing duplicate database queries.
        static $associations = [];

        // Multilanguage association array key. If the key is already in the array we don't need to run the query again, just return it.
        $key = md5(serialize(['content', $contentID]));

        if (!empty($associations[$key])) {
            return $associations[$key];
        }

        $associations[$key] = [];

        $query = DB::query();
        $query->select(DB::qn(['c2.language', 'c2.id']))
            ->from(DB::qn('#__content', 'c'))
            ->innerJoin(DB::qn('#__associations', 'a'), DB::qc('a.id', 'c.id'))
            ->innerJoin(DB::qn('#__associations', 'a2'), DB::qc('a.key', 'a2.key'))
            ->innerJoin(DB::qn('#__content', 'c2'), DB::qn('a2.id') . ' = ' . DB::qn('c2.id'))
            ->where(DB::qcs([['a.context', 'com_content.item', '=', true], ['c.id', $contentID]]));
        DB::set($query);

        foreach (DB::objects('language') as $tag => $item) {
            // No identity references
            if ((int) $item->id !== $contentID) {
                $associations[$key][$tag] = $item;
            }
        }

        return $associations[$key];
    }

    /**
     * Gets the id of the category associated with the content.
     *
     * @param   int  $contentID
     *
     * @return int
     */
    public static function categoryID(int $contentID): int
    {
        $table = new CTable();
        if ($table->load($contentID)) {
            return $table->catid;
        }
        return 0;
    }

    /**
     * Gets the id of the content with the given alias.
     *
     * @param   string  $alias   the parsed alias
     * @param   int     $userID  the parsed user id
     *
     * @return int
     */
    public static function id(string $alias, int $userID): int
    {
        $query = DB::query();
        $query->select(DB::qn('c.id'))
            ->from(DB::qn('#__content AS c'))
            ->innerJoin(DB::qn('#__groups_pages', 'p'), DB::qc('p.content', 'c.id'))
            ->where(DB::qcs([['c.alias', $alias, '=', true], ['p.userID', $userID]]));
        DB::set($query);
        return DB::integer();
    }

    /**
     * Removes profile parameter stubs from content.
     *
     * @param   string  $html
     *
     * @return void
     */
    public static function removeProfileParameters(string &$html): void
    {
        $pattern = '/{(thm[_]?)?groups[A-Za-z0-9]*\s.*?}/';
        $html    = preg_replace($pattern, "", $html);
    }

    /**
     * Render the list of associated items
     *
     * @param   stdClass  $item  the content/page resource to generate the language display for
     *
     * @return  string
     */
    public static function languageDisplay(stdClass $item): string
    {
        $contentID = $item->id;
        $html      = '<div class="small" style="display:inline-block">';

        $title = $item->language_title ? htmlspecialchars($item->language_title, ENT_COMPAT, 'UTF-8') : '';

        if ($item->language === '*') {
            $html .= Text::_('NOT_CONFIGURED');
        }
        elseif ($item->language_image) {
            $html .= HTML::_('image', 'mod_languages/' . $item->language_image . '.gif', '', ['class' => 'me-1'], true) . $title;
        }
        elseif ($item->language_title) {
            $html .= $title;
        }
        else {
            $html .= Text::_('NOT_CONFIGURED');
        }

        $html .= '</div>';

        // Get the associations
        if ($localizations = self::localizations($contentID)) {

            foreach ($localizations as $tag => $localization) {
                $localizations[$tag] = (int) $localization->id;
            }

            // Get the associated menu items
            $query = DB::query()
                ->select(array_merge(DB::qn(['c.id', 'c.title', 'l.lang_code']), DB::qn(['l.title'], ['language_title'])))
                ->from(DB::qn('#__content', 'c'))
                ->leftJoin(DB::qn('#__languages', 'l'), DB::qc('c.language', 'l.lang_code'))
                ->whereIn(DB::qn('c.id'), $localizations);

            DB::set($query);

            if ($associations = DB::objects('id')) {
                $languages         = LanguageHelper::getContentLanguages([0, 1]);
                $content_languages = array_column($languages, 'lang_code');

                foreach ($associations as $association) {
                    if (in_array($association->lang_code, $content_languages)) {
                        $url               = Route::_('index.php?option=com_groups&view=content&layout=edit&id=' . $association->id);
                        $language          = htmlspecialchars($association->language_title, ENT_QUOTES, 'UTF-8');
                        $title             = htmlspecialchars($association->title, ENT_QUOTES, 'UTF-8');
                        $association->link = HTML::tip(
                            $association->lang_code,
                            "localization-tip-$contentID",
                            '<strong>' . $language . '</strong><br>' . $title,
                            ['class' => 'badge bg-secondary'],
                            $url
                        );
                    }
                    else {
                        // Display warning if Content Language is trashed or deleted
                        Application::message(
                            Text::sprintf('CONTENT_LANGUAGE_WARNING', $association->lang_code),
                            Application::WARNING
                        );
                    }
                }
            }

            $associations = LayoutHelper::render('joomla.content.associations', $associations);
            $html         .= "&nbsp;<div style=\"display:inline-block\">$associations</div>";
        }

        return $html;
    }

    /**
     * Replaces relevant links to categories and articles with links to profiles and pages.
     *
     * @param   string  $html  the string containing potential links to alter
     *
     * @return void
     */
    public static function replaceContentURLS(string &$html): void
    {
        if (!preg_match_all('/href="(([^"]+)\?([^"]+(category|article)[^"]+))"/', $html, $matches)) {
            return;
        }

        foreach (array_unique($matches[self::URL]) as $index => $url) {
            // Menu item or pre-resolved URL item
            if (THM_GroupsHelperRouter::getPathItems($matches[self::PATH][$index])) {
                continue;
            }

            $query = html_entity_decode($matches[self::QUERY][$index]);
            parse_str($query, $params);

            if (!empty($params['option']) and $params['option'] !== 'com_content') {
                continue;
            }

            $params['option'] = 'com_content';
            if (empty($params['view']) or !in_array($params['view'], ['article', 'category'])) {
                continue;
            }

            if (THM_GroupsHelperRouter::translateContent($params)) {
                $newURL = THM_GroupsHelperRouter::build($params);
                $html   = str_replace($url, $newURL, $html);
            }
        }
    }

    /**
     * Replaces groups URLS with queries with groups SEF-URLs.
     *
     * @param   string  $html
     *
     * @return void
     */
    public static function replaceGroupsQueries(string &$html): void
    {
        if (!preg_match_all('/href="([^"]+\?[^"]+(thm_)?groups[^"]+)"/', $html, $matches)) {
            return;
        }

        $modalViews = ['select-profiles'];
        $queries    = array_unique($matches[1]);

        foreach ($queries as $query) {
            $uri = Uri::getInstance($query);
            $uri->parse($query);
            $params = $uri->getQuery(true);

            if ((!empty($params['view']) and in_array($params['view'], $modalViews))
                or !empty($params['task'])) {
                continue;
            }

            if ($url = THM_GroupsHelperRouter::build($params)) {
                $html = str_replace($query, $url, $html);
            }
        }
    }

    /**
     * Parses the given string to check for content associated with the component
     *
     * @param   int|string  $potentialContent  the segment being checked
     *
     * @return int the id of the associated content if existent, otherwise 0
     */
    public static function resolve(int|string $potentialContent, int $userID = 0): int
    {
        $contentID = 0;
        if (is_numeric($potentialContent)) {
            $contentID = (int) $potentialContent;
        }
        elseif (preg_match('/^(\d+)-[a-zA-Z\-]+$/', $potentialContent, $matches)) {
            $contentID = (int) $matches[1];
        }

        if (!$contentID) {
            return $contentID;
        }

        return Pages::userID($contentID, $userID) ? $contentID : 0;
    }

    /**
     * Gets the title of the content with the given id.
     *
     * @param   int  $contentID
     *
     * @return string
     */
    public static function title(int $contentID): string
    {
        $table = new CTable();
        if ($table->load($contentID)) {
            return $table->title;
        }
        return '';
    }

    /**
     * Gets the id of the author associated with the content.
     *
     * @param   int  $contentID
     * @param   int  $userID
     *
     * @return int
     */
    public static function userID(int $contentID, int $userID = 0): int
    {
        $table = new PTable();
        if ($table->load(['contentID' => $contentID])) {
            if ($userID) {
                return $userID === $table->userID ? $table->userID : 0;
            }
            return $table->userID;
        }
        return 0;
    }
}