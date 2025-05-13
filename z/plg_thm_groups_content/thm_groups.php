<?php
/**
 * @category    Joomla plugin
 * @package     THM_Groups
 * @subpackage  plg_thm_groups_content
 * @name        PlgContentThm_Groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2017 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT . '/media/com_thm_groups/helpers/categories.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/content.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/renderer.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/router.php';

/**
 * THM Groups content plugin
 *
 * @category  Joomla.Plugin.Content
 * @package   THM_Groups
 */
class PlgContentThm_Groups extends JPlugin
{
    /**
     * Ensures consistency for content saved in the context of com_thm_groups
     *
     * @param   string   $context  The context of the content passed to the plugin
     * @param   object   $article  A JTableContent object
     * @param   boolean  $isNew    If the content is just about to be created
     *
     * @return  boolean   true if the groups associations were saved correctly or irrelevant, otherwise false
     */
    public function onContentAfterSave($context, $article, $isNew)
    {
        // Don't run this plugin when the content context is not correct or dependencies are missing
        if (($context != 'com_content.form' and $context != 'com_content.article')) {
            return true;
        }

        $profileID = THM_GroupsHelperCategories::getProfileID($article->catid);

        // Irrelevant
        if (empty($profileID)) {
            return true;
        }

        $associationExists = THM_GroupsHelperContent::isAssociated($article->id, $profileID);
        if ($associationExists) {
            return true;
        }

        return THM_GroupsHelperContent::associate($article->id, $profileID);
    }

    /**
     * Ensures consistency for content saved in the context of com_thm_groups
     *
     * @param   string   $context  The context of the content passed to the plugin
     * @param   object   $article  A JTableContent object
     * @param   boolean  $isNew    If the content is just about to be created
     *
     * @return  boolean   true if the groups associations were saved correctly or irrelevant, otherwise false
     */
    public function onContentBeforeSave($context, $article, $isNew)
    {
        // Don't run this plugin when the content context is not correct or dependencies are missing
        if (($context != 'com_content.form' and $context != 'com_content.article')) {
            return true;
        }

        $profileID = THM_GroupsHelperCategories::getProfileID($article->catid);

        // Irrelevant
        if (empty($profileID)) {
            return THM_GroupsHelperContent::disassociate($article->id);
        }

        $article->created_by = $profileID;

        return true;
    }

    /**
     * Removes THM Groups parameters from articles texts.
     *
     * @param   string   $context  The context of the content being passed to the plugin.
     * @param   mixed   &$row      An object with a "text" property or the string to be removed.
     * @param   integer  $page     Optional page number. Unused. Defaults to zero.
     *
     * @return  boolean    True on success.
     * @throws Exception
     */
    public function onContentPrepare($context, &$row, $page = 0)
    {
        // Don't run this plugin when the content is being indexed or dependencies are missing
        if ($context == 'com_finder.indexer') {
            return true;
        }

        $sef = JFactory::getConfig()->get('sef', 1);

        if (is_object($row)) {
            THM_GroupsHelperRenderer::contentURLS($row->text);
            THM_GroupsHelperRenderer::modProfilesParams($row->text);

            if ($sef) {
                THM_GroupsHelperRenderer::groupsQueries($row->text);
            }
        }
        else {
            THM_GroupsHelperRenderer::contentURLS($row);
            THM_GroupsHelperRenderer::modProfilesParams($row);

            if ($sef) {
                THM_GroupsHelperRenderer::groupsQueries($row);
            }
        }


        return true;
    }
}
