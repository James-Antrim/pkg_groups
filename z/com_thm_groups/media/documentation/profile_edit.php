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
<h3><?php echo Text::_('PROFILE_EDIT'); ?></h3>
<p><?php echo Text::_('PROFILE_EDIT_VIEW_DESC_LONG'); ?></p>
<div class="list">
    <div class="label"><?php echo Text::_('LABEL'); ?></div>
    <div class="description"><?php echo Text::_('LABEL_DESC_LONG'); ?></div>
    <div class="label"><?php echo Text::_('FIELD'); ?></div>
    <div class="description">
        <?php echo html_entity_decode(Text::_('PROFILE_EDIT_FIELD_DESC_LONG')); ?>
    </div>
    <div class="label"><?php echo Text::_('PUBLISHED'); ?></div>
    <div class="description">
        <?php echo Text::_('PROFILE_ATTRIBUTE_PUBLISHED_DESC_LONG'); ?>
    </div>
    <div class="label"><?php echo Text::_('VIEW_LEVEL'); ?></div>
    <div class="description"><?php echo Text::_('VIEW_LEVEL_DESC_LONG'); ?></div>
    <div class="clearFix"></div>
</div>
</body>
</html>
