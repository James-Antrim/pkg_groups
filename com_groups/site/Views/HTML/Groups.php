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

use Joomla\CMS\Helper\UserGroupsHelper as UGH;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Button\DropdownButton;
use stdClass;
use THM\Groups\Adapters\{HTML, Text, Toolbar};
use THM\Groups\Helpers\{Can, Groups as Helper};
use THM\Groups\Layouts\HTML\Row;

/**
 * View class for displaying available groups.
 */
class Groups extends ListView
{
    /** @inheritDoc */
    protected function addToolbar(): void
    {
        $this->toDo[] = 'Add associated template to batch and rows.';

        $toolbar = Toolbar::instance();

        if (Can::create()) {
            $toolbar->addNew('groups.add');
        }

        /** @var DropdownButton $dropdown */
        $dropdown = $toolbar->dropdownButton('status-group', Text::_('ACTIONS'))
            ->buttonClass('btn btn-action')
            ->icon('icon-ellipsis-h')
            ->listCheck(true);
        $dropdown->toggleSplit(false);
        $childBar = $dropdown->getChildToolbar();

        if (Can::batchProcess()) {
            $this->allowBatch = true;
            $childBar->popupButton('batch', Text::_('BATCH_LEVELS'))
                ->popupType('inline')
                ->textHeader(Text::_('BATCH_LEVELS'))
                ->url('#groups-batch')
                ->modalWidth('800px')
                ->modalHeight('fit-content')
                ->listCheck(true);

            $batchBar = Toolbar::instance('batch');
            $batchBar->standardButton('batch', Text::_('PROCESS'), 'groups.batch');
        }

        if (Can::delete()) {
            $childBar->delete('groups.delete', Text::_('REMOVE'))->message(Text::_('DELETE_CONFIRMATION'))->listCheck(true);
        }

        parent::addToolbar();
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        $ugh = UGH::getInstance();

        if ($this->filtered()) {
            // The last item is the $item->name
            array_pop($item->path);

            foreach ($item->path as $key => $parentID) {
                $item->path[$key] = $ugh->get($parentID)->title;
            }
            $item->supplement = implode(' / ', $item->path);
        }
        else {
            $item->prefix = Helper::prefix($item->level);
        }

        if (in_array($item->id, Helper::STANDARD_GROUPS)) {
            $context = "groups-group-$item->id";
            $tip     = Text::_('PROTECTED_GROUP');

            $item->icon = HTML::tip(HTML::icon('lock'), $context, $tip);
        }

        if (!$this->state->get('filter.roleID')) {
            $roles = Helper::roles($item->id);
            $count = count($roles);

            //$item->viewLevel = implode(', ', $levels);
            switch (true) {
                case $count === 0:
                    $item->role = Text::_('NONE');
                    break;
                case $count === 1:
                    // Doesn't take up too much space I hope...
                case $count === 2:
                case $count === 3:
                    $item->viewLevel = implode(', ', $roles);
                    break;
                default:
                    $item->role = Text::_('MULTIPLE');
                    break;

            }
        }

        if (!$this->state->get('filter.levelID')) {
            $levels = Helper::levels($item->id);
            $count  = count($levels);

            $item->viewLevel = match (true) {
                $count === 0 => Text::_('NONE'),
                $count > 2 => Text::_('MULTIPLE'),
                default => implode(', ', $levels),
            };
        }

        if ($item->enabled or $item->blocked) {
            $link = "index.php?option=com_groups&view=users&filter[groupID]=$item->id&filter[state]=";

            $eLink       = Route::_($link . 1);
            $properties  = ['class' => 'btn btn-success'];
            $tip         = Text::_('ENABLED_USERS');
            $item->users = HTML::tip($item->enabled, "enabled-tip-$item->id", $tip, $properties, $eLink);

            $bLink       = Route::_($link . 0);
            $properties  = ['class' => 'btn btn-danger'];
            $tip         = Text::_('BLOCKED_USERS');
            $item->users .= HTML::tip($item->blocked, "blocked-tip-$item->id", $tip, $properties, $bLink);
        }
        else {
            $item->users = '';
        }

        $link         = "index.php?option=com_users&view=debuggroup&group_id=$item->id";
        $tip          = Text::_('DEBUG_GROUP_RIGHTS');
        $icon         = HTML::icon('fas fa-th');
        $item->rights = HTML::tip($icon, "rights-tip-$item->id", $tip, [], $link, true);
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $this->headers = [
            'check' => ['type' => 'check'],
            'name'  => [
                'link'       => Row::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUP'),
                'type'       => 'value'
            ]
        ];

        if (!$this->state->get('filter.roleID')) {
            $this->headers['role'] = [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('ROLE'),
                'type'       => 'value'
            ];
        }

        if (!$this->state->get('filter.levelID')) {
            $this->headers['viewLevel'] = [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('LEVEL'),
                'type'       => 'value'
            ];
        }

        $this->headers['users'] = [
            'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('USERS'),
            'type'       => 'value'
        ];

        $this->headers['id'] = [
            'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('ID'),
            'type'       => 'value'
        ];

        $this->headers['rights'] = [
            'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('RIGHTS'),
            'type'       => 'value'
        ];
    }
}
