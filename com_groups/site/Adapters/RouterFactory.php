<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Joomla\CMS\Application\{CMSApplication, CMSApplicationInterface};
use Joomla\CMS\Component\Router\{RouterFactoryInterface, RouterInterface};
use Joomla\CMS\Menu\AbstractMenu;

/** @inheritDoc */
class RouterFactory implements RouterFactoryInterface
{
    /** @inheritDoc */
    public function createRouter(CMSApplicationInterface $application, AbstractMenu $menu): RouterInterface
    {
        /** @var CMSApplication $application * */
        return new Router($application, $menu);
    }
}