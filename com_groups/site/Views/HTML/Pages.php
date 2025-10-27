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

use THM\Groups\Adapters\{Application, Input, Text, User};
use THM\Groups\Helpers\{Can, Profiles, Users};
use THM\Groups\Layouts\HTML\Row;

/**
 * Class displays content in the profile's content category
 */
class Pages extends Contents
{
    public bool $own = false;

    public int $profileID;

    /** @inheritDoc */
    public function addToolBar(): void
    {
        parent::addToolBar();

        $title = $this->own ? Text::_('MY_PAGES') : Text::sprintf('MANAGE_PAGES', Profiles::name($this->profileID));

        $this->title($title);
    }

    /** @inheritDoc */
    protected function authorize(): void
    {
        // No public access
        if (!$userID = User::id()) {
            Application::error(401);
        }

        $this->profileID = Input::integer('profileID', $userID);

        // Profile content disabled
        if (!Users::categoryID($this->profileID) or !Users::content($this->profileID)) {
            Application::error(412);
        }

        $this->own = $this->profileID === $userID;

        // Current user has no content access
        if (!Can::manage('com_content') and !$this->own) {
            Application::error(403);
        }
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $this->headers = [
            'check'    => ['type' => 'check'],
            'ordering' => ['active' => false, 'type' => 'ordering'],
            'title'    => [
                'column'     => 'title',
                'link'       => Row::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('TITLE'),
                'type'       => 'sort'
            ]
        ];

        $this->filteredColumns();
    }
}
