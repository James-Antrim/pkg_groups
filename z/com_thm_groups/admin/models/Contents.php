<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      Ilja Michajlow, <ilja.michajlow@mni.thm.de>
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */
defined('_JEXEC') or die;

use Joomla\Database\DatabaseQuery;
use THM\Groups\Adapters\{Application, Database as DB, HTML, Text, User};
use THM\Groups\Controllers\Pages as Controller;
use THM\Groups\Helpers\{Can, Categories, Pages};
use THM\Groups\Models\ListModel;

/**
 * THM_GroupsModelContent_Manager is a class which deals with the information preparation for the administrator view.
 */
class Contents extends ListModel
{
    protected string $defaultOrdering = 'author_name';

    /** @inheritDoc */
    public function __construct($config = [])
    {
        parent::__construct($config);

        Controller::clean();
    }

    /** @inheritDoc */
    protected function getListQuery(): DatabaseQuery
    {
        $query = DB::query();

        $rootCategory = Categories::root();

        if (empty($rootCategory)) {
            return $query;
        }

        $query->select('content.*')
            ->select('pContent.featured AS featured')
            ->select($query->concatenate(['pa1.value', 'pa2.value'], '->') . ' as author_name')
            ->from('#__content AS content')
            ->innerJoin('#__thm_groups_content AS pContent ON pContent.id = content.id')
            ->innerJoin('#__categories AS cCats ON cCats.id = content.catid')
            ->innerJoin('#__thm_groups_categories AS pCats ON pCats.id = cCats.id')
            ->innerJoin('#__users AS users ON users.id = pCats.profileID')
            ->innerJoin('#__thm_groups_profile_attributes AS pa1 ON pa1.profileID = pCats.profileID')
            ->innerJoin('#__thm_groups_profile_attributes AS pa2 ON pa2.profileID = pCats.profileID')
            ->where(DB::qcs([['cCats.parent_id', $rootCategory], ['pa1.attributeID', 2], ['pa2.attributeID', 1]]));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $query->where("(content.title LIKE '%" . implode("%' OR content.title LIKE '%",
                    explode(' ', $search)) . "%')");
        }

        $authorID = $this->getState('filter.author');
        if (!empty($authorID)) {
            $query->where(DB::qc('pCats.profileID', $authorID));
        }

        $featured = $this->getState('filter.featured');
        if (isset($featured) and $featured == '0') {
            $query->where("(pContent.featured = '0' OR pContent.featured IS NULL)");
        }
        elseif ($featured == '1') {
            $query->where("pContent.featured = '1'");
        }

        $state = $this->getState('filter.status');
        if (is_numeric($state)) {
            $query->where('content.state = ' . (int) $state);
        }

        $this->orderBy($query);

        return $query;
    }

    /** @inheritDoc */
    public function getItems(): array
    {
        $rootCategory = Categories::root();

        $return = [];

        if (!empty($rootCategory)) {
            $items = parent::getItems();
        }
        else {
            Application::message(Text::_('ROOT_CATEGORY_NOT_CONFIGURED'), Application::NOTICE);

            return $return;
        }

        if (empty($items)) {
            return $return;
        }

        $generalOrder    = '<input type="text" style="display:none" name="order[]" ';
        $generalOrder    .= 'value="XX" class="width-20 text-area-order " />';
        $generalSortIcon = '<span class="sortable-handlerXXX"><i class="icon-menu"></i></span>';
        $canSort         = User::authorise('core.edit', 'com_thm_groups');
        $orderingActive  = $this->state->get('list.ordering') == 'content.ordering';

        $index = 0;

        foreach ($items as $item) {
            $canEdit   = User::authorise('core.edit', 'com_content.article.' . $item->id);
            $iconClass = '';

            if (!$canEdit) {
                $iconClass = ' inactive';
            }
            elseif (!$orderingActive) {
                $iconClass = ' inactive tip-top hasTooltip';
            }

            $specificOrder = ($canSort and $orderingActive) ? str_replace('XX', $item->ordering, $generalOrder) : '';

            $return[$index] = [];

            $return[$index]['attributes'] = ['class' => 'order nowrap center', 'id' => $item->id];

            $return[$index]['ordering']['attributes'] = ['class' => "order nowrap center", 'style' => "width: 40px;"];
            $return[$index]['ordering']['value']      = str_replace('XXX', $iconClass,
                    $generalSortIcon) . $specificOrder;

            $return[$index][0] = JHtml::_('grid.id', $index, $item->id) . " $item->id";

            $canEdit = Can::edit('com_content.article', $item->id);

            if ($canEdit) {
                $url               = JRoute::_("index.php?option=com_content&task=article.edit&id=$item->id");
                $return[$index][1] = JHtml::link($url, $item->title, ['target' => '_blank']);
                $return[$index][1] .= " <span class=\"icon-edit\"></span>";
            }
            else {
                $return[$index][1] = $item->title;
            }

            $authorParts       = explode('->', $item->author_name);
            $return[$index][2] = count($authorParts) > 1 ? "$authorParts[0], $authorParts[1]" : $authorParts[0];
            $return[$index][3] = HTML::toggle($item->id, Pages::FEATURED_STATES[$item->featured], 'pages');
            $return[$index][4] = HTML::toggle($item->id, Pages::STATES[$item->status], 'pages');

            $index++;
        }

        return $return;
    }

    /**
     * Function to get table headers
     *
     * @return array including headers
     */
    public function getHeaders(): array
    {
        $ordering  = $this->state->get('list.ordering');
        $direction = $this->state->get('list.direction');

        $headers = ['order', 'id', 'title', 'author', 'featured', 'status'];
        $headers = array_flip($headers);

        $headers['id']    = JHtml::_('searchtools.sort', 'COM_THM_GROUPS_ID', 'content.id', $direction, $ordering);
        $headers['title'] = JHtml::_('searchtools.sort', 'COM_THM_GROUPS_TITLE', 'title', $direction, $ordering);

        $headers['author']
            = JHtml::_('searchtools.sort', 'COM_THM_GROUPS_PROFILE', 'author_name', $direction, $ordering);
        $headers['featured']
            = JHtml::_('searchtools.sort', 'COM_THM_GROUPS_PROFILE_MENU', 'pContent.featured', $direction, $ordering);
        $headers['order']
            = JHtml::_('searchtools.sort', 'COM_THM_GROUPS_ORDER', 'content.ordering', $direction, 'ASC');
        $headers['status']
            = JHtml::_('searchtools.sort', 'COM_THM_GROUPS_STATUS', 'content.state', $direction, $ordering);

        return $headers;
    }
}
