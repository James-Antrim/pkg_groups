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

use Joomla\CMS\MVC\Factory\{ApiMVCFactory, MVCFactoryInterface};
use Joomla\Database\DatabaseInterface;
use Joomla\DI\{Container, ServiceProviderInterface};
use Joomla\Event\DispatcherInterface;
use THM\Groups\Adapters\{Application, FormFactory, MVCFactory};

/**
 * Service provider for the service MVC factory.
 */
class MVC implements ServiceProviderInterface
{
    /** @inheritDoc */
    public function register(Container $container): void
    {
        $container->set(
            MVCFactoryInterface::class,
            function (Container $container) {
                if (Application::api()) {
                    $factory = new ApiMVCFactory('\\THM\\Groups');
                }
                else {
                    $factory = new MVCFactory('\\THM\\Groups');
                }

                $factory->setDispatcher($container->get(DispatcherInterface::class));

                // Form factory is 'protected' and cannot be overwritten through services
                $formFactory = new FormFactory();
                $formFactory->setDatabase($container->get(DatabaseInterface::class));

                $factory->setFormFactory($formFactory);

                return $factory;
            }
        );
    }
}