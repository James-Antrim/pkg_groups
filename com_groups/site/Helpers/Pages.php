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

use THM\Groups\Tables\{Content as CTable, Pages as PTable};

/**
 * Class for the handling of content related information.
 */
class Pages
{
    use Persistent;

    public const FEATURED = 1, UNFEATURED = 0;

    public const featureStates = [
        self::FEATURED   => [
            'class'  => 'publish',
            'column' => 'featured',
            'task'   => 'unfeature',
            'tip'    => 'GROUPS_TOGGLE_TIP_FEATURED'
        ],
        self::UNFEATURED => [
            'class'  => 'unpublish',
            'column' => 'showIcon',
            'task'   => 'showIcon',
            'tip'    => 'GROUPS_TOGGLE_TIP_UNFEATURED'
        ]
    ];

    public const ARCHIVED = 2, PUBLISHED = 1, TRASHED = -2, UNPUBLISHED = 0;

    // todo joomla has some kind of drop down here
    public const publishedStates = [
        self::ARCHIVED,
        self::PUBLISHED,
        self::TRASHED,
        self::UNPUBLISHED
    ];

    /**
     * Gets the id of the category associated with the content.
     *
     * @param   int  $contentID
     *
     * @return int
     */
    public static function authorID(int $contentID): int
    {
        $table = new CTable();
        if ($table->load($contentID)) {
            return $table->created_by;
        }
        return 0;
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
}