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
use stdClass;
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

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        $icon             = HTML::icon('fa fa-list-ol');
        $tip              = Text::_('GROUPS_TEMPLATE_ATTRIBUTES_TIP');
        $item->attributes = HTML::tip($icon, "attributes-tip-$item->id", $tip, [], $item->attributes);
        $item->cards      = HTML::toggle($index, Helper::CARDS[$item->cards], 'Templates');
        $item->roles      = HTML::toggle($index, Helper::ROLES[$item->roles], 'Templates');
        $item->vcards     = HTML::toggle($index, Helper::VCARDS[$item->vcards], 'Templates');
    }

    /** @inheritDoc */
    public function display($tpl = null): void
    {
        $this->todo = [];

        parent::display($tpl);
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $this->headers = [
            'check'      => ['type' => 'check'],
            'name'       => [
                'link'       => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_TEMPLATE'),
                'type'       => 'value'
            ],
            'attributes' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_ATTRIBUTES'),
                'type'       => 'value'
            ],
            'cards'      => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_CARDS'),
                'type'       => 'value'
            ],
            'roles'      => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_ROLES'),
                'type'       => 'value'
            ],
            'vcards'     => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_VCARDS'),
                'type'       => 'value'
            ]
        ];
    }
}
