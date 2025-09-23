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

use Joomla\CMS\{Application\AdministratorApplication, Helper\UserGroupsHelper, Language\Text};
use Joomla\CMS\Installer\{InstallerAdapter, InstallerScriptInterface};
use Joomla\Database\{DatabaseDriver, DatabaseInterface, ParameterType};
use Joomla\DI\{Container, ServiceProviderInterface};

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            InstallerScriptInterface::class,
            new class (
                $container->get(AdministratorApplication::class),
                $container->get(DatabaseInterface::class)
            ) implements InstallerScriptInterface {
                private AdministratorApplication $app;
                private DatabaseDriver $db;
                private string $minimumJoomla = '5.0.0';
                private string $minimumPhp = '8.1.0';

                /**
                 * Sets up the installer.
                 *
                 * @param   AdministratorApplication  $app
                 * @param   DatabaseDriver            $db
                 */
                public function __construct(AdministratorApplication $app, DatabaseDriver $db)
                {
                    $this->app = $app;
                    $this->db  = $db;
                }

                /**
                 * Adds basic entries for group localizations.
                 *
                 * @return void
                 */
                private function groups(): void
                {
                    $query = $this->db->getQuery(true);
                    $query->insert($this->db->quoteName('#__groups_groups'))
                        ->columns($this->db->quoteName((['id', 'name_de', 'name_en'])))
                        ->values(":id, :name_de, :name_en")
                        ->bind(':id', $groupID, ParameterType::INTEGER)
                        ->bind(':name_de', $name)
                        ->bind(':name_en', $name);

                    // Make no exception for standard groups here, in order to have comparable output to the users component.
                    foreach (UserGroupsHelper::getInstance()->getAll() as $groupID => $group) {
                        $name = $group->title;
                        $this->db->setQuery($query);
                        $this->db->execute();
                    }
                }

                /** @inheritDoc */
                public function install(InstallerAdapter $adapter): bool
                {
                    // The package are now installed and queries have been executed
                    require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

                    $this->groups();

                    $path = JPATH_ROOT . '/images/com_groups';

                    if (!file_exists($path) && !mkdir($path, 0755, true)) {
                        $msg = "Failed to create the images directory $path. This can lead to errors saving image attributes.";
                        $this->app->enqueueMessage($msg, 'error');
                    }

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
                    $version = '';
                    if ($type === 'install' or $type === 'update') {
                        if (version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
                            $this->app->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_PHP'), $this->minimumPhp),
                                'error');
                            return false;
                        }

                        if (version_compare(JVERSION, $this->minimumJoomla, '<')) {
                            $this->app->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_JOOMLA'), $this->minimumJoomla),
                                'error');
                            return false;
                        }

                        echo '<hr>';
                        $thisVersion = $adapter->getManifest()->version;

                        if ($type === 'update') {
                            $query = $this->db->getQuery(true);
                            $query->select($this->db->quoteName('manifest_cache'))
                                ->from($this->db->quoteName('#__extensions'))
                                ->where($this->db->quoteName('name') . ' = ' . $this->db->quote('com_groups'));
                            $this->db->setQuery($query);

                            if ($manifest = json_decode((string) $this->db->loadResult()) and !empty($manifest['version'])) {
                                $version = $manifest['version'];
                            }
                            $version = '<br/>' . $version . ' &rArr; ' . $thisVersion;
                        }
                        else {
                            $version = '<br/>' . $thisVersion;
                        }
                    }

                    echo '<h1>Groups ' . strtoupper($type) . $version . '</h1>';
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
            }
        );
    }
};