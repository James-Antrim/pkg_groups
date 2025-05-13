<?php
/**
 * @package     THM_Groups
 * @extension   mod_thm_groups_menu
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

defined('_JEXEC') or die;

use THM_GroupsHelperMenu as Helper;
use \JText as Text;

$vCardShort = THM_GroupsHelperProfiles::getVCardLink($profileID);

echo '<ul class="menu">';
$active      = (!empty($view) and $view == 'profile');
$activeClass = $active ? 'active current' : '';
echo '<li class="' . $activeClass . '">';
echo Helper::getItem($active, $profileParams, Text::_('MOD_THM_GROUPS_PROFILE')) . $vCardShort;
echo '</li>';
if ($contentEnabled and $showAdmin) {
    $active      = (!empty($view) and $view == 'content_manager');
    $activeClass = $active ? 'active current' : '';
    $lastClass   = $contentExists ? '' : ' item-last';
    echo '<li class="' . $activeClass . $lastClass . '">';
    echo Helper::getItem($active, $managerParams, Text::_('MOD_THM_GROUPS_MANAGE_CONTENT'));
    echo '</li>';
}
if ($showSubMenu) {
    echo "<li>$subMenuHeader<ul>";
}
if ($contentExists) {
    $lastItem = end($contents);
    foreach ($contents as $item) {
        $active      = (!empty($contentID) and $item->id == $contentID);
        $activeClass = $active ? 'active current' : '';
        $lastClass   = $item === $lastItem ? ' item-last' : '';
        echo '<li class="' . $activeClass . $lastClass . '">';
        $iterantParams = $contentParams + ['id' => $item->id];;
        echo Helper::getItem($active, $iterantParams, $item->title);
        echo '</li>';
    }
}
if ($showSubMenu) {
    echo "</ul></li>";
}
echo '</ul>';
