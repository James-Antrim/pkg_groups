<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

use Joomla\CMS\Router\Route;
use THM\Groups\Adapters\{Application, HTML, Input, Text};
use THM\Groups\Views\HTML\FormView;

/** @var FormView $this */

// Core behaviour scripts
$wa = Application::document()->getWebAssetManager();
$wa->useScript('keepalive')->useScript('form.validate');
$this->renderTasks();

$formName = strtoupper($this->getName());
$return   = Input::instance()->getBase64('return');

?>
<form action="<?php echo Route::_('index.php?option=com_groups'); ?>"
      aria-label="<?php echo Text::_("GROUPS_{$formName}_FORM"); ?>"
      class="form-validate"
      enctype="multipart/form-data"
      id="adminForm"
      method="post"
      name="adminForm">
    <div class="main-card">
        <div class="row">
            <div class="col-lg-9">
                <div>
                    <?php echo $this->form->renderFieldset('content'); ?>
                </div>
            </div>
            <div class="col-lg-3">
                <?php echo $this->form->renderFieldset('properties'); ?>
            </div>
        </div>
        <input type="hidden" name="task" value="<?php echo $this->defaultTask; ?>">
        <input type="hidden" name="return" value="<?php echo Input::instance()->getBase64('return'); ?>">
        <input type="hidden" name="forcedLanguage" value="<?php echo Input::string('forcedLanguage'); ?>">
        <?php echo HTML::token(); ?>
    </div>
</form>