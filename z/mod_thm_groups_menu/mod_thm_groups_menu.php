<?php
/**
 * @package     Groups
 * @extension   mod_thm_groups_menu
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

require_once JPATH_ROOT . '/media/com_thm_groups/helpers/categories.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/menu.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/router.php';

use Joomla\CMS\Helper\ModuleHelper;
use THM\Groups\Adapters\{Input, Text};
use THM\Groups\Helpers\{Can, Profiles, Users};

$userID = Input::integer('profileID');

if (!empty($userID)) {
    // used in layout file
    $content = Users::content($userID);
    if ($content) {
        $contents = THM_GroupsHelperMenu::getContent($userID);
    }

    $contentExists = !empty($contents);
    $view          = Input::view();
    $showAdmin     = Users::editing($userID);
    $showSubMenu   = false;

    $isOwner        = Can::identity($userID);
    $displayedTitle = $params->get('displayedTitle', 'module_title');
    if ($displayedTitle == 'profile_name') {
        if ($showAdmin) {
            $name          = Profiles::name($userID);
            $module->title = Text::sprintf('MOD_THM_GROUPS_ADMINISTRATION', $name);
        }
        else {
            $showTitles    = $params->get('showTitles', 1);
            $module->title = Profiles::name($userID, $showTitles);
        }
    }

    $contentID     = Input::id();
    $contentParams = ['view' => 'content', 'profileID' => $userID];
    $managerParams = ['view' => 'content_manager', 'profileID' => $userID];
    $profileParams = ['view' => 'profile', 'profileID' => $userID];

    if ($showAdmin and $contentExists) {
        $showSubMenu   = true;
        $subMenuHeader = Text::_('CONTENT_MENU');
    }

    require ModuleHelper::getLayoutPath('mod_thm_groups_menu');
}
