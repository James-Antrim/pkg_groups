<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Helpers\Groups;

defined('_JEXEC') or die;

use THM\Groups\Helpers\Profiles as Helper;

/**
 * Class provides methods to retrieve data for pool ajax calls
 */
class THM_GroupsModelProfile_Ajax extends JModelLegacy
{

    /**
     * Gets profile options for use in content
     *
     * @return string the concatenated profile options
     * @throws Exception
     */
    public function getContentOptions()
    {
        $groupID    = JFactory::getApplication()->input->getInt('groupID', 0);
        $profiles   = [];
        $profileIDs = Groups::profileIDs($groupID);

        foreach ($profileIDs as $profileID) {
            $displayName = Helper::name($profileID, true);
            if (empty($displayName)) {
                continue;
            }

            $lnfName = Helper::lnfName($profileID);

            $link = JUri::base() . "?option=com_thm_groups&view=profile&profileID=$profileID";

            $profiles[$lnfName] = [
                'id'          => $profileID,
                'displayName' => $displayName,
                'link'        => $link,
                'sortName'    => $lnfName
            ];
        }

        ksort($profiles);

        return json_encode($profiles);
    }
}
