<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

require_once 'autoloader.php';

//use Joomla\CMS\Categories\CategoryFactoryInterface;
//use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service;

//use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use THM\Groups\Component;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use THM\Groups\Providers;

/**
 * The service provider.
 */
return new class implements ServiceProviderInterface {
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
		$container->registerServiceProvider(new Providers\Dispatcher());
		$container->registerServiceProvider(new Providers\MVC());
//		$container->registerServiceProvider(new Service\Provider\CategoryFactory('\\THM\\Groups'));
//		$container->registerServiceProvider(new Service\Provider\RouterFactory('\\THM\\Groups'));
		$container->set(
			ComponentInterface::class,
			function (Container $container) {
				$component = new Component($container->get(ComponentDispatcherFactoryInterface::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
//				$component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
//				$component->setRouterFactory($container->get(RouterFactoryInterface::class));
//				$component->setRegistry($container->get(Registry::class));

				return $component;
			}
		);
	}
};