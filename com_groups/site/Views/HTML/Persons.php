<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use THM\Groups\Adapters\HTML;
use THM\Groups\Helpers\Persons as Helper;
use THM\Groups\Layouts\ListItem;

/**
 * View class for displaying available persons.
 */
class Persons extends ListView
{
    /**
     * @inheritDoc
     */
    protected function completeItems()
    {
        foreach ($this->items as $rowNo => $item)
        {
            $item->activated     = HTML::toggle($rowNo, Helper::activatedStates[$item->activated], 'Persons');
            $item->block         = HTML::toggle($rowNo, Helper::blockedStates[$item->block], 'Persons');
            $item->content       = HTML::toggle($rowNo, Helper::contentStates[$item->content], 'Persons');
            $item->editing       = HTML::toggle($rowNo, Helper::editingStates[$item->editing], 'Persons');
            $item->editLink      = Route::_('index.php?option=com_groups&view=Profile&layout=edit&id=' . $item->id);
            $item->groups        = $this->formatGroups($item->groups);
            $item->lastvisitDate = $item->lastvisitDate ?: Text::_('GROUPS_NEVER');
            $item->published     = HTML::toggle($rowNo, Helper::publishedStates[$item->published], 'Persons');
            $item->viewLink      = Route::_('index.php?option=com_groups&view=Profile&id=' . $item->id);
        }
    }

    /**
     * @inheritDoc
     */
    public function display($tpl = null)
    {
        $this->todo = [
            'batch stuff',
            'main menu',
            'password reset',
            'toolbar'
            // User notes support is not planned at this time.
        ];

        parent::display($tpl);
    }

    /**
     * Formats the person associated groups and roles for display.
     *
     * @param array $groups the groups associated with the person
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
            $roles = match (count($group['roles']))
            {
                0, 1 => '',
                2 => ': ' . implode(' & ', $group['roles']),
                default => ': ' . Text::_('GROUPS_MULTIPLE_ROLES'),
            };

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
