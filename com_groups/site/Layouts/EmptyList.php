<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Layouts;

use Joomla\CMS\Language\Text;
use THM\Groups\Views\HTML\ListView;

class EmptyList
{
    /**
     * Renders a notice for an empty result set.
     */
    public static function render(ListView $view): void
    {
        ?>
        <div class="alert alert-info">
            <span class="fa fa-info-circle" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
            <?php echo $view->empty ?: Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
        <?php
    }
}