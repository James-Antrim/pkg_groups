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

use Joomla\CMS\Language\{Associations, Multilanguage};
use Joomla\CMS\Toolbar\Button\DropdownButton;
use stdClass;
use THM\Groups\Adapters\{Application, HTML, Input, Text, Toolbar};
use THM\Groups\Helpers\{Categories, Pages as Helper};
use THM\Groups\Layouts\HTML\Row;

/** @inheritDoc */
class Contents extends ListView
{
    private bool $showDelete = false;
    private bool $showLanguages;

    /**
     * Constructor
     *
     * @param   array  $config  An optional associative array of configuration settings.
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->showLanguages = (Associations::isEnabled() and Multilanguage::isEnabled());
    }

    /** @inheritDoc */
    protected function addToolbar(): void
    {
        $this->toDo[] = 'Joomla batch functions for language and level. No current plans for tags implementation.';
        $this->toDo[] = 'Joomla batch functions for category with consequences if shoved into a profile category.';
        $this->toDo[] = 'Add view language and level filter.';

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

            if ($this->showDelete) {
                $toolbar->delete('contents.delete', Text::_('EMPTY_TRASH'));
            }
        }
        else {
            Application::message('NO_ROOT', Application::NOTICE);
        }

        parent::addToolbar();
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        $item->featured = HTML::toggle($index, Helper::FEATURED_STATES[$item->featured], 'contents');

        if ($this->showLanguages) {
            $item->language = Helper::languageDisplay($item);
        }

        if ($item->state === Helper::TRASHED) {
            $this->showDelete = true;
        }

        $item->state = HTML::toggle($index, Helper::STATES[$item->state], 'contents');

        if ($checkin = HTML::toggle($index, Helper::CHECKED_STATES[(int) ($item->checked_out > 0)], 'contents')) {
            $item->title = "$checkin $item->title";
        }

        $item->user = $item->forenames ? "$item->surnames, $item->forenames" : $item->surnames;
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $this->headers = ['check' => ['type' => 'check']];

        $userID = Input::integer('userID');
        if ($userID) {
            $this->headers['ordering'] = ['active' => false, 'type' => 'ordering'];
        }

        $this->headers['title'] = [
            'column'     => 'title',
            'link'       => Row::DIRECT,
            'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('TITLE'),
            'type'       => 'sort'
        ];

        if (!$userID) {
            $this->headers['user'] = [
                'column'     => 'user.surnames, user.forenames',
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('USER'),
                'type'       => 'sort'
            ];
        }

        $this->headers['state'] = [
            'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('STATUS'),
            'type'       => 'value'
        ];

        $this->headers['featured'] = [
            'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('FEATURED'),
            'type'       => 'value'
        ];

        $this->headers['level'] = [
            'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('LEVEL'),
            'type'       => 'value'
        ];

        if ($this->showLanguages) {
            $this->headers['language'] = [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('LANGUAGE'),
                'type'       => 'value'
            ];
        }

        $this->headers['id'] = [
            'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('ID'),
            'type'       => 'value'
        ];
    }
}
