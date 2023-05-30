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
use THM\Groups\Adapters\{HTML, Text};
use THM\Groups\Helpers\Attributes as Helper;

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
            'Add edit links'
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
        $unlabeledAttributes = Helper::getUnlabeled();
        $unlabeledTip        = Text::_('GROUPS_TOGGLE_TIP_UNLABELED');
        foreach ($this->items as $rowNo => $item) {

            $neither = in_array($item->id, $unlabeledAttributes) ? $unlabeledTip : '';

            if (in_array($item->id, Helper::PROTECTED)) {
                $context    = "groups-attribute-$item->id";
                $item->name = HTML::icon('fa fa-lock') . " $item->name";
                $tip        = Text::_('GROUPS_PROTECTED_ATTRIBUTE');
                $item->name = HTML::tip($item->name, $context, $tip);
            }

            $item->icon      = '';
            $item->showIcon  = HTML::toggle($rowNo, Helper::showIconStates[$item->showIcon], 'Attributes', $neither);
            $item->showLabel = HTML::toggle($rowNo, Helper::showLabelStates[$item->showLabel], 'Attributes', $neither);

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
            'showIcon' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_SHOW_ICON'),
                'type' => 'value'
            ],
            'showLabel' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_SHOW_LABEL'),
                'type' => 'value'
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
