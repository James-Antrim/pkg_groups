<?php
/**
 * @package     Groups
 * @extension   mod_thm_groups_menu
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

use THM\Groups\Adapters\{Database as DB, HTML};
use THM\Groups\Helpers\{Pages, Users};

/**
 * Data retrieval class for the THM Groups menu module.
 */
class THM_GroupsHelperMenu
{
    /**
     * Returns published content information for the profile's content category.
     *
     * @param   int  $profileID  the id of the profile
     *
     * @return    array  an array of table row objects
     */
    public static function getContent(int $profileID): array
    {
        $categoryID = Users::categoryID($profileID);
        $date       = date('Y-m-d H:i:s');
        $notExpired = '(' . DB::qcs([
                ['content.publish_down', $date, '>=', true],
                ['content.publish_down', 0],
                ['content.publish_down', '0000-00-00 00:00:00', '=', true]
            ], 'OR') . ')';

        $query = DB::query();
        $query->select(DB::qn(['content.id', 'content.title', 'content.alias', 'content.catid']));
        $query->from(DB::qn('#__content', 'content'));
        $query->innerJoin(DB::qn('#__groups_content', 'pContent'), DB::qc('pContent.id', 'content.id'));
        $query->where(DB::qcs([
            ['content.catid', $categoryID],
            ['content.publish_up', $date, '<=', true],
            ['content.state', Pages::PUBLISHED],
            ['pContent.featured', Pages::FEATURED]
        ]));
        $query->where($notExpired);
        $query->order('content.ordering ASC');

        DB::set($query);

        return DB::objects();
    }

    /**
     * Creates a list item with a link
     *
     * @param   bool    $active  whether the link is currently active
     * @param   array   $params  the parameters used to generate the link
     * @param   string  $text    the text to be displayed in the link
     *
     * @return string the HTML for the list item
     */
    public static function getItem(bool $active, array $params, string $text): string
    {
        $attribs = [];

        if ($active) {
            $attribs['class'] = 'active_link current_link';
        }

        $href = THM_GroupsHelperRouter::build($params);
        $text = '<span class="item-title">' . $text . "</span>";

        return HTML::link($href, $text, $attribs);
    }
}