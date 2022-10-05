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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\CMS\MVC\Factory\ApiMVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use THM\Groups\Adapters\MVCFactory;

class MVC implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function register(Container $container)
	{
		$container->set(
			MVCFactoryInterface::class,
			function (Container $container) {
				if (Factory::getApplication()->isClient('api'))
				{
					$factory = new ApiMVCFactory('\\THM\\Groups');
				}
				else
				{
					$factory = new MVCFactory('\\THM\\Groups');
				}

				$factory->setFormFactory($container->get(FormFactoryInterface::class));
				$factory->setDispatcher($container->get(DispatcherInterface::class));

				return $factory;
			}
		);
	}
}