<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

use Joomla\CMS\{Language\Text, Router\Route};
use THM\Groups\Adapters\{Application, HTML};
use THM\Groups\Layouts\HTML\{Batch, EmptySet, Headers, HiddenInputs, Row, Tools};
use THM\Groups\Views\HTML\ListView;

/** @var ListView $this */

$action = Route::_('index.php?option=com_groups&view=' . strtolower($this->_name));

if (count($this->headers) > 4) {
    $wa = Application::document()->getWebAssetManager();
    $wa->useScript('table.columns');
}

$direction   = $this->escape($this->state->get('list.direction'));
$orderBy     = $this->escape($this->state->get('list.ordering'));
$dragEnabled = (!empty($this->items) and $orderBy == 'ordering' and strtolower($direction) == 'asc');
$dragProps   = '';

if ($dragEnabled) {
    $baseURL = 'index.php?option=com_groups';
    $dragURL = "$baseURL&task=$this->_name.saveOrderAjax&tmpl=component";
    HTML::_('draggablelist.draggable');
    $dragProps = [
        'properties' => [
            'class'          => 'js-draggable',
            'data-direction' => 'asc',
            'data-nested'    => 'false',
            'data-url'       => $dragURL
        ]
    ];
    $dragProps = HTML::properties($dragProps);
}

$this->renderTasks();
require_once 'header.php';
?>
<form action="<?php echo $action; ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container groups">
                <?php Tools::render($this); ?>
                <?php if (empty($this->items)) : ?>
                    <?php EmptySet::render($this); ?>
                <?php else : ?>
                    <table class="table" id="<?php echo $this->_name ?>List">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_USERS_USERS_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <?php Headers::render($this); ?>
                        <tbody <?php echo $dragProps; ?>>
                        <?php foreach ($this->items as $rowNo => $item) : ?>
                            <?php Row::render($this, $rowNo, $item, $dragEnabled); ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->getListFooter(); ?>
                    <?php if ($this->allowBatch and $batch = $this->filterForm->getGroup('batch')): ?>
                        <template id="groups-batch"><?php Batch::render($this); ?></template>
                    <?php endif; ?>
                <?php endif; ?>
                <?php HiddenInputs::render($this); ?>
                <input type="hidden" name="task" value="<?php echo strtolower($this->_name); ?>.display">
                <input type="hidden" name="boxchecked" value="0">
                <?php echo HTML::token(); ?>
            </div>
        </div>
    </div>
</form>
