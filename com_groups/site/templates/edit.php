<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use THM\Groups\Adapters\{Application, HTML, Input, Text};
use THM\Groups\Views\HTML\FormView;

/** @var FormView $this */

// Core behaviour scripts
$wa = Application::document()->getWebAssetManager();
$wa->useScript('keepalive')->useScript('form.validate');

$formName  = strtoupper($this->getName());
$ariaLabel = Text::_("GROUPS_{$formName}_FORM");

$tabs   = $this->form->getFieldsets();
$tabbed = count($tabs) > 1;

$this->renderTasks();
?>
<form action="<?php echo Route::_('index.php?option=com_groups'); ?>"
      aria-label="<?php echo $ariaLabel; ?>"
      class="form-validate"
      enctype="multipart/form-data"
      id="adminForm"
      method="post"
      name="adminForm">
    <div class="main-card">
        <?php if ($tabbed): ?>
            <?php echo HTML::startTabs(); ?>
            <?php foreach ($tabs as $name => $tab): ?>
                <?php if (!$this->form->getFieldset($name)) {
                    continue;
                } ?>
                <?php echo HTML::addFieldSet($tab); ?>
                <fieldset class="options-form">
                    <div class="form-grid">
                        <?php echo $this->form->renderFieldset($name); ?>
                    </div>
                </fieldset>
                <?php echo HTML::endTab(); ?>
            <?php endforeach; ?>
            <?php echo HTML::endTabs(); ?>
        <?php else: ?>
            <fieldset class="options-form">
                <div class="form-grid">
                    <?php echo $this->form->renderFieldset('details'); ?>
                </div>
            </fieldset>
        <?php endif; ?>
        <input type="hidden" name="task" value="<?php echo $this->defaultTask; ?>">
        <input type="hidden" name="return" value="<?php echo Input::instance()->getBase64('return'); ?>">
        <input type="hidden" name="forcedLanguage" value="<?php echo Input::string('forcedLanguage'); ?>">
        <?php echo HTML::token(); ?>
    </div>
</form>