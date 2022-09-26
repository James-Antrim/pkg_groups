<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      Dennis Priefer, <dennis.priefer@mni.thm.de>
 * @author      Niklas Simonis, <niklas.simonis@mni.thm.de>
 * @author      Ilja Michajlow, <ilja.michajlow@mni.thm.de>
 * @author      Dieudonne Timma Meyatchie, <dieudonne.timma.meyatchie@mni.thm.de>
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use THM\Groups\Helpers\Can;
use THM\Groups\Helpers\Component;

/**
 * THM_GroupsViewTHM_Groups class for component com_thm_groups
 */
class Groups extends BaseView
{
	protected $_layout = 'list';

    /**
     * Method to get display
     *
     * @param   Object $tpl template
     *
     * @return void
     */
    public function display($tpl = null)
    {
        if ($this->backend and !Can::manage()) {
            Component::error(403);
        }

        //JHtml::_('bootstrap.tooltip');

        //THM_GroupsHelperComponent::addSubmenu($this);

        //$this->addToolBar();

        parent::display($tpl);
    }

    /**
     *
     *
     * @return void
     */
//    protected function addToolBar()
//    {
//        JToolBarHelper::title(JText::_('COM_THM_GROUPS'), 'logo');
//
//        if (THM_GroupsHelperComponent::isAdmin()) {
//            JToolBarHelper::preferences('com_thm_groups');
//        }
//    }
}
