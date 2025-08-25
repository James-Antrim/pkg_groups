<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\MVC\View\AbstractView;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Users;

require_once HELPERS . 'menu.php';

use THM\Groups\Helpers\Profiles as Helper;

/**
 * JSON Profile View
 */
class Profile extends AbstractView
{
    /**
     * Method to get display
     *
     * @param   Object  $tpl  template
     *
     * @return void
     */
    public function display($tpl = null): void
    {
        if (!$profileID = Input::integer('profileID') or !Users::published($profileID)) {
            Application::error(404);
        }

        if (!$profile = Helper::raw($profileID)) {
            echo json_encode([]);

            return;
        }

        $nameAttributeIDs = [FORENAME, SURNAME, TITLE, POSTTITLE];
        $specialFieldIDs  = [FILE];

        $json = [
            'profileName' => Helper::name($profileID, true),
            'profileLink' => THM_GroupsHelperRouter::build(['view' => 'profile', 'profileID' => $profileID])
        ];

        if (Users::content($profileID)) {
            $contentParams           = ['view' => 'content', 'profileID' => $profileID];
            $contents                = THM_GroupsHelperMenu::getContent($profileID);
            $json['profileContents'] = [];

            foreach ($contents as $content) {
                $url                                      = THM_GroupsHelperRouter::build($contentParams + ['id' => $content->id]);
                $json['profileContents'][$content->title] = $url;
            }
        }

        foreach ($profile as $attributeID => $properties) {
            // Suppress redundant (name) attributes and files
            if (in_array($attributeID, $nameAttributeIDs) or in_array($properties['fieldID'], $specialFieldIDs)) {
                continue;
            }

            $json[$properties['label']] = $properties['value'];
        }

        echo json_encode($json);
    }
}
