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
<h3><?php echo Text::_('TEMPLATE_MANAGER'); ?></h3>
<p><?php echo html_entity_decode(Text::_('TEMPLATE_MANAGER_DESC_LONG')); ?></p>
</body>
</html>
