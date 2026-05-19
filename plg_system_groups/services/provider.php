<?php
/**
 * @package     Groups
 * @extension   plg_system_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

use Joomla\CMS\{Extension\PluginInterface, Factory, Plugin\PluginHelper};
use Joomla\DI\{Container, ServiceProviderInterface};
use Joomla\Event\DispatcherInterface;
use THM\Plugin\System\Groups\Extension\Groups;

return new class() implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {

                $config  = (array) PluginHelper::getPlugin('system', 'groups');
                $subject = $container->get(DispatcherInterface::class);
                $app     = Factory::getApplication();

                $plugin = new Groups($subject, $config);
                $plugin->setApplication($app);

                return $plugin;
            }
        );
    }
};