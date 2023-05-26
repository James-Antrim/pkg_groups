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

use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\{HTML, Text};

/**
 * View class for displaying available attribute types.
 */
class Attributes extends ListView
{
    /**
     * @inheritDoc
     */
    protected function addToolbar(): void
    {
        $this->todo = [
            'Add toggles for icon / label use and publication.',
            'Add locked icon for protected ',
            'Add ordering.'
        ];

        // Manage access is a prerequisite for getting this far
        ToolbarHelper::addNew('Attributes.add');
        ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Attributes.delete');
        ToolbarHelper::divider();

        parent::addToolbar();
    }

    /**
     * @inheritDoc
     */
    protected function completeItems(): void
    {
        foreach ($this->items as $item) {
            $label = 'label_' . Application::getTag();
            if ($item->showIcon and $item->icon) {
                $item->label = HTML::icon($item->icon);
            } elseif ($item->showLabel and $item->$label) {
                $item->label = $item->$label;
            } else {
                $item->label = '';
            }

            $item->icon = '';

            /*$item->context = match ($item->context)
            {
                Helpers\Attributes::GROUPS_CONTEXT => Text::_('GROUPS_GROUPS'),
                Helpers\Attributes::PERSONS_CONTEXT => Text::_('GROUPS_PROFILES'),
                default => Text::_('GROUPS_GROUPS_AND_PROFILES'),
            };*/
        }
    }

    /**
     * @inheritDoc
     */
    protected function initializeHeaders(): void
    {
        $this->headers = [
            'check' => ['type' => 'check'],
            'name' => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_ATTRIBUTE'),
                'type' => 'text'
            ],
            'label' => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_LABEL'),
                'type' => 'text'
            ],
            'input' => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_INPUT'),
                'type' => 'text'
            ],
            /*'context' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_CONTEXT'),
                'type' => 'text'
            ],*/
            'level' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_LEVEL'),
                'type' => 'text'
            ],
        ];
    }
}
