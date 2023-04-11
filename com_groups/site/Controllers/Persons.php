<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use Joomla\CMS\Language\Text;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Can;
use THM\Groups\Models\ListModel;

/**
 * Controller class for groups.
 */
class Persons extends Controller
{
    /**
     * Activates the selected users.
     * @return void
     */
    public function activate(): void
    {
        if (!Can::changeState())
        {
            Application::error(403);
        }

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = ListModel::updateBool('Users', 'activation', $selectedIDs, false);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Blocks the selected users.
     * @return void
     */
    public function block(): void
    {
        if (!Can::changeState())
        {
            Application::error(403);
        }

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = ListModel::updateBool('Users', 'block', $selectedIDs, true);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Disables content management for the selected users.
     * @return void
     * @todo integrate component parameters
     * @todo integrate category suppression
     */
    public function disableContent(): void
    {
        if (!Can::changeState())
        {
            Application::error(403);
        }

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = ListModel::updateBool('Persons', 'content', $selectedIDs, false);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Disables profile editing for the selected users.
     * @return void
     * @todo integrate component parameters
     */
    public function disableEditing(): void
    {
        if (!Can::changeState())
        {
            Application::error(403);
        }

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = ListModel::updateBool('Persons', 'editing', $selectedIDs, false);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Enables content management for the selected users.
     * @return void
     * @todo integrate component parameters
     * @todo integrate category creation
     */
    public function enableContent(): void
    {
        if (!Can::changeState())
        {
            Application::error(403);
        }

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = ListModel::updateBool('Persons', 'content', $selectedIDs, true);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Enables profile editing for the selected users.
     * @return void
     * @todo integrate component parameters
     */
    public function enableEditing(): void
    {
        if (!Can::changeState())
        {
            Application::error(403);
        }

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = ListModel::updateBool('Persons', 'editing', $selectedIDs, true);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Filters selected persons for the current user.
     * @param array $selectedIDs
     *
     * @return array
     */
    private function filterSelected(array $selectedIDs): array
    {
        $userID = $this->getUserID();

        return in_array($userID, $selectedIDs) ? [$userID] : [];
    }

    /**
     * Publishes the selected persons' profiles.
     * @return void
     */
    public function publish(): void
    {
        $this->togglePublished(true);
    }

    /**
     * Toggles the values of the published column.
     * @param bool $value
     *
     * @return void
     */
    private function togglePublished(bool $value): void
    {
        $selectedIDs = Input::getSelectedIDs();

        if (!Can::changeState())
        {
            $selectedIDs = $this->filterSelected($selectedIDs);
        }

        if (empty($selectedIDs))
        {
            Application::error(403);
        }

        $selected = count($selectedIDs);
        $updated  = ListModel::updateBool('Persons', 'published', $selectedIDs, $value);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Unblocks the selected users.
     * @return void
     */
    public function unblock(): void
    {
        if (!Can::changeState())
        {
            Application::error(403);
        }

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = ListModel::updateBool('Users', 'block', $selectedIDs, false);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Unpublishes the selected persons' profiles.
     * @return void
     */
    public function unpublish(): void
    {
        $this->togglePublished(false);
    }

    /**
     * An extract for providing a message for the number of entries updated.
     *
     * @param int $selected
     * @param int $updated
     *
     * @return void
     */
    private function updateMessage(int $selected, int $updated): void
    {
        if ($selected === $updated)
        {
            $message = $updated === 1 ? Text::_('GROUPS_1_UPDATED', $updated) : Text::sprintf('GROUPS_X_UPDATED', $updated);
            $type    = Application::MESSAGE;
        }
        else
        {
            $message = Text::sprintf('GROUPS_XX_UPDATED', $updated, $selected);
            $type    = Application::WARNING;
        }

        Application::message($message, $type);
    }
}