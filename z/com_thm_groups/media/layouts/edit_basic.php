<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

/**
 * Class provides standardized output of list items
 */
class THM_GroupsLayoutEdit_Basic
{
    /**
     * Method to create a form output based solely on the xml configuration.
     *
     * @param   object &$view  the view context calling the function
     *
     * @return void
     */
    public static function render(&$view)
    {
        ?>
        <form action="<?php echo JURI::base(); ?>"
              enctype="multipart/form-data"
              method="post"
              name="adminForm"
              id="adminForm"
              class="form-horizontal form-validate">
            <fieldset class="adminform">
                <?php echo $view->form->renderFieldset('details'); ?>
            </fieldset>
            <input type="hidden" name="option" value="com_thm_groups"/>
            <?php echo $view->form->getInput('id'); ?>
            <?php echo JHtml::_('form.token'); ?>
            <input type="hidden" name="task" value=""/>
        </form>
        <?php
    }
}
