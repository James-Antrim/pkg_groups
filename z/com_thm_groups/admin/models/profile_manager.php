<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      Ilja Michajlow, <ilja.michajlow@mni.thm.de>
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use THM\Groups\Adapters\{Database as DB, Text};
use THM\Groups\Helpers\{Can, Profiles as Helper};
use THM\Groups\Models\ListModel;

defined('_JEXEC') or die;

/**
 * THM_GroupsModelProfile_Manager class for component com_thm_groups
 */
class THM_GroupsModelProfile_Manager extends ListModel
{
    protected string $defaultOrdering = "surname";

    /**
     * Return groups with roles of a user by ID
     *
     * @param   int profileID the user ID
     *
     * @return  array the association IDs
     * @throws Exception
     */
    private function getAssociations($profileID)
    {
        $query    = DB::query();
        $template = 'GROUP_CONCAT(DISTINCT %s ORDER BY %s SEPARATOR ", ") AS %s';

        $groups = DB::qn(['ug.id', 'ug.title'], ['groupID', 'groupName']);
        $roles  = [
            sprintf($template, 'roles.id', 'roles.name', 'roleID'),
            sprintf($template, 'roles.name', 'roles.name', 'roleName')
        ];
        $query
            ->select(array_merge($groups, $roles))
            ->from(DB::qn('#__groups_profile_associations', 'pa'))
            ->leftJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('pa.role_associationID', 'ra.id'))
            ->leftJoin(DB::qn('#__usergroups', 'ug'), DB::qc('ug.id', 'ra.groupID'))
            ->leftJoin(DB::qn('#__groups_roles', 'roles'), DB::qc('ra.roleID', 'roles.id'))
            ->where(DB::qcs([['pa.profileID', $profileID], ['ra.groupID', 1, '>']]))
            ->group(DB::qn('groupID'));
        DB::set($query);

