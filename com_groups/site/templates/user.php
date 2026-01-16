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
use THM\Groups\Helpers\{Can, Users};
use THM\Groups\Views\HTML\User;

// Core behaviour scripts
$wa = Application::document()->getWebAssetManager();
$wa->useScript('keepalive')->useScript('form.validate');

/** @var User $this */
$ariaLabel = Text::_("GROUPS_USER_FORM");

$manage  = Can::manage();
$profile = Users::editOwn($this->item->id);
$active  = $profile ? 'profile' : 'details';

$sets           = $this->form->getFieldsets();
$accessibility  = $sets['accessibility'];
$account        = $sets['account'];
$administration = $sets['administration'];
$settings       = $sets['settings'];

// Global restriction or account to be edited does not belong to the user.
//if (!Input::parameters()->get('profile-management') or $accountID != $userID) {
//    return false;
//}

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
        <?php echo HTML::startTabs($active); ?>
        <?php echo HTML::addTab($account); ?>
        <fieldset class="options-form">
            <div class="form-grid">
                <?php echo $this->form->renderFieldset('account'); ?>
            </div>
        </fieldset>
        <?php echo HTML::endTab(); ?>
        <?php if ($manage or $profile): ?>
            <?php echo HTML::addTab('profile', 'PROFILE'); ?>
            <fieldset class="options-form">
                <div class="form-grid">
                </div>
            </fieldset>
            <?php echo HTML::endTab(); ?>
        <?php endif; ?>
        <?php if ($manage): ?>
            <?php echo HTML::addTab('associations', 'ASSOCIATIONS'); ?>
            <fieldset class="options-form">
                <div class="form-grid">
                </div>
            </fieldset>
            <?php echo HTML::endTab(); ?>
            <?php echo HTML::addTab($administration); ?>
            <fieldset class="options-form">
                <div class="form-grid">
                    <?php echo $this->form->renderFieldset('administration'); ?>
                </div>
            </fieldset>
            <?php echo HTML::endTab(); ?>
        <?php endif; ?>
        <?php echo HTML::addTab($settings); ?>
        <fieldset class="options-form">
            <div class="form-grid">
                <?php echo $this->form->renderFieldset('settings'); ?>
            </div>
        </fieldset>
        <?php echo HTML::endTab(); ?>
        <?php echo HTML::addTab($accessibility); ?>
        <fieldset class="options-form">
            <div class="form-grid">
                <?php echo $this->form->renderFieldset('accessibility'); ?>
            </div>
        </fieldset>
        <?php echo HTML::endTab(); ?>
        <?php echo HTML::endTabs(); ?>
        <input type="hidden" name="task" value="<?php echo $this->defaultTask; ?>">
        <input type="hidden" name="return" value="<?php echo Input::instance()->getBase64('return'); ?>">
        <input type="hidden" name="forcedLanguage" value="<?php echo Input::string('forcedLanguage'); ?>">
        <?php echo HTML::token(); ?>
    </div>
</form>