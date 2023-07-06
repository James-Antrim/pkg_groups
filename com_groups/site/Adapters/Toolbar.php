<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Joomla\DI\Exception\KeyNotFoundException;
use Joomla\CMS\Toolbar\{Toolbar as Base, ToolbarFactoryInterface};

class Toolbar extends Base
{
    /**
     * Returns the GLOBAL Toolbar object, only creating it if it doesn't already exist. The parent documentation says
     * deprecated => use the container, but the container is explicitly not allowed to set toolbars because they are
     * GLOBAL and used by joomla to display component items outside the component context.
     *
     * @param string $name The name of the toolbar.
     *
     * @return  Base  The Toolbar object.
     */
    public static function getInstance($name = 'toolbar'): Base
    {
        if (empty(self::$instances[$name])) {
            $container = Application::getContainer();

            try {
                $tbFactory              = $container->get(ToolbarFactoryInterface::class);
                self::$instances[$name] = $tbFactory->createToolbar($name);
            } catch (KeyNotFoundException $exception) {
                Application::handleException($exception);
            }
        }

        return self::$instances[$name];
    }
}