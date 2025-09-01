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

use Joomla\CMS\Uri\Uri;
use THM\Groups\Adapters\Database as DB;
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
            'class'  => 'publish',
            'column' => 'featured',
            'task'   => 'unfeature',
            'tip'    => 'TOGGLE_TIP_FEATURED'
        ],
        self::UNFEATURED => [
            'class'  => 'unpublish',
            'column' => 'featured',
            'task'   => 'feature',
            'tip'    => 'TOGGLE_TIP_UNFEATURED'
        ]
    ];

    public const ARCHIVED = 2, PUBLISHED = 1, TRASHED = -2, HIDDEN = 0;

    public const STATES = [
        self::ARCHIVED  => [
            'class'  => 'archive',
            'column' => 'state',
            'task'   => 'hide',
            'tip'    => 'TOGGLE_TIP_ARCHIVED'
        ],
        self::PUBLISHED => [
            'class'  => 'publish',
            'column' => 'state',
            'task'   => 'hide',
            'tip'    => 'TOGGLE_TIP_PUBLISHED'
        ],
        self::TRASHED   => [
            'class'  => 'trash',
            'column' => 'state',
            'task'   => 'publish',
            'tip'    => 'TOGGLE_TIP_TRASHED'
        ],
        self::HIDDEN    => [
            'class'  => 'unpublish',
            'column' => 'state',
            'task'   => 'publish',
            'tip'    => 'TOGGLE_TIP_UNPUBLISHED'
        ],
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
        elseif (preg_match('/^(\d+)\-[a-zA-Z\-]+$/', $potentialContent, $matches)) {
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