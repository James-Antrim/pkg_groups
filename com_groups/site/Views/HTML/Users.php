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
use Joomla\CMS\Toolbar\Button\DropdownButton;
use THM\Groups\Adapters\{HTML, Toolbar};
use THM\Groups\Helpers\{Can, Users as Helper};
use stdClass;
use THM\Groups\Layouts\ListItem;

/**
 * View class for displaying available persons.
 */
class Users extends ListView
{
    /** @inheritDoc */
    protected function addToolbar(): void
    {
        // Get the toolbar object instance
        $toolbar = Toolbar::getInstance();

        /*if (Can::create())
        {
            $toolbar->addNew('Person.add');
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
                    ->task('Users.publish')
                    ->listCheck(true);
                $childBar->standardButton('unblock', 'GROUPS_UNPUBLISH_PROFILE')
                    ->icon('fa fa-eye-slash')
                    ->task('Users.unpublish')
                    ->listCheck(true);
                $childBar->standardButton('enableEditing', 'GROUPS_ENABLE_EDITING')
                    ->icon('fa fa-edit')
                    ->task('Users.enableEditing')
                    ->listCheck(true);
                $childBar->standardButton('disableEditing', 'GROUPS_DISABLE_EDITING')
                    ->icon('fa fa-minus-circle')
                    ->task('Users.disableEditing')
                    ->listCheck(true);
                $childBar->standardButton('enableContent', 'GROUPS_ENABLE_CONTENT')
                    ->icon('fa fa-folder-open')
                    ->task('Users.enableContent')
                    ->listCheck(true);
                $childBar->standardButton('disableContent', 'GROUPS_DISABLE_CONTENT')
                    ->icon('fa fa-folder')
                    ->task('Users.disableContent')
                    ->listCheck(true);
                $childBar->standardButton('unblock', 'GROUPS_UNBLOCK_USER')
                    ->icon('fa fa-door-open')
                    ->task('Users.block')
                    ->listCheck(true);
                $childBar->standardButton('block', 'GROUPS_BLOCK_USER')
                    ->icon('fa fa-door-closed')
                    ->task('Users.block')
                    ->listCheck(true);
                $childBar->standardButton('activate', 'GROUPS_ACTIVATE_USER')
                    ->icon('fa fa-check-square')
                    ->task('Users.activate')
                    ->listCheck(true);

                if (Can::batchProcess()) {
                    $childBar->popupButton('batch')
                        ->selector('collapseModal')
                        ->text('GROUPS_BATCH_PROCESSING')
                        ->listCheck(true);
                }

                if (Can::delete()) {
                    $childBar->delete('Users.delete')
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
        $item->activated     = HTML::toggle($index, Helper::activatedStates[$item->activated], 'Users');
        $item->block         = HTML::toggle($index, Helper::blockedStates[$item->block], 'Users');
        $item->content       = HTML::toggle($index, Helper::contentStates[$item->content], 'Users');
        $item->editing       = HTML::toggle($index, Helper::editingStates[$item->editing], 'Users');
        $item->editLink      = Route::_('index.php?option=com_groups&view=user&id=' . $item->id);
        $item->groups        = $this->formatGroups($item->groups);
        $item->lastvisitDate = $item->lastvisitDate ?: Text::_('GROUPS_NEVER');
        $item->name          = $item->forenames ? "$item->forenames $item->surnames" : $item->surnames;
        $item->published     = HTML::toggle($index, Helper::publishedStates[$item->published], 'Users');
        $item->viewLink      = Route::_('index.php?option=com_groups&view=profile&id=' . $item->id);
    }

    /** @inheritDoc */
    public function display($tpl = null): void
    {
        $this->allowBatch = Can::batchProcess();
        $this->todo       = [
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
            return Text::_('GROUPS_MULTIPLE_GROUPS');
        }

        foreach ($groups as $groupID => $group) {
            $roles = match (count($group['roles'])) {
                0 => '',
                1, 2 => ': ' . implode(' & ', $group['roles']),
                default => ': ' . Text::_('GROUPS_MULTIPLE_ROLES'),
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
                'link'       => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_PROFILE'),
                'type'       => 'sort'
            ],
            'groups'        => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_GROUPS'),
                'type'       => 'value'
            ],
            'published'     => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_PROFILE_PUBLISHED'),
                'type'       => 'value'
            ],
            'editing'       => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_EDITING'),
                'type'       => 'value'
            ],
            'content'       => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_CONTENTS'),
                'type'       => 'value'
            ],
            'block'         => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_USER_ENABLED'),
                'type'       => 'value'
            ],
            'activated'     => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_USER_ACTIVATION'),
                'type'       => 'value'
            ],
            'lastvisitDate' => [
                'column'     => 'lastvisitDate',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_VISITED'),
                'type'       => 'sort'
            ],
            'registerDate'  => [
                'column'     => 'registerDate',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_REGISTERED'),
                'type'       => 'sort'
            ]
        ];
    }
}
