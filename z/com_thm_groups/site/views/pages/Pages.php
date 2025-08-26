<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\{HTML\HTMLHelper, Router\Route, Uri\Uri};
use THM\Groups\Adapters\{Application, Document, HTML, Input, Text, User};
use THM\Groups\Helpers\{Can, Pages as Helper, Profiles, Users};
use THM\Groups\Views\HTML\ListView;

/**
 * Class displays content in the profile's content category
 */
class Pages extends ListView
{
    private bool $canCreate;

    public bool $canEdit;

    private int $categoryID;

    public $items;

    public string $pageTitle;

    public int $profileID;

    /**
     * Method to get display
     *
     * @param   Object  $tpl  template
     *
     * @return void
     */
    public function display($tpl = null): void
    {
        $userID          = User::id();
        $this->profileID = Input::integer('profileID', $userID);
        if (empty($this->profileID) or !Users::published($this->profileID)) {
            Application::error(404);
        }

        $this->categoryID = Users::categoryID($this->profileID);
        if (empty($this->categoryID) or !Users::content($this->profileID)) {
            Application::error(412);
        }

        $this->canCreate = Can::create('com_content.category', $this->categoryID);
        $this->canEdit   = Can::edit('com_content.category', $this->categoryID);
        $this->items     = $this->get('Items');
        $this->menuID    = Input::itemID();
        $this->modifyDocument();

        if ($this->profileID == $userID) {
            $contextTitle = Text::_('MY_CONTENT');
        }
        elseif ($this->canEdit) {
            $contextTitle = Text::sprintf(
                'MANAGE_PROFILE_CONTENT',
                Profiles::name($this->profileID)
            );
        }
        else {
            $contextTitle = Text::sprintf(
                'PROFILE_CONTENT',
                Profiles::name($this->profileID)
            );
        }
        $this->pageTitle = $contextTitle;

        parent::display($tpl);
    }

    /**
     * Returns a button for creating of a new article
     *
     * @return string
     */
    public function getNewButton(): string
    {
        if ($this->canCreate) {
            $return  = base64_encode(Joomla\CMS\Uri\Uri::getInstance()->toString());
            $addURL  = Uri::base() . '?option=com_content&task=article.add';
            $addURL  .= "&catid=$this->categoryID&return=$return";
            $attribs = ['title' => Text::_('NEW_ARTICLE'), 'class' => 'btn'];
            $text    = '<span class="icon-new"></span> ' . Text::_('NEW_ARTICLE');

            return HTML::link(Route::_($addURL), $text, $attribs);
        }
        else {
            return '';
        }
    }

    /**
     * Creates the output data for the table row
     *
     * @param   int     $key   the row id
     * @param   object  $item  the content item
     *
     * @return  string  the HTML for the row to be rendered
     * @throws Exception
     */
    public function getRow(int $key, object $item): string
    {
        if ($this->canEdit) {
            $sortIcon  = '<span class="sortable-handler" style="cursor: move;"><i class="icon-menu"></i></span>';
            $sortInput = '<input type="text" style="display:none" name="order[]" ';
            $sortInput .= 'value="' . $item->ordering . '" class="width-20 text-area-order">';
            $sort      = '<td class="order nowrap center btn-column" style="width: 40px;">' . $sortIcon . $sortInput;
            $sort      .= '</td>';

            $published = '<td class="publish-column">';
            $published .= HTML::toggle($item->id, Helper::STATES[$item->status], 'pages');
            $published .= HTML::checkBox($key, $item->id);
            $published .= '</td>';

            $listed = '<td class="btn-column">';
            $listed .= HTML::toggle($item->id, Helper::FEATURED_STATES[$item->featured], 'pages');
            $listed .= '</td>';
        }
        else {
            $sort      = '';
            $published = '';
            $listed    = '';
        }

        $title = '<td class="title-column">' . $this->getTitle($key, $item) . '</td>';

        return $sort . $title . $published . $listed;
    }

    /**
     * Returns a title of an article
     *
     * @param   int     $key   the key of the item being iterated
     * @param   object  $item  An object item
     *
     * @return  string the HTML for the title
     * @throws Exception
     */
    public function getTitle(int $key, object $item): string
    {
        $contentLinkAttribs = [
            'class'  => 'view-link',
            'target' => '_blank',
            'title'  => Text::_('VIEW')
        ];
        $lock               = '';
        $formLink           = '';

        if ($this->canEdit) {
            if (!empty($item->checked_out)) {
                $author = empty($item->author_name) ? '' : $item->author_name;
                $lock   = HTMLHelper::_('jgrid.checkedout', $key, $author, $item->checked_out_time, 'content.',
                    true);
            }

            $returnURL = base64_encode(Joomla\CMS\Uri\Uri::getInstance()->toString());
            $formURL   = Uri::base() . "?option=com_content&task=article.edit&a_id=$item->id&return=$returnURL";
            $formURL   = Route::_($formURL);
            $formLink  .= HTML::link($formURL, $item->title, ['title' => Text::_('EDIT')]);

            $contentText = '<span class="icon-eye-open"></span>';
        }
        else {
            $contentText = $item->title;
        }

        $params     = ['id' => $item->id, 'profileID' => $this->profileID, 'view' => 'content'];
        $contentURL = THM_GroupsHelperRouter::build($params);

        return $lock . $formLink . HTML::link($contentURL, $contentText, $contentLinkAttribs);
    }

    protected function initializeColumns(): void
    {
        // TODO: Implement initializeColumns() method.
    }

    /**
     * Adds styles and scripts to the document
     *
     * @return  void
     */
    protected function modifyDocument(): void
    {
        Document::style('content_manager');
    }
}
