<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\Router\RouterInterface;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Menu\AbstractMenu;
use Psr\Container\ContainerInterface;

class Component extends MVCComponent implements BootableExtensionInterface, RouterServiceInterface
{
    use HTMLRegistryAwareTrait;

    /**
     * @inheritDoc
     */
    public function boot(ContainerInterface $container)
    {
        // TODO: Implement boot() method.
    }

    /**
     * @inheritDoc
     */
    public function createRouter(CMSApplicationInterface $application, AbstractMenu $menu): RouterInterface
    {
        // TODO: Implement createRouter() method.
    }

    public function setCategoryFactory($get)
    {
    }

    public function setRouterFactory($get)
    {
    }
}

