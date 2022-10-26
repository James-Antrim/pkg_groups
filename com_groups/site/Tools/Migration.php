<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Tools;

use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\Database\ParameterType;
use THM\Groups\Adapters\Application;
use THM\Groups\Tables;

class Migration
{
	/**
	 * Compares the values of the role associations table to determine if a migration should be executed.
	 * @return bool true if migration should be executed, otherwise false
	 */
	private static function compare(): bool
	{
		$db = Application::getDB();

		$count = 'COUNT(' . $db->quoteName('id') . ')';

		$query = $db->getQuery(true);
		$ras   = $db->quoteName('#__groups_role_associations');
		$query->select($count)->from($ras);
		$db->setQuery($query);

		if (!$count1 = (int) $db->loadResult())
		{
			return true;
		}

		$query  = $db->getQuery(true);
		$thmRAs = $db->quoteName('#__thm_groups_role_associations');
		$query->select($count)->from($thmRAs);
		$db->setQuery($query);
		$count2 = (int) $db->loadResult();

		return $count2 > $count1;
	}

	/**
	 * Migrates the existing store of usergroups to groups.
	 */
	private static function groups()
	{
		$db = Application::getDB();

		$groups = $db->quoteName('#__groups_groups');
		$id     = $db->quoteName('id');
		$nameDE = $db->quoteName('name_de');
		$nameEN = $db->quoteName('name_en');
		$query  = $db->getQuery(true);
		$query->insert($groups)->columns([$id, $nameDE, $nameEN])->values(":groupID, :name_de, :name_en");

		foreach (UserGroupsHelper::getInstance()->getAll() as $groupID => $group)
		{
			$table = new Tables\Groups($db);

			// Already there
			if ($table->load($groupID))
			{
				continue;
			}

			// Array binding was sometimes failing without providing any clue to why.
			$query->bind(':groupID', $groupID, ParameterType::INTEGER)
				->bind(':name_de', $group->title)
				->bind(':name_en', $group->title);

			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Migrates exiting data to the new tables.
	 */
	public static function migrate()
	{
		if (!self::compare())
		{
			return;
		}

		self::groups();
		$rMap  = self::roles();
		$raMap = self::roleAssociations($rMap);
	}

	/**
	 * Migrates the role associations table.
	 *
	 * @param   array  $rMap  an array mapping the existing roles table to the new one
	 *
	 * @return array an array mapping the existing role associations table to the new one
	 */
	private static function roleAssociations(array $rMap): array
	{
		$db = Application::getDB();

		$query  = $db->getQuery(true);
		$thmRAs = $db->quoteName('#__thm_groups_role_associations');
		$query->select('*')->from($thmRAs);
		$db->setQuery($query);

		$map = [];

		foreach ($db->loadObjectList() as $assoc)
		{
			$table = new Tables\RoleAssociations($db);
			$data  = ['groupID' => $assoc->groupID, 'roleID' => $rMap[$assoc->roleID]];

			if ($table->load($data))
			{
				$map[$assoc->id] = $table->id;
				continue;
			}

			$table->save($data);
			$map[$assoc->id] = $table->id;
		}

		return $map;
	}

	/**
	 * Creates any role entries not included in the standard installation.+
	 *
	 */
	private static function roles(): array
	{
		$db = Application::getDB();

		// Get the old
		$query    = $db->getQuery(true);
		$thmRoles = $db->quoteName('#__thm_groups_roles');
		$query->select('*')->from($thmRoles);
		$db->setQuery($query);
		$thmRoles = $db->loadObjectList();

		$id     = $db->quoteName('id');
		$nameDE = $db->quoteName('name_de');
		$roles  = $db->quoteName('#__groups_roles');

		// Create a prepared statement to find roles based on their name.
		$query = $db->getQuery(true);
		$query->select($id)->from($roles)->where("$nameDE LIKE :thmName");

		$thmOrdering = [];
		$map         = [];

		foreach ($thmRoles as $thmRole)
		{
			$thmID = $thmRole->id;

			$thmOrdering[$thmRole->ordering] = $thmID;

			//name
			$table   = new Tables\Roles($db);
			$thmName = $thmRole->name;

			// Exact match 50% of THM roles
			if ($table->load(['name_de' => $thmName]))
			{
				$map[$thmID] = $table->id;
				continue;
			}

			// Two known changes that wouldn't work with like.
			if ($thmName === 'Koordinatorin')
			{
				$map[$thmID] = 9;
				continue;
			}

			if ($thmName === 'ProfessorInnen')
			{
				$map[$thmID] = 10;
				continue;
			}

			//  German gender changes (+:in/:innen)
			$name = trim($thmName) . '%';
			$query->bind(':thmName', $name);
			$db->setQuery($query);

			if ($groupsID = $db->loadResult())
			{
				$map[$thmID] = $groupsID;
				continue;
			}

			// Non-standard/additional roles
			$migrant = [
				'name_de'  => $thmName,
				'name_en'  => $thmName,
				'names_de' => $thmName,
				'names_en' => $thmName,

				// Ordering has no default value, will be set correctly in the next portion of the function.
				'ordering' => 0
			];

			if (!$table->save($migrant))
			{
				echo "<pre>" . print_r($table->getErrors(), true) . "</pre>";
			}
			$map[$thmID] = $table->id;
		}

		$roleIDs  = array_unique(array_values($map));
		$ordering = 1;
		ksort($thmOrdering);
		$thmOrdering = array_flip($thmOrdering);

		foreach (array_keys($thmOrdering) as $thmID)
		{
			$roleID = $map[$thmID];

			if (!$position = array_search($roleID, $roleIDs))
			{
				continue;
			}

			$table = new Tables\Roles($db);
			$table->load($roleID);
			$table->ordering = $ordering;
			$table->store();

			$ordering++;
			unset($roleIDs[$position]);
		}

		return $map;
	}
}