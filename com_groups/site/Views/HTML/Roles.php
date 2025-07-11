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
use stdClass;
use THM\Groups\Adapters\Text;
use THM\Groups\Layouts\HTML\Row;

/**
 * View class for displaying available roles.
 */
class Roles extends ListView
{
    /**
     * Add the page title and toolbar.
     * @return  void
     */
    protected function addToolbar(): void
    {
        // Manage access is a prerequisite for getting this far
        ToolbarHelper::addNew('Roles.add');
        ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'Roles.delete');
        ToolbarHelper::divider();

        parent::addToolbar();
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $this->headers = [
            'check'    => ['type' => 'check'],
            'ordering' => ['type' => 'ordering'],
            'name'     => [
                'link'       => Row::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('ROLE'),
                'type'       => 'value'
            ],
            'plural'   => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('PLURAL'),
                'type'       => 'text'
            ],
            'groups'   => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS'),
                'type'       => 'value'
            ]
        ];

        if ($this->filtered()) {
            unset($this->headers['ordering']);
        }
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        // Simple resource with no further processing necessary.
    }
}
