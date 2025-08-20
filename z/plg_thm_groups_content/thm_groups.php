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

require_once JPATH_ROOT . '/media/com_thm_groups/helpers/content.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/renderer.php';

use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\{Categories, Pages};

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
     * @param   string  $context  The context of the content passed to the plugin
     * @param   object  $article  A JTableContent object
     * @param   bool    $isNew    If the content is just about to be created
     *
     * @return  bool
     */
    public function onContentAfterSave(string $context, $article, bool $isNew = false): bool
    {
        // Don't run this plugin when the content context is not correct or dependencies are missing
        if (($context != 'com_content.form' and $context != 'com_content.article')) {
            return true;
        }

        // Irrelevant
        if (!$userID = Categories::userID($article->catid)) {
            return true;
        }

        if (Pages::userID($article->id, $userID)) {
            return true;
        }

        return THM_GroupsHelperContent::associate($article->id, $userID);
    }

    /**
     * Ensures consistency for content saved in the context of com_thm_groups
     *
     * @param   string  $context  The context of the content passed to the plugin
     * @param   object  $article  A JTableContent object
     * @param   bool    $isNew    If the content is just about to be created
     *
     * @return  bool
     */
    public function onContentBeforeSave(string $context, $article, bool $isNew = false): bool
    {
        // Don't run this plugin when the content context is not correct or dependencies are missing
        if (($context != 'com_content.form' and $context != 'com_content.article')) {
            return true;
        }

        $profileID = Categories::userID($article->catid);

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
     * @param   int      $page     Optional page number. Unused. Defaults to zero.
     *
     * @return  bool    True on success.
     * @throws Exception
     */
    public function onContentPrepare(string $context, &$row, int $page = 0): bool
    {
        // Don't run this plugin when the content is being indexed or dependencies are missing
        if ($context == 'com_finder.indexer') {
            return true;
        }

        $sef = Application::configuration()->get('sef', 1);

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
