<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Helpers\Can;
use THM\Groups\Helpers\Categories;
use THM\Groups\Views\HTML\Titled;

defined('_JEXEC') or die;

require_once HELPERS . 'content.php';
require_once JPATH_ROOT . "/media/com_thm_groups/views/list.php";

/**
 * THM_GroupsViewContent_Manager class for component com_thm_groups
 */
class THM_GroupsViewContent_Manager extends THM_GroupsViewList
{
    use Titled;

    /**
     * Method to get display
     *
     * @param   Object  $tpl  template
     *
     * @return void
     */
    public function display($tpl = null)
    {
        if (!Can::manage()) {
            $exc = new Exception(JText::_('JLIB_RULES_NOT_ALLOWED'), 401);
            JErrorPage::render($exc);
        }

        parent::display($tpl);
    }

    /**
     * Add Joomla ToolBar with add edit delete options.
     *
     * @return void
     */
    protected function addToolbar()
    {
        $this->title('CONTENT');

        $rootCategory = Categories::root();

        if (!empty($rootCategory)) {
            JToolBarHelper::publishList('content.feature', 'COM_THM_GROUPS_FEATURE');
            JToolBarHelper::unpublishList('content.unfeature', 'COM_THM_GROUPS_UNFEATURE');
        }

        if (Can::administrate()) {
            JToolBarHelper::preferences('com_thm_groups');
        }

        JToolbarHelper::help('COM_THM_GROUPS_TEMPLATES_DOCUMENTATION', '',
            JUri::root() . 'media/com_thm_groups/documentation/content.php');
    }
}
