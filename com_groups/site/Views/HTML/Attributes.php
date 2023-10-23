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

use Joomla\CMS\Router\Route;
use THM\Groups\Adapters\{HTML, Text, Toolbar};
use THM\Groups\Helpers\Attributes as Helper;
use THM\Groups\Layouts\ListItem;

/**
 * View class for displaying available attribute types.
 * @todo Add a type migration dialog.
 */
class Attributes extends ListView
{
    /**
     * @inheritDoc
     */
    protected function addToolbar(): void
    {
        // Manage access is a prerequisite for getting this far
        $toolbar = Toolbar::getInstance();
        $toolbar->addNew('Attributes.add');
        $toolbar->delete('Attributes.delete', 'GROUPS_DELETE')->message('JGLOBAL_CONFIRM_DELETE')->listCheck(true);
        $toolbar->divider();

        parent::addToolbar();
    }

    /**
     * @inheritDoc
     */
    protected function completeItems(): void
    {
        $unlabeledAttributes = Helper::getUnlabeled();
        $unlabeledTip        = Text::_('GROUPS_TOGGLE_TIP_UNLABELED');
        $query               = 'index.php?option=com_groups&view=Attribute&id=';

        foreach ($this->items as $rowNo => $item) {

            $neither = in_array($item->id, $unlabeledAttributes) ? $unlabeledTip : '';

            if (in_array($item->id, Helper::PROTECTED)) {
                $context    = "groups-attribute-$item->id";
                $item->name = HTML::icon('fa fa-lock') . " $item->name";
                $tip        = Text::_('GROUPS_PROTECTED_ATTRIBUTE');
                $item->name = HTML::tip($item->name, $context, $tip);
            }

            $item->link      = Route::_($query . $item->id);
            $icon            = ($item->showIcon and $item->icon) ? ' ' . HTML::icon($item->icon) : '';
            $item->icon      = '';
            $item->showIcon  = HTML::toggle($rowNo, Helper::showIconStates[$item->showIcon], 'Attributes', $neither) . $icon;
            $item->showLabel = HTML::toggle($rowNo, Helper::showLabelStates[$item->showLabel], 'Attributes', $neither);
        }
    }

    /**
     * @inheritDoc
     */
    protected function initializeHeaders(): void
    {
        $this->headers = [
            'check' => ['type' => 'check'],
            'ordering' => ['active' => false, 'type' => 'ordering'],
            'name' => [
                'link' => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_ATTRIBUTE'),
                'type' => 'value'
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
            'output' => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_OUTPUT'),
                'type' => 'text'
            ],
            'level' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_LEVEL'),
                'type' => 'text'
            ],
        ];

        if ($this->filtered()) {
            unset($this->headers['ordering']);
        }
    }
}
