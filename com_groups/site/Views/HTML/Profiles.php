<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\HTML;
use THM\Groups\Helpers;
use THM\Groups\Helpers\Profiles as Helper;
use THM\Groups\Layouts\ListItem;

/**
 * View class for displaying available profiles.
 */
class Profiles extends ListView
{
    /**
     * @inheritDoc
     */
    public function display($tpl = null)
    {
        if ($this->backend and !Helpers\Can::manage())
        {
            Application::error(403);
        }

        // TBD: Support notes?
        $this->todo = [
            'main menu',
            'Profiles => Persons',
            'groups + filter',
            'batch stuff',
            'password reset',
            'model functions',
            'suppress columns based on filters'
        ];

        parent::display($tpl);
    }

    /**
     * @inheritDoc
     */
    protected function completeItems()
    {
        foreach ($this->items as $rowNo => $item)
        {
            $item->activated     = HTML::toggle($rowNo, Helper::activatedStates[$item->activated], 'Profiles');
            $item->block         = HTML::toggle($rowNo, Helper::blockedStates[$item->block], 'Profiles');
            $item->content       = HTML::toggle($rowNo, Helper::contentStates[$item->content], 'Profiles');
            $item->editing       = HTML::toggle($rowNo, Helper::editingStates[$item->editing], 'Profiles');
            $item->editLink      = Route::_('index.php?option=com_groups&view=Profile&layout=edit&id=' . $item->id);
            $item->lastvisitDate = $item->lastvisitDate ?: Text::_('GROUPS_NEVER');
            $item->published     = HTML::toggle($rowNo, Helper::publishedStates[$item->published], 'Profiles');
            $item->viewLink      = Route::_('index.php?option=com_groups&view=Profile&id=' . $item->id);
        }
    }

    /**
     * @inheritDoc
     */
    protected function initializeHeaders()
    {
        $this->headers = [
            'check' => ['type' => 'check'],
            'name' => [
                'column' => 'surnames, forenames',
                'link' => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_PROFILE'),
                'type' => 'sort'
            ],
            'published' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_PROFILE_PUBLISHED'),
                'type' => 'value'
            ],
            'editing' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_EDITING'),
                'type' => 'value'
            ],
            'content' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_CONTENTS'),
                'type' => 'value'
            ],
            'block' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_USER_ENABLED'),
                'type' => 'value'
            ],
            'activated' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_USER_ACTIVATION'),
                'type' => 'value'
            ],
            'lastvisitDate' => [
                'column' => 'lastvisitDate',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_VISITED'),
                'type' => 'sort'
            ],
            'registerDate' => [
                'column' => 'registerDate',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_REGISTERED'),
                'type' => 'sort'
            ]
        ];
    }
}
