<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups;

defined('_JEXEC') or die;

spl_autoload_register(function ($originalClassName) {

    $classNameParts = explode('\\', $originalClassName);

    if (array_shift($classNameParts) !== 'THM' or array_shift($classNameParts) !== 'Groups') {
        return;
    }

    //THM\Groups\Plugin\<Type>\Groups
    if (reset($classNameParts) === 'Plugin') {
        array_shift($classNameParts);
        $type     = strtolower(array_shift($classNameParts));
        $filepath = JPATH_ROOT . "/plugins/$type/groups/Groups.php";
    }
    //THM\Groups\Module\<Name>\Path..
    elseif (reset($classNameParts) === 'Module') {
        array_shift($classNameParts);
        $name      = strtolower(array_shift($classNameParts));
        $extension = array_search($name, ['mod_groups_menu' => 'Menu', 'mod_groups_profiles' => 'Profiles']);
        $filepath  = JPATH_ROOT . "/modules/$extension/" . implode('/', $classNameParts) . '.php';
    }
    else {
        // Namespaced classes are all in the site directory
        if (reset($classNameParts) === 'Admin') {
            array_shift($classNameParts);
        }
        $className        = array_pop($classNameParts);
        $classNameParts[] = empty($className) ? 'Component' : $className;
        $filepath         = JPATH_ROOT . '/components/com_groups/' . implode('/', $classNameParts) . '.php';
    }

    if (is_file($filepath)) {
        require_once $filepath;
    }
});
