<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Joomla\CMS\Toolbar\Button\DropdownButton;
use stdClass;
use THM\Groups\Adapters\{Application, HTML, Text, Toolbar};
use THM\Groups\Helpers\{Categories, Pages as Helper, Pages};
use THM\Groups\Layouts\HTML\Row;

/** @inheritDoc */
class Contents extends ListView
{
    /** @inheritDoc */
    protected function addToolbar(): void
    {
        $this->toDo[] = 'Everything :)';
        $this->toDo[] = 'Authors as field => associated with a category whether currently allowed or not.';
        $toolbar      = Toolbar::instance();

        if (Categories::root()) {
            // select articles and authors to add/reassign
            $this->allowBatch = true;
            $toolbar->popupButton('batch', Text::_('BATCH'))
                ->popupType('inline')
                ->textHeader(Text::_('BATCH'))
                ->url('#groups-batch')
                ->modalWidth('800px')
                ->modalHeight('fit-content')
                ->listCheck(true);

            $batchBar = Toolbar::instance('batch');
            $batchBar->standardButton('batch', Text::_('PROCESS'), 'contents.batch');

            /** @var DropdownButton $dropdown */
            $dropdown = $toolbar->dropdownButton('account-group', Text::_('USER_ACTIONS'))
                ->buttonClass('btn btn-action')
                ->icon('icon-ellipsis-h')
                ->listCheck(true);
            $dropdown->toggleSplit(false);
            $childBar = $dropdown->getChildToolbar();
            $childBar->publish('contents.feature', Text::_('FEATURE'));
            $childBar->publish('contents.unfeature', Text::_('UNFEATURE'));
        }
        else {
            Application::message('NO_ROOT', Application::NOTICE);
        }

        parent::addToolbar();
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        $this->toDo[] = 'Author/Category as subtitle';
        $this->toDo[] = 'J-Assoc and Language both as language column.';

        $item->featured = HTML::toggle($item->id, Pages::FEATURED_STATES[$item->featured], 'pages');
        $item->state    = HTML::toggle($index, Helper::STATES[$item->state], 'pages');
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $this->headers = [
            'check'    => ['type' => 'check'],
            'ordering' => ['active' => false, 'type' => 'ordering'],
            'name'     => [
                'column'     => 'title',
                'link'       => Row::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('TITLE'),
                'type'       => 'sort'
            ],
            'state'    => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('STATUS'),
                'type'       => 'value'
            ],
            'featured' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('FEATURED'),
                'type'       => 'value'
            ],
            'level'    => [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('LEVEL'),
                'type'       => 'value'
            ],
            'language' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('LANGUAGE'),
                'type'       => 'value'
            ],
            'id'       => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('ID'),
                'type'       => 'value'
            ]
        ];
    }
}
