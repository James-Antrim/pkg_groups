<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
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
<h3><?php echo Text::_('ATTRIBUTES'); ?></h3>
<p><?php echo html_entity_decode(Text::_('ATTRIBUTES_DESC_LONG')); ?></p>
<div class="list">
    <div class="label"><?php echo Text::_('ORDER'); ?></div>
    <div class="description"><?php echo Text::_('ATTRIBUTES_ORDER_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('LABEL'); ?></div>
    <div class="description"><?php echo Text::_('ATTRIBUTES_LABEL_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('SHOW_LABEL'); ?></div>
    <div class="description"><?php echo Text::_('ATTRIBUTES_SHOW_LABEL_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('SHOW_ICON'); ?></div>
    <div class="description"><?php echo Text::_('ATTRIBUTES_SHOW_ICON_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('PUBLISHED'); ?></div>
    <div class="description"><?php echo Text::_('ATTRIBUTES_PUBLISHED_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('VIEW_LEVEL'); ?></div>
    <div class="description"><?php echo Text::_('ATTRIBUTES_VIEW_LEVEL_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('ATTRIBUTE_TYPE'); ?></div>
    <div class="description">
        <?php echo html_entity_decode(Text::_('ATTRIBUTES_ATTRIBUTE_TYPE_DESC_LONG')); ?>
    </div>
    <div class="clearFix"></div>
</div>
</body>
</html>
