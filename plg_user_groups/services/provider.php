<?php
/**
 * @package     Groups
 * @extension   plg_user_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

use Joomla\CMS\{Extension\PluginInterface, Factory, Plugin\PluginHelper};
use Joomla\DI\{Container, ServiceProviderInterface};
use Joomla\Event\DispatcherInterface;
use THM\Groups\Plugin\User\Groups;

return new class() implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $plugin = new Groups(
                    $container->get(DispatcherInterface::class),
                    (array) PluginHelper::getPlugin('user', 'groups')
                );
                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};