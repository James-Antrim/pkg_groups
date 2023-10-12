<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace Groups;

defined('_JEXEC') or die;

spl_autoload_register(function ($originalClassName)
{
    $classNameParts = explode('\\', $originalClassName);

    if (array_shift($classNameParts) !== 'THM' or array_shift($classNameParts) !== 'Groups') {
        return;
    }

    $className = array_pop($classNameParts);

    if (reset($classNameParts) === 'Admin') {
        array_shift($classNameParts);
    }

    $classNameParts[] = empty($className) ? 'Component' : $className;

    $filepath = JPATH_ROOT . '/components/com_groups/' . implode('/', $classNameParts) . '.php';

    if (is_file($filepath)) {
        require_once $filepath;
    }
});
