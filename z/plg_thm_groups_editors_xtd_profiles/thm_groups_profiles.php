<?php
/**
 * @package     THM_Groups
 * @extension   plg_thm_groups_editor_xtd_profiles
 * @author      James Antrim, <james.antrim@mni.thm.de>
 * @copyright   2019 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

defined('_JEXEC') or die;

/**
 * Class adds a button for the insertion of THM Groups profile links/parameter hooks in editor fields.
 *
 * @category    Joomla.Plugin.Editors
 * @package     thm_groups
 * @subpackage  plg_thm_groups_editor_xtd_profiles.site
 */
class PlgButtonTHM_Groups_Profiles extends JPlugin
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * Display the button.
     *
     * @param   string  $name  the name of the editor
     *
     * @return  object  the object modeling the button
     */
    public function onDisplay($name)
    {
        JFactory::getLanguage()->load('plg_editors-xtd_thm_groups_profiles');

        // Button
        $button          = new JObject;
        $button->modal   = true;
        $button->class   = 'btn';
        $button->text    = JText::_('PLG_THM_GROUPS_PROFILES_BUTTON');
        $button->name    = 'users';
        $button->link    = "index.php?option=com_thm_groups&view=profile_select&tmpl=component&editor=name";
        $button->options = "{handler: 'iframe', size:{x:'700', y:'600'}}";

        return $button;
    }
}
