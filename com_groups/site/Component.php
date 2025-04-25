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

use Joomla\CMS\{Application\CMSApplicationInterface, Extension\MVCComponent};
use Joomla\CMS\{HTML\HTMLRegistryAwareTrait, Menu\AbstractMenu};
use Joomla\CMS\Component\Router\{RouterInterface, RouterServiceInterface};
use THM\Groups\Adapters\{MVCFactory, RouterFactory};

class Component extends MVCComponent implements RouterServiceInterface
{
    use HTMLRegistryAwareTrait;

    private ?RouterFactory $routerFactory = null;

    /**
     * Wrapper for the getMVCFactory function to accurately return type the unnecessarily private property mvcFactory.
     * @return MVCFactory
     */
    public function mvcFactory(): MVCFactory
    {
        /** @var MVCFactory $factory */
        $factory = $this->getMVCFactory();

        return $factory;
    }

    /** @inheritDoc */
    public function createRouter(CMSApplicationInterface $application, AbstractMenu $menu): RouterInterface
    {
        echo "<pre>246810</pre>";
        return $this->routerFactory->createRouter($application, $menu);
    }

    /**
     * The router factory.
     *
     * @param   RouterFactory  $factory  The router factory
     *
     * @return  void
     */
    public function setRouterFactory(RouterFactory $factory): void
    {
        $this->routerFactory = $factory;
    }
}

