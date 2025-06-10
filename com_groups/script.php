<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\{InstallerAdapter, InstallerScriptInterface};
use Joomla\CMS\Language\Text;

return new class () implements InstallerScriptInterface {

    private string $minimumJoomla = '5.0.0';
    private string $minimumPhp = '8.1.0';

    /** @inheritDoc */
    public function install(InstallerAdapter $adapter): bool
    {
        return true;
    }

    /** @inheritDoc */
    public function update(InstallerAdapter $adapter): bool
    {
        return true;
    }

    /** @inheritDoc */
    public function uninstall(InstallerAdapter $adapter): bool
    {
        return true;
    }

    /** @inheritDoc */
    public function preflight(string $type, InstallerAdapter $adapter): bool
    {
        if (version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
            Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_PHP'), $this->minimumPhp), 'error');
            return false;
        }

        if (version_compare(JVERSION, $this->minimumJoomla, '<')) {
            Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_JOOMLA'), $this->minimumJoomla),
                'error');
            return false;
        }

        return true;
    }

    /** @inheritDoc */
    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        //todo: copy rights
        //todo: Component settings
        //todo: -editownprofile => profile-management
        //todo: -enabled => profile-content
        //todo: -rootCategory => category
        //todo: Resource migration
        //todo: File migration
        //todo: Menu assignments

        return true;
    }
};