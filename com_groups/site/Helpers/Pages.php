<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

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

    public const ARCHIVED = 2, PUBLISHED = 1, TRASHED = -2, UNPUBLISHED = 0;

    // todo joomla has some kind of drop down here
    public const STATES = [
        self::ARCHIVED,
        self::PUBLISHED,
        self::TRASHED,
        self::UNPUBLISHED
    ];

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