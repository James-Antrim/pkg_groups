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
use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Adapters\HTML;
use THM\Groups\Helpers\Templates as Helper;
use THM\Groups\Layouts\ListItem;

/**
 * View class for displaying available roles.
 */
class Templates extends ListView
{
    /**
     * Add the page title and toolbar.
     * @return  void
     */
    protected function addToolbar(): void
    {
        // Manage access is a prerequisite for getting this far
        ToolbarHelper::addNew('Templates.add');
        ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Templates.delete');
        ToolbarHelper::divider();

        parent::addToolbar();
    }

    /**
     * @inheritDoc
     */
    protected function completeItems(): void
    {
        foreach ($this->items as $rowNo => $item) {
            $item->cards  = HTML::toggle($rowNo, Helper::CARDS[$item->cards], 'Templates');
            $item->roles  = HTML::toggle($rowNo, Helper::ROLES[$item->roles], 'Templates');
            $item->vcards = HTML::toggle($rowNo, Helper::VCARDS[$item->vcards], 'Templates');
        }
    }

    /**
     * @inheritDoc
     */
    public function display($tpl = null): void
    {
        $this->todo = [];

        parent::display($tpl);
    }

    /**
     * @inheritDoc
     */
    protected function initializeHeaders(): void
    {
        $this->headers = [
            'check' => ['type' => 'check'],
            'name' => [
                'link' => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_TEMPLATE'),
                'type' => 'value'
            ],
            'cards' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_PROFILE_CARDS'),
                'type' => 'value'
            ],
            'roles' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_ROLES'),
                'type' => 'value'
            ],
            'vcards' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_VCARDS'),
                'type' => 'value'
            ]
        ];
    }
}
