<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Providers;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use THM\Groups\Adapters\DispatcherFactory;


/**
 * Service provider for the service dispatcher factory.
 */
class Dispatcher implements ServiceProviderInterface
{
    /** @inheritDoc */
    public function register(Container $container): void
    {
        $container->set(
            ComponentDispatcherFactoryInterface::class,
            function (Container $container) {
                return new DispatcherFactory('\\THM\\Groups', $container->get(MVCFactoryInterface::class));
            }
        );
    }
}
