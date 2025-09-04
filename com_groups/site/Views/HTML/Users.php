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
        $this->toDo[] = 'Expand system plugin to overwrite com_users => users view links to this view.';
        $this->toDo[] = 'Implement the add feature.';
        $this->toDo[] = 'Test batch functions.';

        // Get the toolbar object instance
        $toolbar = Toolbar::instance();

        if (Can::create()) {
            $toolbar->addNew('users.add');
        }

        if (Can::changeState()) {
            /** @var DropdownButton $accDD */
            $accDD = $toolbar->dropdownButton('account-group', Text::_('USER_ACTIONS'))
                ->buttonClass('btn btn-action')
                ->icon('icon-ellipsis-h');
            $accDD->toggleSplit(false);
            $accBar = $accDD->getChildToolbar();

            $accBar->standardButton('block', Text::_('BLOCK_USER'), 'users.block')->icon('fa fa-door-closed');
            $accBar->standardButton('unblock', Text::_('UNBLOCK_USER'), 'users.unblock')->icon('fa fa-door-open');
            $accBar->standardButton('activate', Text::_('ACTIVATE_USER'), 'users.activate')->icon('fa fa-check-square');

            if (Can::batchProcess()) {
                $this->allowBatch = true;
                $accBar->popupButton('batch', Text::_('BATCH'))
                    ->modalHeight('fit-content')
                    ->modalWidth('800px')
                    ->popupType('inline')
                    ->textHeader(Text::_('BATCH'))
                    ->url('#groups-batch');
                $batchBar = Toolbar::instance('batch');
                $batchBar->standardButton('batch', Text::_('PROCESS'), 'groups.batch');
            }

            if (Can::delete()) {
                $accBar->delete('users.delete', Text::_('REMOVE'))->message(Text::_('DELETE_CONFIRMATION'));
            }

            /** @var DropdownButton $profileDD */
            $profileDD = $toolbar->dropdownButton('profile-group', Text::_('PROFILE_ACTIONS'))
                ->buttonClass('btn btn-action')
                ->icon('icon-ellipsis-h');
            $profileDD->toggleSplit(false);
            $profileBar = $profileDD->getChildToolbar();

            $profileBar->standardButton('publish', Text::_('PUBLISH_PROFILE'), 'users.publish')->icon('fa fa-eye');
            $profileBar->standardButton('hide', Text::_('HIDE_PROFILE'), 'users.hide')->icon('fa fa-eye-slash');
            $profileBar->standardButton('enableEditing', Text::_('ENABLE_EDITING'), 'users.enableEditing')
                ->icon('fa fa-edit');
            $profileBar->standardButton('disableEditing', Text::_('DISABLE_EDITING'), 'users.disableEditing')
                ->icon('fa fa-minus-circle');
            $profileBar->standardButton('enableContent', Text::_('ENABLE_CONTENT'), 'users.enableContent')
                ->icon('fa fa-folder-open');
            $profileBar->standardButton('disableContent', Text::_('DISABLE_CONTENT'), 'users.disableContent')
                ->icon('fa fa-folder');
        }

        /*if (Can::configure()) {
            $toolbar->preferences('com_users');
        }*/

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
                'title'      => Text::_('USER'),
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
                'title'      => Text::_('ENABLED'),
                'type'       => 'value'
            ],
            'activated'     => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('ACTIVATED'),
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
