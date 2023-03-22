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
use Joomla\CMS\Router\Route;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers;
use THM\Groups\Layouts\ListItem;

/**
 * View class for displaying available profiles.
 */
class Profiles extends ListView
{
    /**
     * @inheritDoc
     */
    public function display($tpl = null)
    {
        if ($this->backend and !Helpers\Can::manage())
        {
            Application::error(403);
        }

        $this->todo = [
            'Profiles => Persons',
            'Add note',
            'published + filter',
            'active + filter',
            'groups + filter',
            'last visit filter ',
            'registered filter',
            'batch stuff'
        ];

        parent::display($tpl);
    }

    /**
     * @inheritDoc
     */
    protected function completeItems()
    {
        foreach ($this->items as $item)
        {
            $item->lastvisitDate = $item->lastvisitDate ?: Text::_('GROUPS_NEVER');
            $item->editLink      = Route::_('index.php?option=com_groups&view=Profile&layout=edit&id=' . $item->id);
            $item->viewLink      = Route::_('index.php?option=com_groups&view=Profile&id=' . $item->id);
        }
    }

    /**
     * @inheritDoc
     */
    protected function initializeHeaders()
    {
        $this->headers = [
            'check' => ['type' => 'check'],
            'fullName' => [
                'column' => 'surnames, forenames',
                'link' => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_PROFILE'),
                'type' => 'sort'
            ],
            'username' => [
                'column' => 'username',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_USERNAME'),
                'type' => 'sort'
            ],
            'email' => [
                'column' => 'email',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_USER_EMAIL'),
                'type' => 'text'
            ],
            'lastvisitDate' => [
                'column' => 'lastvisitDate',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_VISITED'),
                'type' => 'sort'
            ],
            'registerDate' => [
                'column' => 'registerDate',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_REGISTERED'),
                'type' => 'sort'
            ],
            'id' => [
                'column' => 'u.id',
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_ID'),
                'type' => 'sort'
            ]
        ];

    }
}
