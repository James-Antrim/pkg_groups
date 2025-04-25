<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

use Joomla\CMS\{HTML\HTMLHelper, Language\Text, Router\Route, Session\Session};
use Joomla\Utilities\ArrayHelper;
use THM\Groups\Adapters\{Application, HTML};
use THM\Groups\Layouts;
use THM\Groups\Views\HTML\ListView;

/** @var ListView $this */

$action         = Route::_('index.php?option=com_groups&view=' . $this->_name);
$direction      = $this->escape($this->state->get('list.direction'));
$orderBy        = $this->escape($this->state->get('list.ordering'));
$dragEnabled    = (!empty($this->items) and $orderBy == 'ordering' and strtolower($direction) == 'asc');
$dragProperties = '';

if ($dragEnabled) {
    $baseURL      = 'index.php?option=com_groups';
    $draggableURL = "$baseURL&task=$this->_name.saveOrderAjax&tmpl=component" . Session::getFormToken() . '=1';
    HTML::_('draggablelist.draggable');
    $dragProperties = [
        'class'          => 'js-draggable',
        'data-direction' => 'asc',
        'data-nested'    => 'false',
        'data-url'       => $draggableURL
    ];
    $dragProperties = ArrayHelper::toString($dragProperties);
}

if ($this->todo) {
    echo '<ul>';
    foreach ($this->todo as $todo) {
        echo "<li>$todo</li>";
    }
    echo '</ul>';
}

if (count($this->headers) > 4) {
    $wa = Application::document()->getWebAssetManager();
    $wa->useScript('table.columns');
}

?>
<form action="<?php echo $action; ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container groups">
                <?php Layouts\ListTools::render($this); ?>
                <?php if (empty($this->items)) : ?>
                    <?php Layouts\EmptyList::render($this); ?>
                <?php else : ?>
                    <table class="table" id="<?php echo $this->_name ?>List">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_USERS_USERS_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <?php Layouts\ListHeaders::render($this); ?>
                        <tbody <?php echo $dragProperties; ?>>
                        <?php foreach ($this->items as $rowNo => $item) : ?>
                            <?php Layouts\ListItem::render($this, $rowNo, $item, $dragEnabled); ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->getListFooter(); ?>
                    <?php if ($this->allowBatch and $batch = $this->filterForm->getGroup('batch')): ?>
                        <?php echo HTMLHelper::_(
                            'bootstrap.renderModal',
                            'collapseModal',
                            [
                                'title'  => Text::_('GROUPS_BATCH_PROCESSING'),
                                'footer' => Layouts\Batch::renderFooter($this),
                            ],
                            Layouts\Batch::renderBody($this)
                        ); ?>
                        <?php Layouts\Batch::renderBody($this); ?>
                    <?php endif; ?>
                <?php endif; ?>

                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <?php echo HTML::token(); ?>
            </div>
        </div>
    </div>
</form>
