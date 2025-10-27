<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.3
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
    use Contented;

    public bool $allowBatch = true;
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
        if (Categories::root()) {
            $controller = strtolower(Application::uqClass($this));
            $toolbar    = Toolbar::instance();

            $toolbar->addNew('contents.add');

            /** @var DropdownButton $dropdown */
            $dropdown = $toolbar->dropdownButton($controller)
                ->buttonClass('btn btn-action')
                ->icon('icon-ellipsis-h')
                ->listCheck(true);
            $dropdown->toggleSplit(false);
            $childBar = $dropdown->getChildToolbar();
            $childBar->publish("$controller.publish");
            $childBar->unpublish("$controller.hide");
            $childBar->archive("$controller.archive");
            $childBar->trash("$controller.trash");
            $childBar->standardButton('feature', Text::_('FEATURE'), "$controller.feature")->icon('fa fa-eye');
            $childBar->standardButton('unfeature', Text::_('UNFEATURE'), "$controller.unfeature")->icon('fa fa-eye-slash');
            $childBar->popupButton('batch', Text::_('BATCH'))
                ->popupType('inline')
                ->textHeader(Text::_('BATCH'))
                ->url('#groups-batch')
                ->modalWidth('800px')
                ->modalHeight('fit-content');

            $batchBar = Toolbar::instance('batch');
            $batchBar->standardButton('batch', Text::_('PROCESS'), "$controller.batch");

            if ($this->showDelete) {
                // No list check necessary as function can be applied generally in the displayed context.
                $toolbar->delete("$controller.delete", Text::_('EMPTY_TRASH'));
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
        $controller     = strtolower(Application::uqClass($this));
        $item->featured = HTML::toggle($index, Helper::FEATURED_STATES[$item->featured], $controller);

        if ($this->showLanguages) {
            $item->language = Helper::languageDisplay($item);
        }

        if ($item->state === Helper::TRASHED) {
            $this->showDelete = true;
        }

        $item->state = HTML::toggle($index, Helper::STATES[$item->state], $controller);

        if ($checkin = HTML::toggle($index, Helper::CHECKED_STATES[(int) ($item->checked_out > 0)], $controller)) {
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

        $this->filteredColumns();
    }
}
