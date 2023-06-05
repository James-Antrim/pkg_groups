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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Registry\Registry;use THM\Groups\Views\HTML\FormView;

/** @var FormView $this */

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')->useScript('form.validate');

$this->configFieldsets  = ['editorConfig'];
$this->hiddenFieldsets  = ['basic-limited'];
$fieldsetsInImages = ['image-intro', 'image-full'];
$fieldsetsInLinks = ['linka', 'linkb', 'linkc'];
$this->ignore_fieldsets = array_merge(['jmetadata', 'item_associations'], $fieldsetsInImages, $fieldsetsInLinks);
$this->useCoreUI = true;

// Create shortcut to parameters.
$params = clone $this->state->get('params');
$params->merge(new Registry($this->item->attribs));

$input = Factory::getApplication()->input;

$assoc              = Associations::isEnabled();
$showArticleOptions = $params->get('show_article_options', 1);

// In case of modal
$isModal = $input->get('layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
// TODO form attribute aria-label
$tabs = $this->tabs($this->form->getFieldsets());
$addTabs = count($tabs) > 1;

?>
<form action="<?php echo Route::_('index.php?option=com_groups'); ?>"
      method="post" name="adminForm" id="adminForm"  class="form-validate">
    <div class="main-card">

        <?php if (!$addTabs): ?>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->form->renderFieldset(reset($tabs[0]['sets'])); ?>
            </div>
        </div>
        <?php else: ?>
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general', 'recall' => true, 'breakpoint' => 768]); ?>

        <?php foreach ($tabs as $tab): ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_CONTENT_ARTICLE_CONTENT')); ?>
        <div class="row">
            <div class="col-lg-9">
                <div>
                    <fieldset class="adminform">
                        <?php echo $this->form->getLabel('articletext'); ?>
                        <?php echo $this->form->getInput('articletext'); ?>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-3">
                <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
            </div>
        </div>

        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php // Do not show the images and links options if the edit form is configured not to. ?>
        <?php if ($params->get('show_urls_images_backend') == 1) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'images', Text::_('COM_CONTENT_FIELDSET_URLS_AND_IMAGES')); ?>
            <div class="row">
                <div class="col-12 col-lg-6">
                <?php foreach ($fieldsetsInImages as $fieldset) : ?>
                    <fieldset id="fieldset-<?php echo $fieldset; ?>" class="options-form">
                        <legend><?php echo Text::_($this->form->getFieldsets()[$fieldset]->label); ?></legend>
                        <div>
                        <?php echo $this->form->renderFieldset($fieldset); ?>
                        </div>
                    </fieldset>
                <?php endforeach; ?>
                </div>
                <div class="col-12 col-lg-6">
                <?php foreach ($fieldsetsInLinks as $fieldset) : ?>
                    <fieldset id="fieldset-<?php echo $fieldset; ?>" class="options-form">
                        <legend><?php echo Text::_($this->form->getFieldsets()[$fieldset]->label); ?></legend>
                        <div>
                        <?php echo $this->form->renderFieldset($fieldset); ?>
                        </div>
                    </fieldset>
                <?php endforeach; ?>
                </div>
            </div>

            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endif; ?>

        <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

        <?php // Do not show the publishing options if the edit form is configured not to. ?>
        <?php if ($params->get('show_publishing_options', 1) == 1) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('COM_CONTENT_FIELDSET_PUBLISHING')); ?>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <fieldset id="fieldset-publishingdata" class="options-form">
                        <legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
                        <div>
                        <?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
                        </div>
                    </fieldset>
                </div>
                <div class="col-12 col-lg-6">
                    <fieldset id="fieldset-metadata" class="options-form">
                        <legend><?php echo Text::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'); ?></legend>
                        <div>
                        <?php echo LayoutHelper::render('joomla.edit.metadata', $this); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endif; ?>

        <?php if (!$isModal && $assoc && $params->get('show_associations_edit', 1) == 1) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'associations', Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
            <fieldset id="fieldset-associations" class="options-form">
            <legend><?php echo Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS'); ?></legend>
            <div>
            <?php echo LayoutHelper::render('joomla.edit.associations', $this); ?>
            </div>
            </fieldset>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php elseif ($isModal && $assoc) : ?>
            <div class="hidden"><?php echo LayoutHelper::render('joomla.edit.associations', $this); ?></div>
        <?php endif; ?>

        <?php if ($this->canDo->get('core.admin') && $params->get('show_configure_edit_options', 1) == 1) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'editor', Text::_('COM_CONTENT_SLIDER_EDITOR_CONFIG')); ?>
            <fieldset id="fieldset-editor" class="options-form">
                <legend><?php echo Text::_('COM_CONTENT_SLIDER_EDITOR_CONFIG'); ?></legend>
                <div class="form-grid">
                <?php echo $this->form->renderFieldset('editorConfig'); ?>
                </div>
            </fieldset>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endif; ?>

        <?php if ($this->canDo->get('core.admin') && $params->get('show_permissions', 1) == 1) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'permissions', Text::_('COM_CONTENT_FIELDSET_RULES')); ?>
            <fieldset id="fieldset-rules" class="options-form">
                <legend><?php echo Text::_('COM_CONTENT_FIELDSET_RULES'); ?></legend>
                <div>
                <?php echo $this->form->getInput('rules'); ?>
                </div>
            </fieldset>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endif; ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

        <?php // Creating 'id' hiddenField to cope with com_associations sidebyside loop ?>
        <?php if ($params->get('show_publishing_options', 1) == 0) : ?>
            <?php $hidden_fields = $this->form->getInput('id'); ?>
            <div class="hidden"><?php echo $hidden_fields; ?></div>
        <?php endif; ?>

        <input type="hidden" name="task" value="">
        <input type="hidden" name="return" value="<?php echo $input->getBase64('return'); ?>">
        <input type="hidden" name="forcedLanguage" value="<?php echo $input->get('forcedLanguage', '', 'cmd'); ?>">
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
