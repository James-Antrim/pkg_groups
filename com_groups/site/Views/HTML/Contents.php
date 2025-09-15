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
use THM\Groups\Helpers\{Categories, Pages as Helper};
use THM\Groups\Layouts\HTML\Row;

/** @inheritDoc */
class Contents extends ListView
{
    /** @inheritDoc */
    protected function addToolbar(): void
    {
        $this->toDo[] = 'Authors as filter field => associated with a category whether currently allowed or not.';
        $this->toDo[] = 'Joomla batch functions for language and level. No current plans for tags implementation.';
        $this->toDo[] = 'Joomla batch functions for category with consequences if shoved into a profile category.';
        $this->toDo[] = 'Show all contents regardless of relevance, but filter for relevance on initial display.';
        $this->toDo[] = 'Delete button if set to trashed state.';
        $this->toDo[] = 'Remove columns when corresponding filter is set';
        $this->toDo[] = 'Form the title as joomla content list.';
        $this->toDo[] = 'Author/Category as subtitle';
        $this->toDo[] = 'J-Assoc and Language both as language column.';

        if (Categories::root()) {
            $toolbar = Toolbar::instance();

            $toolbar->addNew('contents.add');

            // select articles and authors to add/reassign
            $this->allowBatch = true;

            /** @var DropdownButton $dropdown */
            $dropdown = $toolbar->dropdownButton('contents')
                ->buttonClass('btn btn-action')
                ->icon('icon-ellipsis-h')
                ->listCheck(true);
            $dropdown->toggleSplit(false);
            $childBar = $dropdown->getChildToolbar();
            $childBar->publish('contents.publish');
            $childBar->unpublish('contents.hide');
            $childBar->archive('contents.archive');
            $childBar->trash('contents.trash');
            $childBar->standardButton('feature', Text::_('FEATURE'), 'contents.feature')->icon('fa fa-eye');
            $childBar->standardButton('unfeature', Text::_('UNFEATURE'), 'contents.unfeature')->icon('fa fa-eye-slash');
            $childBar->popupButton('batch', Text::_('BATCH'))
                ->popupType('inline')
                ->textHeader(Text::_('BATCH'))
                ->url('#groups-batch')
                ->modalWidth('800px')
                ->modalHeight('fit-content');

            $batchBar = Toolbar::instance('batch');
            $batchBar->standardButton('batch', Text::_('PROCESS'), 'contents.batch');
        }
        else {
            Application::message('NO_ROOT', Application::NOTICE);
        }

        parent::addToolbar();
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        $item->featured = HTML::toggle($item->id, Helper::FEATURED_STATES[$item->featured], 'contents');
        $item->state    = HTML::toggle($item->id, Helper::STATES[$item->state], 'contents');
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
