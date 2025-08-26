<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */
require_once 'framework.php';

use THM\Groups\Adapters\Text;

?>
<html>
<head>
    <link rel="stylesheet" href="<?php echo JUri::root() . 'css/documentation.css'; ?>">
</head>
<body class="groups-documentation">
<h3><?php echo Text::_('GROUP_MANAGER'); ?></h3>
<p><?php echo Text::_('GROUP_MANAGER_DESC_LONG'); ?></p>
<div class="list">
    <div class="label"><?php echo Text::_('ORDER'); ?></div>
    <div class="description"><?php echo Text::_('GROUPS_ORDER_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('NAME'); ?></div>
    <div class="description"><?php echo Text::_('GROUPS_NAME_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('ROLES'); ?></div>
    <div class="description"><?php echo Text::_('GROUPS_ROLES_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('MEMBERS'); ?></div>
    <div class="description"><?php echo Text::_('GROUPS_MEMBERS_DESC_LONG'); ?></div>
    <div class="clearFix"></div>
</div>
</body>
</html>
