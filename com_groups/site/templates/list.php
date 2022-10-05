<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use THM\Groups\Adapters\HTML;
use THM\Groups\Layouts\EmptyList;
use THM\Groups\Layouts\ListHeaders;
use THM\Groups\Layouts\ListItem;
use THM\Groups\Views\HTML\ListView;

/** @var ListView $this */

$direction      = $this->escape($this->state->get('list.direction'));
$order          = $this->escape($this->state->get('list.ordering'));
$dragEnabled    = ($order == 'order' and strtolower($direction) == 'asc');
$dragProperties = '';

if ($dragEnabled and !empty($this->items))
{
	$baseURL      = 'index.php?option=com_groups';
	$draggableURL = "$baseURL&task=$this->_name.saveOrderAjax&tmpl=component&" . Session::getFormToken() . '=1';
	HTML::_('draggablelist.draggable');
	$dragProperties = [
		'class'          => 'js-draggable',
		'data-direction' => 'asc',
		'data-nested'    => 'false',
		'data-url'       => $draggableURL
	];
	$dragProperties = HTML::toProperties($dragProperties);
}
?>
<form action="<?php echo Route::_('index.php?option=com_groups&controller=' . $this->_name); ?>" method="post" name="adminForm"
      id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
				<?php
				// Search tools bar
				echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]);
				?>
				<?php if (empty($this->items)) : ?>
					<?php EmptyList::render(); ?>
				<?php else : ?>
                    <table class="table" id="<?php echo $this->_name ?>List">
                        <caption class="visually-hidden">
							<?php echo Text::_('COM_USERS_USERS_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
						<?php ListHeaders::render($this); ?>
                        <tbody <?php echo $dragProperties; ?>>
						<?php foreach ($this->items as $rowNo => $item) : ?>
							<?php ListItem::render($this, $rowNo, $item); ?>
						<?php endforeach; ?>
                        </tbody>
                    </table>
					<?php echo $this->pagination->getListFooter(); ?>
				<?php endif; ?>

                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
				<?php echo HTML::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
