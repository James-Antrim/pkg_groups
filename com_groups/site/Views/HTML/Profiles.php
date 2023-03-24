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
    protected function completeItems()
    {
        foreach ($this->items as $rowNo => $item)
        {
            $item->activated     = HTML::toggle($rowNo, Helper::activatedStates[$item->activated], 'Profiles');
            $item->block         = HTML::toggle($rowNo, Helper::blockedStates[$item->block], 'Profiles');
            $item->content       = HTML::toggle($rowNo, Helper::contentStates[$item->content], 'Profiles');
            $item->editing       = HTML::toggle($rowNo, Helper::editingStates[$item->editing], 'Profiles');
            $item->editLink      = Route::_('index.php?option=com_groups&view=Profile&layout=edit&id=' . $item->id);
            $item->groups        = $this->formatGroups($item->groups);
            $item->lastvisitDate = $item->lastvisitDate ?: Text::_('GROUPS_NEVER');
            $item->published     = HTML::toggle($rowNo, Helper::publishedStates[$item->published], 'Profiles');
            $item->viewLink      = Route::_('index.php?option=com_groups&view=Profile&id=' . $item->id);
        }
    }

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
            'groups filter',
            'batch stuff',
            'password reset',
            'model functions'
        ];

        parent::display($tpl);
    }

    /**
     * Formats the profile associated groups and roles for display.
     *
     * @param array $groups the groups associated with the profile
     *
     * @return string
     */
    private function formatGroups(array $groups): string
    {
        if (count($groups) >= 3)
        {
            return Text::_('GROUPS_MULTIPLE_GROUPS');
        }

        foreach ($groups as $groupID => $group)
        {
            switch (count($group['roles']))
            {
                case 0:
                    $roles = '';
                    break;
                case 1:
                case 2:
                    $roles = ': ' . implode(' & ', $group['roles']);
                    break;
                default:
                    $roles = ': ' . Text::_('GROUPS_MULTIPLE_ROLES');
                    break;
            }

            $groups[$groupID] = $group['name'] . $roles;
        }

        return implode('<br>', $groups);
    }

    /**
     * @inheritDoc
     */
    protected function initializeHeaders()
    {
        // Columns made 'redundant' by filters are left in to allow for display of in column buttons.
        $this->headers = [
            'check' => ['type' => 'check'],
            'name' => [
                'column' => 'surnames, forenames',
                'link' => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_PROFILE'),
                'type' => 'sort'
            ],
            'groups' => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_GROUPS'),
                'type' => 'value'
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
