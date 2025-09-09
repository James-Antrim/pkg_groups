<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Joomla\CMS\{HTML\HTMLHelper, Router\Route, Uri\Uri};
use Joomla\CMS\Toolbar\Button\DropdownButton;
use stdClass;
use THM\Groups\Adapters\{Application, HTML, Input, Text, Toolbar, User};
use THM\Groups\Helpers\{Can, Categories, Pages as Helper, Profiles, Users};
use THM\Groups\Layouts\HTML\Row;
use THM_GroupsHelperRouter;

/**
 * Class displays content in the profile's content category
 */
class Pages extends ListView
{
    public bool $access;

    public $items;

    public bool $own = false;

    public int $profileID;

    public function addToolBar(): void
    {
        $this->toDo[] = 'Flesh out the implementation of the authorizeAJAX function for the profile user.';
        $this->toDo[] = 'Joomla batch functions for language and level. No current plans for tags implementation.';
        $this->toDo[] = 'Filter for relevance. :)';
        $this->toDo[] = 'Delete button if set to trashed state and accessible.';
        $this->toDo[] = 'Set the form Itemid. //$this->menuID = Input::itemID()';
        $this->toDo[] = 'Set the form profile id.';
        $this->toDo[] = 'Filter content for publication state & language when no access.';

        if (Categories::root()) {
            $toolbar = Toolbar::instance();

            if ($this->access) {
                $this->allowBatch = true;
                $toolbar->addNew('pages.add');
                /** @var DropdownButton $dropdown */
                $dropdown = $toolbar->dropdownButton('pages')
                    ->buttonClass('btn btn-action')
                    ->icon('icon-ellipsis-h')
                    ->listCheck(true);
                $dropdown->toggleSplit(false);
                $childBar = $dropdown->getChildToolbar();
                $childBar->publish('pages.feature', Text::_('FEATURE'));
                $childBar->unpublish('pages.unfeature', Text::_('UNFEATURE'));
                $childBar->publish('pages.publish');
                $childBar->unpublish('pages.hide');
                $childBar->archive('pages.archive');
                $childBar->trash('pages.trash');
                $childBar->popupButton('batch', Text::_('BATCH'))
                    ->popupType('inline')
                    ->textHeader(Text::_('BATCH'))
                    ->url('#groups-batch')
                    ->modalWidth('800px')
                    ->modalHeight('fit-content')
                    ->listCheck(true);

                $batchBar = Toolbar::instance('batch');
                $batchBar->standardButton('batch', Text::_('PROCESS'), 'pages.batch');
            }
        }
        else {
            Application::message('NO_ROOT', Application::NOTICE);
        }

        if ($this->access) {
            $title = $this->own ? Text::_('MY_PAGES') : Text::sprintf('MANAGE_PAGES', Profiles::name($this->profileID));
        }
        else {
            $title = Text::sprintf('PAGES', Profiles::name($this->profileID));
        }

        $this->title($title);
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        if ($this->access) {
            $item->featured = HTML::toggle($item->id, Helper::FEATURED_STATES[$item->featured], 'contents');
            $item->state    = HTML::toggle($index, Helper::STATES[$item->state], 'contents');
        }

        $item->title = $this->getTitle($item->id, $item->title);
    }

    /** @inheritDoc */
    public function display($tpl = null): void
    {
        $userID          = User::id();
        $this->profileID = Input::integer('profileID', $userID);
        if (empty($this->profileID) or !Users::published($this->profileID)) {
            Application::error(404);
        }

        if (!Users::categoryID($this->profileID) or !Users::content($this->profileID)) {
            Application::error(412);
        }

        $this->own    = $this->profileID = $userID;
        $this->access = ($this->own or Can::manage('com_content'));

        parent::display($tpl);
    }

    /**
     * Returns a title of an article
     *
     * @param   int     $key   the key of the item being iterated
     * @param   object  $item  An object item
     *
     * @return  string the HTML for the title
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

        if ($this->access) {
            if (!empty($item->checked_out)) {
                $author = empty($item->author_name) ? '' : $item->author_name;
                $lock   = HTMLHelper::_('jgrid.checkedout', $key, $author, $item->checked_out_time, 'content.',
                    true);
            }

            $returnURL = base64_encode(Uri::getInstance()->toString());
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

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $boxes = $this->access ? [
            'check'    => ['type' => 'check'],
            'ordering' => ['active' => false, 'type' => 'ordering']
        ] : [];

        $title = [
            'name' => [
                'column'     => 'title',
                'link'       => Row::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('TITLE'),
                'type'       => 'sort'
            ]
        ];

        $supplements = $this->access ? [
            'state'    => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('STATUS'),
                'type'       => 'value'
            ],
            'featured' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('FEATURED'),
                'type'       => 'value'
            ],
            'level'    => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('LEVEL'),
                'type'       => 'value'
            ],
            'language' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('LANGUAGE'),
                'type'       => 'value'
            ],
            'id'       => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('ID'),
                'type'       => 'value'
            ]
        ] : [];

        $this->headers = array_merge($boxes, $title, $supplements);
    }
}
