<?php
/**
 * @package     THM_Groups
 * @extension   mod_thm_groups_menu
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT . '/media/com_thm_groups/helpers/categories.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/menu.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/profiles.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/router.php';

use \Joomla\CMS\Factory as Factory;
use Joomla\CMS\Helper\ModuleHelper;
use \JText as Text;

$input     = Factory::getApplication()->input;
$profileID = $input->get('profileID');

if (!empty($profileID))
{
	$contentEnabled = THM_GroupsHelperMenu::contentEnabled($profileID);

	if ($contentEnabled)
	{
		$contents = THM_GroupsHelperMenu::getContent($profileID);
	}

	$contentExists = !empty($contents);
	$view          = $input->get('view');
	$showAdmin     = THM_GroupsHelperProfiles::canEdit($profileID);
	$showSubMenu   = false;

	$isOwner        = Factory::getUser()->id == $profileID;
	$displayedTitle = $params->get('displayedTitle', 'module_title');
	if ($displayedTitle == 'profile_name')
	{
		if ($showAdmin)
		{
			$name          = THM_GroupsHelperProfiles::getDisplayName($profileID);
			$module->title = Text::sprintf('MOD_THM_GROUPS_ADMINISTRATION', $name);
		}
		else
		{
			$showTitles    = $params->get('showTitles', 1);
			$module->title = THM_GroupsHelperProfiles::getDisplayName($profileID, $showTitles);
		}
	}

	$contentID     = $input->get('id');
	$contentParams = ['view' => 'content', 'profileID' => $profileID];
	$managerParams = ['view' => 'content_manager', 'profileID' => $profileID];
	$profileParams = ['view' => 'profile', 'profileID' => $profileID];

	if ($showAdmin and $contentExists)
	{
		$showSubMenu   = true;
		$subMenuHeader = Text::_('MOD_THM_GROUPS_CONTENT_MENU');
	}

	require ModuleHelper::getLayoutPath('mod_thm_groups_menu');
}
