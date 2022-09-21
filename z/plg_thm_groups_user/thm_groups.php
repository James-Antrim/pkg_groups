<?php
/**
 * @package     THM_Groups
 * @extension   plg_thm_groups_user
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

/** @noinspection PhpIncludeInspection */
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/groups.php';
/** @noinspection PhpIncludeInspection */
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/profiles.php';

/**
 * THM Groups User plugin
 *
 * @category  Joomla.Plugin.User
 * @package   THM_Groups
 */
class PlgUserTHM_Groups extends JPlugin
{
	// Default User Groups
	const DEFAULT_GROUPS = [1, 2, 3, 4, 5, 6, 7, 8];

	/**
	 * Automatically gives users the member role in any groups with which they are associated
	 *
	 * @param   int    $profileID  the profile id
	 * @param   array  $groupIDs   the group ids
	 *
	 * @return  bool  true if the user was given the member role in associated groups, otherwise false
	 * @throws Exception
	 */
	private function associateMemberRole($profileID, $groupIDs)
	{
		$groupAssocs = [];

		foreach ($groupIDs as $groupID)
		{
			if ($assocID = THM_GroupsHelperGroups::associateRole(1, $groupID))
			{
				$groupAssocs[] = $assocID;
			}
		}

		foreach ($groupAssocs as $assocID)
		{
			if (!THM_GroupsHelperProfiles::associateRole($profileID, $assocID))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Deletes user from a group both in Joomla and in THM Groups.
	 *
	 * @param   int    $profileID  the id of the profile to delete the associations of
	 * @param   array  $groupIDs   the ids of groups with which the profile should no longer be associated
	 *
	 * @return bool true if the groups were successfully disassociated, otherwise false
	 *
	 * @throws Exception
	 */
	private function deleteAssociations($profileID, $groupIDs)
	{
		$app           = JFactory::getApplication();
		$dbo           = JFactory::getDbo();
		$profileAssocs = THM_GroupsHelperProfiles::getRoleAssociations($profileID);

		foreach ($groupIDs as $groupID)
		{
			$groupAssocs = THM_GroupsHelperGroups::getRoleAssocIDs($groupID);

			if ($disposableAssocs = array_intersect($profileAssocs, $groupAssocs))
			{
				$groupsQuery = $dbo->getQuery(true);
				$groupsQuery->delete('#__thm_groups_profile_associations')
					->where("role_associationID IN ('" . implode("','", $disposableAssocs) . "')")
					->where("profileID = $profileID");
				$dbo->setQuery($groupsQuery);

				try
				{
					if (!$dbo->execute())
					{
						return false;
					}
				}
				catch (Exception $exception)
				{
					$app->enqueueMessage($exception->getMessage(), 'error');

					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Checks whether the given profile id already exists in THM Groups tables
	 *
	 * @param   int  $profileID  the id of the joomla user
	 *
	 * @return bool true if the user is new to groups, otherwise false
	 * @throws Exception
	 */
	private function isNewToGroups($profileID)
	{
		$dbo   = JFactory::getDbo();
		$query = $dbo->getQuery(true);
		$query->select('id')->from('#__thm_groups_profiles')->where("id = '$profileID'");
		$dbo->setQuery($query);
		try
		{
			$result = $dbo->loadResult();

			return empty($result);
		}
		catch (Exception $exception)
		{
			JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

			return false;
		}
	}

	/**
	 * Method is called after user data is stored in the database
	 *
	 * @param   array   $user     Holds the new user data.
	 * @param   bool    $isNew    True if a new user is stored.
	 * @param   bool    $success  True if user was successfully stored in the database.
	 * @param   string  $msg      Message.
	 *
	 * @return    bool
	 * @throws Exception
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function onUserAfterSave($user, $isNew, $success, $msg)
	{
		$dbo       = JFactory::getDbo();
		$groupIDs  = array_diff($user['groups'], self::DEFAULT_GROUPS);
		$profileID = $user['id'];

		// The person is only associated with default groups and should therefore irrelevant to THM Groups
		if (empty($groupIDs))
		{

			// The profile was disassociated from THM Groups relevant groups in using the Joomla interface.
			if (THM_GroupsHelperProfiles::getRoleAssociations($profileID))
			{
				$query = $dbo->getQuery(true);
				$query->delete('#__thm_groups_profile_associations')->where("profileID = $profileID");
				$dbo->setQuery($query);

				try
				{
					$dbo->execute();
				}
				catch (Exception $exception)
				{
					JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

					return false;
				}

				return $this->unpublish($profileID);
			}

			return true;
		}

		// True new, no entry exists
		$isNewToGroups = $this->isNewToGroups($profileID);
		if ($isNew or $isNewToGroups)
		{
			if (!THM_GroupsHelperProfiles::createProfile($profileID))
			{
				return false;
			}
		}
		else
		{
			$email = $user['email'];
			list($forename, $surname) = THM_GroupsHelperProfiles::resolveUserName($profileID, $user['name']);

			$profileUpdated = THM_GroupsHelperProfiles::fillAttributes($profileID, $forename, $surname, $email);
			if (empty($profileUpdated))
			{
				return false;
			}
		}

		$aliasSet = THM_GroupsHelperProfiles::setAlias($profileID);
		if (empty($aliasSet))
		{
			return false;
		}


		$currentGroupIDs = THM_GroupsHelperProfiles::getGroupAssociations($profileID);
		if ($dGroupIDs = array_diff($currentGroupIDs, $groupIDs))
		{
			if (!$this->deleteAssociations($profileID, $dGroupIDs))
			{
				return false;
			}
		}

		return $this->associateMemberRole($profileID, $groupIDs);
	}

	/**
	 * Creates a group => member role association for a new group.
	 *
	 * @param   string  $context     com_users.group
	 * @param   Table   $groupTable  the group table object
	 * @param   bool    $isNew       whether or not the group is new to the instance
	 * @param   array   $data        the form data
	 *
	 * @return void
	 * @noinspection PhpUnusedParameterInspection
	 * @throws Exception
	 */
	public function onUserAfterSaveGroup($context, $groupTable, $isNew, $data)
	{
		if (!$groupID = $groupTable->id or in_array($groupID, self::DEFAULT_GROUPS))
		{
			return;
		}

		if (!THM_GroupsHelperGroups::getRoleAssocIDs($groupID))
		{
			THM_GroupsHelperGroups::associateRole(1, $groupID);
		}
	}

	/**
	 * Unpublishes the groups disassociated profile.
	 *
	 * @param   int  $profileID  the id of the profile to unpublish
	 *
	 * @return bool true on success, otherwise false
	 * @throws Exception
	 */
	private function unpublish($profileID)
	{
		$dbo   = JFactory::getDbo();
		$query = $dbo->getQuery(true);
		$query->update('#__thm_groups_profiles')
			->set('published = 0')
			->set('contentEnabled = 0')
			->where("id = $profileID");
		$dbo->setQuery($query);

		try
		{
			return (bool) $dbo->execute();
		}
		catch (Exception $exception)
		{
			JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

			return false;
		}
	}
}