<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Layouts\HTML;

use Joomla\CMS\Form\FormHelper;
use THM\Groups\Adapters\Application;
use THM\Groups\Views\HTML\ListView;

/**
 * Creates the HTML filter bar element.
 */
class FilterBar
{
    /**
     * Renders the filter bar.
     *
     * @param   ListView  $view
     */
    public static function render(ListView $view): void
    {
        if (empty($view->filterForm)) {
            return;
        }

        if (!$filters = $view->filterForm->getGroup('filter')) {
            return;
        }

        $wam = Application::document()->getWebAssetManager();

        foreach ($filters as $fieldName => $field) {
            if ($fieldName !== 'filter_search') {
                $dataShowOn = '';

                if ($showOn = $field->__get('showon')) {
                    $wam->useScript('showon');

                    $control    = $field->__get('formControl');
                    $group      = $field->__get('group');
                    $json       = json_encode(FormHelper::parseShowOnConditions($showOn, $control, $group));
                    $dataShowOn = " data-showon='$json'";
                }

                ?>
                <div class="js-stools-field-filter"<?php echo $dataShowOn; ?>>
                    <span class="visually-hidden"><?php echo $field->__get('label'); ?></span>
                    <?php echo $field->__get('input'); ?>
                </div>
                <?php
            }
        }
    }
}