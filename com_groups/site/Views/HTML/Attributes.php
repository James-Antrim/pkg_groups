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
use stdClass;
use THM\Groups\Helpers\Attributes as Helper;
use THM\Groups\Layouts\ListItem;

/** @inheritDoc */
class Attributes extends ListView
{
    /** @inheritDoc */
    protected function addToolbar(): void
    {
        $this->toDo[] = 'Add a type migration dialog';
        // Manage access is a prerequisite for getting this far
        $toolbar = Toolbar::instance();
        $toolbar->addNew('Attributes.add');
        $toolbar->delete('Attributes.delete', 'GROUPS_DELETE')->message('JGLOBAL_CONFIRM_DELETE')->listCheck(true);
        $toolbar->divider();

        parent::addToolbar();
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        $neither = in_array($item->id, $options['unlabeledIDs']) ? $options['unlabeledTip'] : '';
        $query   = $options['query'];

        if (in_array($item->id, Helper::PROTECTED)) {
            $context    = "groups-attribute-$item->id";
            $item->name = HTML::icon('fa fa-lock') . " $item->name";
            $tip        = Text::_('GROUPS_PROTECTED_ATTRIBUTE');
            $item->name = HTML::tip($item->name, $context, $tip);
        }

        $item->link      = Route::_($query . $item->id);
        $icon            = ($item->showIcon and $item->icon) ? ' ' . HTML::icon($item->icon) : '';
        $item->icon      = '';
        $item->showIcon  = HTML::toggle($index, Helper::showIconStates[$item->showIcon], 'Attributes', $neither) . $icon;
        $item->showLabel = HTML::toggle($index, Helper::showLabelStates[$item->showLabel], 'Attributes', $neither);
    }

    /** @inheritDoc */
    protected function completeItems(array $options = []): void
    {
        $options = [
            'query'        => 'index.php?option=com_groups&view=attribute&id=',
            'unlabeledIDs' => Helper::unlabeledIDs(),
            'unlabeledTip' => Text::_('TOGGLE_TIP_UNLABELED')
        ];

        parent::completeItems($options);
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $this->headers = [
            'check'     => ['type' => 'check'],
            'ordering'  => ['active' => false, 'type' => 'ordering'],
            'name'      => [
                'link'       => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_ATTRIBUTE'),
                'type'       => 'value'
            ],
            'showIcon'  => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_SHOW_ICON'),
                'type'       => 'value'
            ],
            'showLabel' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_SHOW_LABEL'),
                'type'       => 'value'
            ],
            'input'     => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_INPUT'),
                'type'       => 'text'
            ],
            'output'    => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_OUTPUT'),
                'type'       => 'text'
            ],
            'level'     => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_LEVEL'),
                'type'       => 'text'
            ],
        ];

        if ($this->filtered()) {
            unset($this->headers['ordering']);
        }
    }
}
