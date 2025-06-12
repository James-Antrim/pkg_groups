<?php
/**
 * @package     THM_Groups
 * @extension   mod_thm_groups_profiles
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';
require_once JPATH_ROOT . "/media/com_thm_groups/helpers/groups.php";
require_once JPATH_ROOT . "/media/com_thm_groups/helpers/profiles.php";

$app = JFactory::getApplication();
JFactory::getLanguage()->load('com_thm_groups');
$input = $app->input;

switch ($input->get('view')) {
    // The group and profile ID will be a part of the URL for THM Groups own views.
    case 'advanced':
    case 'overview':
    case 'content':

        $profileID = $input->getInt('profileID');
        if (!empty($profileID)) {
            $dynamicParams               = [];
            $dynamicParams['profileIDs'] = [$profileID];
        }

        break;

    // This will first look in the article and then the containing categories for THM Groups parameter hooks. Stops on first positive.
    case 'article':

        $articleID     = $input->getInt('id');
        $dynamicParams = THM_GroupsHelperProfilesModule::getArticleParameters($articleID);

        break;

    // This will look in the category and then the parent categories for THM Groups parameter hooks. Stops on first positive.
    case 'category':

        $categoryID    = $input->getInt('id');
        $dynamicParams = THM_GroupsHelperProfilesModule::getCategoryParameters($categoryID);
        break;
}

// If there is no specific template as part of the article hook, the template configured for the module will be used. Default: Standard.
if (!isset($dynamicParams['templateID'])) {
    $dynamicParams['templateID'] = empty($params['templateID']) ? null : $params['templateID'];
}

$profileIDs = THM_GroupsHelperProfilesModule::getProfileIDs($dynamicParams, $params);

// No output if there was nothing found.
if (!empty($profileIDs)) {
    $document = $app->getDocument();
    $document->addStyleSheet(JUri::base() . 'media/com_thm_groups/css/advanced.css');
    $hide = JText::_('COM_THM_GROUPS_ACTION_HIDE');
    $read = JText::_('COM_THM_GROUPS_ACTION_DISPLAY');
    $document->addScriptOptions('com_thm_groups', ['hide' => $hide, 'read' => $read]);
    $document->addScript(JUri::base() . 'media/com_thm_groups/js/toggle_text.js');
    require JModuleHelper::getLayoutPath('mod_thm_groups_profiles');
}
