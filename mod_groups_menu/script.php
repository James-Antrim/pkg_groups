<?php
/**
 * @package     Groups
 * @extension   mod_groups_menu
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\{InstallerAdapter, InstallerScriptInterface};
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseDriver;

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
        if ($type === 'install') {
            /** @var DatabaseDriver $db */
            $db = $adapter->__get('db');

            // Migrate extension settings from old
            $query = $db->getQuery(true);
            $query->select($db->qn('params'))->from($db->qn('#__extensions'))
                ->where($db->qn('name') . ' = ' . $db->q('mod_thm_groups_menu'));
            $db->setQuery($query);

            if ($params = $db->loadResult()) {
                $query = $db->getQuery(true);
                $query->update($db->qn('#__extensions'))
                    ->set($db->qn('params') . ' = ' . $db->q($params))
                    ->where($db->qn('name') . ' = ' . $db->q('mod_groups_menu'));
                $db->setQuery($query);
                $db->execute();
            }

            // Module Settings
            $query = $db->getQuery(true);
            $query->update($db->qn('#__modules'))
                ->set($db->qn('module') . ' = ' . $db->q('mod_groups_menu'))
                ->where($db->qn('module') . ' = ' . $db->q('mod_thm_groups_menu'));
            $db->setQuery($query);
            $db->execute();
        }

        return true;
    }
};