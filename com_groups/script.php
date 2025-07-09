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
use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\CMS\Language\Text;
use Joomla\Database\ParameterType;
use THM\Groups\Adapters\{Application, Database as DB};
use THM\Groups\Controllers\Profile as Controller;

return new class () implements InstallerScriptInterface {

    private string $minimumJoomla = '5.0.0';
    private string $minimumPhp = '8.1.0';

    /**
     * Adds basic entries for group localizations.
     *
     * @return void
     */
    private function groups(): void
    {
        $query = DB::query();
        $query->insert(DB::qn('#__groups_groups'))
            ->columns(DB::qn(['id', 'name_de', 'name_en']))
            ->values(":id, :name_de, :name_en")
            ->bind(':id', $groupID, ParameterType::INTEGER)
            ->bind(':name_de', $name)
            ->bind(':name_en', $name);

        // Make no exception for standard groups here, in order to have comparable output to the users component.
        foreach (UserGroupsHelper::getInstance()->getAll() as $groupID => $group) {
            $name = $group->title;
            DB::set($query);
            DB::execute();
        }
    }

    /** @inheritDoc */
    public function install(InstallerAdapter $adapter): bool
    {
        // The package are now installed and queries have been executed
        require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

        $this->groups();
        $this->profiles();

        $path = JPATH_ROOT . '/images/com_groups';

        if (!file_exists($path) && !mkdir($path, 0755, true)) {
            $msg = "Failed to create the images directory $path. This can lead to errors saving image attributes.";
            Application::message($msg, Application::ERROR);
        }

        return true;
    }

    /**
     * Gets the installed extension version from the extensions table.
     *
     * @return string
     */
    private function installedVersion(): string
    {
        $query = DB::query();
        $query->select(DB::qn('manifest_cache'))->from(DB::qn('#__extensions'))->where(DB::qc('name', 'com_groups', '=', true));
        DB::set($query);

        if ($manifest = json_decode(DB::string()) and !empty($manifest['version'])) {
            return $manifest['version'];
        }

        return '';
    }

    /**
     * Creates rudimentary profiles based on user table data.
     *
     * @return void
     */
    private function profiles(): void
    {
        $query = DB::query();
        $query->select(DB::qn('id'))->from(DB::qn('#__users'));
        DB::set($query);

        if (!$userIDs = DB::integers()) {
            return;
        }

        foreach ($userIDs as $userID) {
            Controller::create($userID);
        }
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

        echo '<hr>';
        $thisVersion = $adapter->getManifest()->version;
        $version     = '';

        if ($type == 'update') {
            $version = '<br/>' . $this->installedVersion() . ' &rArr; ' . $thisVersion;
        }
        elseif ($type == 'install') {
            $version = '<br/>' . $thisVersion;
        }

        echo '<h1>THM Groups ' . strtoupper($type) . $version . '</h1>';
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