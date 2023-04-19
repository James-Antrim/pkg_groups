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

use Joomla\CMS\Access\Access;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\UserHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Can;
use THM\Groups\Tables\Users;

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

        $app         = Application::getApplication();
        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = 0;

        PluginHelper::importPlugin('user');

        // Access checks.
        foreach ($selectedIDs as $selectedID)
        {
            $users = new Users();

            if ($users->load($selectedID))
            {
                $current = $users->getProperties();

                if (empty($users->activation))
                {
                    continue;
                }

                $users->block      = 0;
                $users->activation = '';

                // Trigger the before save event.
                $result = $app->triggerEvent('onUserBeforeSave', [$current, false, $users->getProperties()]);

                if (in_array(false, $result, true))
                {
                    continue;
                }

                // Store the table.
                if (!$users->store())
                {
                    Application::message($users->getError(), Application::ERROR);
                    continue;
                }

                // Fire the after save event
                $app->triggerEvent('onUserAfterSave', [$users->getProperties(), false, true, null]);
            }
        }

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Blocks the selected users.
     * @return void
     */
    public function block(): void
    {
        $this->toggleBlock(true);
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
        $updated     = $this->updateBool('Persons', 'content', $selectedIDs, false);

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
        $updated     = $this->updateBool('Persons', 'editing', $selectedIDs, false);

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
        $updated     = $this->updateBool('Persons', 'content', $selectedIDs, true);

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
        $updated     = $this->updateBool('Persons', 'editing', $selectedIDs, true);

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
     * Toggles the block state of the user.
     *
     * @param bool $value
     *
     * @return void
     */
    private function toggleBlock(bool $value): void
    {
        if (!Can::changeState())
        {
            Application::error(403);
        }

        /** @var CMSApplication $app */
        $app         = Application::getApplication();
        $block       = $value === true;
        $selectedIDs = Input::getSelectedIDs();
        $user        = Application::getUser();

        $selected = count($selectedIDs);
        $updated  = 0;

        PluginHelper::importPlugin('user');

        // Prepare the logout options.
        $options = ['clientid' => $app->get('shared_session', '0') ? null : 0];

        foreach ($selectedIDs as $selectedID)
        {
            if ($block and $selectedID === $user->id)
            {
                Application::message(Text::_('GROUPS_CANNOT_BLOCK_SELF'), Application::ERROR);
                continue;
            }

            if (!$user->get('isRoot') && Access::check($selectedID, 'core.admin'))
            {
                Application::message(Text::_('GROUPS_CANNOT_BLOCK_SUPER_ADMIN'), Application::WARNING);
                continue;
            }

            $users = new Users();

            if ($users->load($selectedID))
            {
                $current = $users->getProperties();

                // Skip changing of same state
                if ($users->block == $value)
                {
                    continue;
                }

                $users->block = (int)$value;

                // If unblocking, also change password reset count to zero to unblock reset
                if (!$block)
                {
                    $users->resetCount = 0;
                }

                /**
                 * The check function is not called because the value set for the columns are predetermined and the
                 * existing values in other columns have nothing to do with this.
                 */

                $result = $app->triggerEvent('onUserBeforeSave', [$current, false, $users->getProperties()]);

                if (in_array(false, $result, true))
                {
                    continue;
                }

                if (!$users->store())
                {
                    Application::message($users->getError(), Application::ERROR);
                    continue;
                }

                $updated++;

                if ($users->block)
                {
                    UserHelper::destroyUserSessions($users->id);
                }

                $app->triggerEvent('onUserAfterSave', [$users->getProperties(), false, true, null]);

                // Log the user out.
                if ($block)
                {
                    $app->logout($users->id, $options);
                }
            }
        }

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
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
        $updated  = $this->updateBool('Persons', 'published', $selectedIDs, $value);

        $this->updateMessage($selected, $updated);
        $this->setRedirect('index.php?option=com_groups&view=Persons');
    }

    /**
     * Unblocks the selected users.
     * @return void
     */
    public function unblock(): void
    {
        $this->toggleBlock(false);
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