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
<h3><?php echo Text::_('CONTENT'); ?></h3>
<p><?php echo Text::_('CONTENT_DESC_LONG'); ?></p>
<div class="list">
    <div class="label"><?php echo Text::_('ORDER'); ?></div>
    <div class="description"><?php echo Text::_('CONTENT_ORDERING_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('ID'); ?></div>
    <div class="description"><?php echo Text::_('CONTENT_ID_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('TITLE'); ?></div>
    <div class="description"><?php echo Text::_('CONTENT_TITLE_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('PROFILE'); ?></div>
    <div class="description"><?php echo Text::_('CONTENT_PROFILE_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('PROFILE_MENU'); ?></div>
    <div class="description"><?php echo Text::_('PROFILE_MENU_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('STATUS'); ?></div>
    <div class="description"><?php echo Text::_('CONTENT_STATUS_DESC_LONG'); ?></div>
    <div class="clearFix"></div>
</div>
</body>
</html>
