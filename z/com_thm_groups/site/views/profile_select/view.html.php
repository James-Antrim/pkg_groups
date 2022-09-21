<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@mni.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */
defined('_JEXEC') or die;

/**
 * Class which loads data into the view output context
 * @link        www.thm.de
 */
class THM_GroupsViewProfile_Select extends JViewLegacy
{
    public $filterForm = null;

    /**
     * Method to create a list output
     *
     * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return void
     */
    public function display($tpl = null)
    {
        JHtml::_('bootstrap.tooltip');
        JHtml::_('jquery.ui', ['core', 'sortable']);
        $rootURI   = JUri::root();
        $document = JFactory::getDocument();
        $document->addStyleSheet($rootURI . 'media/jui/css/icomoon.css');
        $document->addStyleSheet($rootURI . 'media/jui/css/sortablelist.css');
        $document->addStyleSheet($rootURI . 'media/com_thm_groups/css/profile_select.css');
        $document->addScript($rootURI . 'media/com_thm_groups/js/profile_select.js');

        $this->filterForm = $this->get('FilterForm');

        parent::display();
    }
}
