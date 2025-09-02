<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{Application, Document, Text, Toolbar};
use THM\Groups\Helpers\Can;
use THM\Groups\Views\HTML\ListView;

require_once HELPERS . 'batch.php';

/**
 * THM_GroupsViewProfile_Manager class for component com_thm_groups
 */
class THM_GroupsViewProfile_Manager extends ListView
{
    public array $batch;

    public array $groups;

    /**
     * Method to get display
     *
     * @param   Object  $tpl  template
     *
     * @return void
     * @throws Exception
     */
    public function display($tpl = null): void
    {
        if (!Can::manage()) {
            Application::error(401);
        }

        $this->batch = [
            'profiles' => JPATH_COMPONENT_ADMINISTRATOR . '/views/profile_manager/tmpl/default_profiles.php',
            'roles'    => JPATH_COMPONENT_ADMINISTRATOR . '/views/profile_manager/tmpl/default_roles.php'
        ];

        $this->groups = THM_GroupsHelperBatch::groups();

        parent::display($tpl);
    }

    /**
     * Add Joomla ToolBar with add edit delete options.
     *
     * @return void
     */
    protected function addToolbar(): void
    {
        $toolbar = Toolbar::instance();

        $script           = 'onclick="jQuery(\'#modal-profiles\').modal(\'show\'); return true;"';
        $newProfileButton = '<button id="profiles" data-toggle="modal" class="btn btn-small" ' . $script . '>';
        $title            = Text::_('ADD_PROFILES');
        $newProfileButton .= '<span class="icon-new" title="' . $title . '"></span>' . " $title";
        $newProfileButton .= '</button>';
        $toolbar->customButton($newProfileButton, 'profiles');

        $toolbar->edit('profile.edit')->listCheck(true);
        $toolbar->publish('profile.publish', Text::_('PUBLISH_PROFILE'))->listCheck(true);
        $toolbar->unpublish('profile.hide', Text::_('HIDE_PROFILE'))->listCheck(true);
        $toolbar->publish('profile.publishContent', Text::_('ACTIVATE_CONTENT_MANAGEMENT'))->listCheck(true);
        $toolbar->unpublish('profile.hideContent', Text::_('DEACTIVATE_CONTENT_MANAGEMENT'))->listCheck(true);

        $layout = new JLayoutFile('joomla.toolbar.batch');
        $title  = Text::_('ADD_ROLES');
        $batch  = $layout->render(['title' => $title]);

        $toolbar->customButton($batch, 'roles');

        JToolbarHelper::help('COM_GROUPS_TEMPLATES_DOCUMENTATION', '',
            JUri::root() . 'media/com_thm_groups/documentation/profile_manager.php');

        parent::addToolbar();
    }

    /** @inheritDoc */
    protected function modifyDocument(): void
    {
        parent::modifyDocument();
        Document::script('jquery.chained.remote');
        Document::script('profile_manager');
        Document::script('remove_association');
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        // TODO: Implement initializeColumns() method.
    }
}
