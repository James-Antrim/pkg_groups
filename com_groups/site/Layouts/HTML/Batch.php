<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Layouts\HTML;

use THM\Groups\Adapters\Toolbar;
use THM\Groups\Views\HTML\ListView;

/**
 * Class renders elements of a modal element for batch processing.
 */
class Batch
{
    /**
     * Renders a batch form for a list view.
     *
     * @param   ListView  $view  the view being rendered
     */
    public static function render(ListView $view): void
    {
        $batch = $view->filterForm->getGroup('batch');
        ?>
        <div class="p-3">
            <?php foreach ($batch as $field) : ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $field->__get('label'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $field->__get('input'); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="btn-toolbar p-3">
            <?php echo Toolbar::render('batch'); ?>
        </div>
        <?php
    }
}