        return DB::arrays() ?: [];
    }

    /**
     * Generates HTML with links for disassociation of groups/roles with the user being iterated
     *
     * @param   int   $profileID  the id of the user being iterated
     * @param   bool  $canEdit    whether the user is authorized to edit associations
     *
     * @return  string the HTML output
     * @throws Exception
     */
    private function getAssocLinks(int $profileID, bool $canEdit)
    {
        $associations = $this->getAssociations($profileID);
        $result       = "";
        $deleteIcon   = '<span class="icon-delete"></span>';

        $deleteRoleParameters = "GROUPID,ROLEID,$profileID";
        $roleTitle            = Text::_('GROUP') . ": GROUPNAME - ";
        $roleTitle            .= Text::_('ROLE') . ": ROLENAME::" . Text::_('REMOVE_ROLE');

        // this should be reworked with dynamic task setting like toggle $roleAssocID
        $rawRoleLink = '<a onclick="disassociateRole(' . $deleteRoleParameters . ');" ';
        $rawRoleLink .= 'title="' . $roleTitle . '" class="hasTooltip">' . $deleteIcon . '</a>ROLENAME';

        $deleteGroupParameters = "'profile',GROUPID,$profileID";
        $groupTitle            = Text::_('GROUP') . ": GROUPNAME::" . Text::_('REMOVE_ALL_ROLES');

        // this should be reworked with dynamic task setting like toggle cb$id and $groupID
        $rawGroupLink = '<a onclick="disassociateGroup(' . $deleteGroupParameters . ');" ';
        $rawGroupLink .= 'title="' . $groupTitle . '" class="hasTooltip">' . $deleteIcon;
        $rawGroupLink .= '</a><strong>GROUPNAME</strong> : ';

        foreach ($associations as $association) {

            $roles      = explode(', ', $association['roleName']);
            $groupRoles = [];
            $groupName  = $association['groupName'];

            // If there is only one role in group, don't show delete icon
            if (count($roles) == 1) {
                $groupRoles[] = $roles[0];
            }
            else {
                $roleIDs          = explode(', ', $association['roleID']);
                $specificRoleLink = str_replace('GROUPID', $association['groupID'], $rawRoleLink);
                $specificRoleLink = str_replace('GROUPNAME', $groupName, $specificRoleLink);

                // If there are many roles, show delete icon
                foreach ($roles as $index => $role) {
                    // Don't show member role when there are multiple roles
                    if ($roleIDs[$index] == 1) {
                        continue;
                    }

                    // Allow to edit groups only for authorised users
                    if ($canEdit) {
                        $groupRoles[] = str_replace('ROLENAME', $role,
                            str_replace('ROLEID', $roleIDs[$index], $specificRoleLink));

                    }
                    else {
                        $groupRoles[] = $role;
                    }

                }
            }

            // Allow to edit groups only for authorised users
            if ($canEdit) {

                // If the user is only in one group, do not allow the removal of the association in this component.
                if (count(JFactory::getUser($profileID)->groups) === 1) {
                    $groupLink = "<strong>$groupName</strong> : ";
                }
                else {
                    $groupLink = str_replace('GROUPID', $association['groupID'], $rawGroupLink);
                    $groupLink = str_replace('GROUPNAME', $groupName, $groupLink);
                }
                $result .= $groupLink . implode(', ', $groupRoles) . '<br>';
            }
        }

        return $result;
    }

    /**
     * Function to get table headers
     *
     * @return array including headers
     */
    public function getHeaders()
    {
        $ordering  = $this->state->get('list.ordering');
        $direction = $this->state->get('list.direction');

        $headers                   = [];
        $headers['checkbox']       = '';
        $headers['surname']        = JHtml::_('searchtools.sort', Text::_('NAME'), 'surname, forename', $direction, $ordering);
        $headers['published']      = JHtml::_('searchtools.sort', Text::_('PUBLISHED'), 'published', $direction, $ordering);
        $headers['canEdit']        = JHtml::_('searchtools.sort', Text::_('PROFILE_EDIT'), 'canEdit', $direction, $ordering);
        $headers['contentEnabled'] = JHtml::_(
            'searchtools.sort', Text::_('CONTENT_ENABLED'), 'contentEnabled', $direction, $ordering
        );
        $headers['gnr']            = Text::_('ASSOCIATED_GROUPS_AND_ROLES');

        return $headers;
    }

    /**
     * Returns custom hidden fields for page
     *
     * @return array
     */
    public function getHiddenFields()
    {
        $fields = [];

        // Hidden fields for batch processing
        $fields[] = '<input type="hidden" name="groupID" value="">';
        $fields[] = '<input type="hidden" name="profileID" value="">';
        $fields[] = '<input type="hidden" name="roleID" value="">';

        return $fields;
    }

    /**
     * Function to feed the data in the table body correctly to the list view
     *
     * @return array consisting of items in the body
     * @throws Exception
     */
    public function getItems()
    {
        $return = [];
        $items  = parent::getItems();
        if (empty($items)) {
            return $return;
        }

        $canEdit = Can::manage();
        $index   = 0;
        foreach ($items as $item) {
            $url            = "index.php?option=com_thm_groups&view=profile_edit&id=$item->profileID";
            $return[$index] = [];

            $return[$index][0] = JHtml::_('grid.id', $index, $item->profileID);
            $return[$index][1] = JHtml::_('link', $url, Helper::lnfName($item->profileID));

            $return[$index][2] = $this->getToggle($item->profileID, $item->published, 'profile', '', 'published');
            $return[$index][3] = $this->getToggle($item->profileID, $item->canEdit, 'profile', '', 'canEdit');
            $return[$index][4] = $this->getToggle($item->profileID, $item->contentEnabled, 'profile', '',
                'contentEnabled');
            $return[$index][5] = $this->getAssocLinks($item->profileID, $canEdit);

            $index++;
        }

        return $return;
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        $query = DB::query();

        $select = 'DISTINCT profile.id as profileID, profile.published, profile.canEdit, profile.contentEnabled, ';
        $select .= 'forenames, surnames, email';

        $query->select('*');
        $query->from(DB::qn('#__users', 'u'));

        $this->setSearchFilter($query, ['id', 'forenames', 'surnames', 'email']);

        $this->setIDFilter($query, 'content', 'filter.content');
        $this->setIDFilter($query, 'editing', 'filter.editing');
        $this->setIDFilter($query, 'published', 'filter.published');

        $filterGroups = $this->state->get('list.groupID');
        $filterRoles  = $this->state->get('list.roleID');

        if ($filterGroups or $filterRoles) {
            $query->leftJoin(DB::qn('#__user_usergroup_map', 'uugm'), DB::qc('uugm.user_id', 'u.id'))
                ->leftJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.mapID', 'uugm.id'));

            if ($filterGroups) {
                $this->setIDFilter($query, 'ra.groupID', 'list.groupID');
            }

            if ($filterRoles) {
                $this->setIDFilter($query, 'ra.roleID', 'list.roleID');
            }
        }

        $this->orderBy($query);

        return $query;
    }
}
