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

use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Button\DropdownButton;
use stdClass;
use THM\Groups\Adapters\{HTML, Text, Toolbar};
use THM\Groups\Helpers\{Can, Users as Helper};
use THM\Groups\Layouts\HTML\Row;

/**
 * View class for displaying available persons.
 */
class Users extends ListView
{
    /** @inheritDoc */
    protected function addToolbar(): void
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::instance();

        /*if (Can::create())
        {
            $toolbar->addNew('users.add');
        }*/

        if (Can::administrate() or Can::changeState()) {
            /** @var DropdownButton $dropdown */
            $dropdown = $toolbar->dropdownButton('status-group')
                ->text('JTOOLBAR_CHANGE_STATUS')
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);
            $dropdown->toggleSplit(false);

            $childBar = $dropdown->getChildToolbar();

            if (Can::changeState()) {
                $childBar->standardButton('publish', 'GROUPS_PUBLISH_PROFILE')
                    ->icon('fa fa-eye')
                    ->task('users.publish')
                    ->listCheck(true);
                $childBar->standardButton('unblock', 'GROUPS_HIDE_PROFILE')
                    ->icon('fa fa-eye-slash')
                    ->task('users.hide')
                    ->listCheck(true);
                $childBar->standardButton('enableEditing', 'GROUPS_ENABLE_EDITING')
                    ->icon('fa fa-edit')
                    ->task('users.enableEditing')
                    ->listCheck(true);
                $childBar->standardButton('disableEditing', 'GROUPS_DISABLE_EDITING')
                    ->icon('fa fa-minus-circle')
                    ->task('users.disableEditing')
                    ->listCheck(true);
                $childBar->standardButton('enableContent', 'GROUPS_ENABLE_CONTENT')
                    ->icon('fa fa-folder-open')
                    ->task('users.enableContent')
                    ->listCheck(true);
                $childBar->standardButton('disableContent', 'GROUPS_DISABLE_CONTENT')
                    ->icon('fa fa-folder')
                    ->task('users.disableContent')
                    ->listCheck(true);
                $childBar->standardButton('unblock', 'GROUPS_UNBLOCK_USER')
                    ->icon('fa fa-door-open')
                    ->task('users.block')
                    ->listCheck(true);
                $childBar->standardButton('block', 'GROUPS_BLOCK_USER')
                    ->icon('fa fa-door-closed')
                    ->task('users.block')
                    ->listCheck(true);
                $childBar->standardButton('activate', 'GROUPS_ACTIVATE_USER')
                    ->icon('fa fa-check-square')
                    ->task('users.activate')
                    ->listCheck(true);

                if (Can::batchProcess()) {
                    $childBar->popupButton('batch')
                        ->selector('collapseModal')
                        ->text('GROUPS_BATCH_PROCESSING')
                        ->listCheck(true);
                }

                if (Can::delete()) {
                    $childBar->delete('users.delete')
                        ->message('GROUPS_DELETE_MESSAGE')
                        ->text('GROUPS_DELETE_USER')
                        ->listCheck(true);
                }
            }

            /*if ($this->state->get('filter.published') != -2 && $canDo->get('core.edit.state')) {
                $childBar->trash('notes.trash');
            }*/
        }

        /*if (!$this->isEmptyState && $this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
            $toolbar->delete('notes.delete')
                ->text('JTOOLBAR_EMPTY_TRASH')
                ->message('JGLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }*/

        /*if (Can::configure()) {
            $toolbar->preferences('com_users');
        }*/

        /*$toolbar->help('User_Notes');*/
        parent::addToolbar();
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        $item->activated     = HTML::toggle($index, Helper::activatedStates[$item->activated], 'users');
        $item->block         = HTML::toggle($index, Helper::blockedStates[$item->block], 'users');
        $item->content       = HTML::toggle($index, Helper::contentStates[$item->content], 'users');
        $item->editing       = HTML::toggle($index, Helper::editingStates[$item->editing], 'users');
        $item->editLink      = Route::_('index.php?option=com_groups&view=user&id=' . $item->id);
        $item->groups        = $this->formatGroups($item->groups);
        $item->lastvisitDate = $item->lastvisitDate ?: Text::_('NEVER');
        $item->name          = $item->forenames ? "$item->forenames $item->surnames" : $item->surnames;
        $item->published     = HTML::toggle($index, Helper::publishedStates[$item->published], 'users');
        $item->viewLink      = Route::_('index.php?option=com_groups&view=profile&id=' . $item->id);
    }

    /** @inheritDoc */
    public function display($tpl = null): void
    {
        $this->allowBatch = Can::batchProcess();
        $this->toDo       = [
            'add configuration to overwrite com_users links to here?',
            'add button',
            'access debug button under group list'
        ];

        parent::display($tpl);
    }

    /**
     * Formats the person associated groups and roles for display.
     *
     * @param   array  $groups  the groups associated with the person
     *
     * @return string
     */
    private function formatGroups(array $groups): string
    {
        if (count($groups) >= 3) {
            return Text::_('MULTIPLE_GROUPS');
        }

        foreach ($groups as $groupID => $group) {
            $roles = match (count($group['roles'])) {
                0 => '',
                1, 2 => ': ' . implode(' & ', $group['roles']),
                default => ': ' . Text::_('MULTIPLE_ROLES'),
            };

            $groups[$groupID] = $group['name'] . $roles;
        }

        return implode('<br>', $groups);
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        // Columns made 'redundant' by filters are left in to allow for display of in column buttons.
        $this->headers = [
            'check'         => ['type' => 'check'],
            'name'          => [
                'column'     => 'surnames, forenames',
                'link'       => Row::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('PROFILE'),
                'type'       => 'sort'
            ],
            'groups'        => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS'),
                'type'       => 'value'
            ],
            'published'     => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('PROFILE_PUBLISHED'),
                'type'       => 'value'
            ],
            'editing'       => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('EDITING'),
                'type'       => 'value'
            ],
            'content'       => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('CONTENTS'),
                'type'       => 'value'
            ],
            'block'         => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('USER_ENABLED'),
                'type'       => 'value'
            ],
            'activated'     => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('USER_ACTIVATION'),
                'type'       => 'value'
            ],
            'lastvisitDate' => [
                'column'     => 'lastvisitDate',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('VISITED'),
                'type'       => 'sort'
            ],
            'registerDate'  => [
                'column'     => 'registerDate',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('REGISTERED'),
                'type'       => 'sort'
            ]
        ];
    }
}
