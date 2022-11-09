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

/**
 * Has functions for migrating resources from the old structures.
 */
class Migration
{
	/**
	 * Migrates the attributes table.
	 *
	 * @param   array  $atMap  an array mapping the existing attribute types to the new ones
	 *
	 * @return array an array mapping the existing attributes table to the new one
	 */
	private static function attributes(array $atMap)
	{
		$db = Application::getDB();

		$query         = $db->getQuery(true);
		$oldAttributes = $db->quoteName('#__thm_groups_attributes');
		$query->select('*')->from($oldAttributes);
		$db->setQuery($query);

		$oldAttributes = $db->loadObjectList('id');
		//echo "<pre>" . print_r($oldAttributes, true) . "</pre>";
		//die;
/*
 * ignore config: mode, path, regex (if it holds a constant), required
 *

	migrate:
	'Nachname' 2 => 'Namen' 1
	'Vorname' 1 => 'Vornamen' 2
	'Email' 4 => 'E-Mail' 3
	'Bild' / 'Profilbild' ? => 'Profilbild' 4
	'Namenszusatz (vor)' 5 => 'Namenszusatz (vor)' 5
	'Namenszusatz (nach)' 7 => 'Namenszusatz (nach)' 6
	'Telefon' ? => 'Telefon' 7
	'Fax' ? => 'Fax' 8
	'Homepage' ? => 'Homepage' 9
	'Anschrift' / 'Kontakt' ? => 'Anschrift' 10
	'Büro' ? => 'Office' 11
	'Raum' ? => 'Raum' 12
	'Sprechstunden' / 'Sprechzeiten' ? => 'Sprechstunden' 12
	'Weitere Informationen' / 'Weitere-Informationen' ? => 'Weitere-Informationen' 13
	'%E-Mail%' at:6
	'%Telefon%' at:7
	'%Mobil%' at:7
	'%Fax%' at:7
	'XING' at:3
	'Twitter' at:3

`id`, `label_de`, `label_en`, `icon`, `typeID`, `configuration`, `context`, `required`, `viewLevelID`

id  typeID  label   showLabel   icon    showIcon    options     ordering    published   required    viewLevelID


-LSE--------------------------------------------------------------------------------------------------------------------
1101weiterer  Raum1icon-home1{"maxlength":"255","hint":"","regex":"simple_text"}15101
1122weiterer  Kontakt1icon-location-21{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}14101
1151Quelle  Bild00{"maxlength":"255","hint":"","regex":"simple_text"}5101
1161Leerzeile  zwischen  Kontakten00{"maxlength":"255","hint":"","regex":"simple_text"}13101
-MNI--------------------------------------------------------------------------------------------------------------------
952Zur  Person1icon-user1{}15101
1001Ansprechpartner1icon-user-check1{"hint":"Prof.  Dr.  Kontakt  Person"}17101
1041Raum  21icon-home1{"hint":"A1.0.01"}10101
-W----------------------------------------------------------------------------------------------------------------------
1012Publikationen1icon-stack1{}17101
1022Lebenslauf1icon-list1{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}16101
-IEM--------------------------------------------------------------------------------------------------------------------
1053Webseite1icon-new-tab1{"maxlength":"255","hint":"www.thm.de \/fb \/maxine-mustermann","validate":"1","regex":"url"}8101
1081Fachgebiete1icon-grid-view1{"maxlength":"255","hint":"","regex":"simple_text"}12101
1092Weiterführende  Links1icon-new-tab1{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}13101
1102Funktionen1icon-list-21{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}11101
1112Weitere  Profile1icon-users1{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}10101
1122Forschungsgebiete10{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}17101
1132Projekte10{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}18101
1142Veranstaltungen10{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}16101
1152Ohne  Überschrift00{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}20101
-MND--------------------------------------------------------------------------------------------------------------------
1023Web1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}12101
1062Arbeitsgebiete11{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}22101
1071Besondere  Funktion1icon-cog1{"maxlength":"255","hint":"","regex":"simple_text"}17101
1175Geburtstag1icon-calendar1{"required":false}191012
1191Fachgebiet1icon-grid-view1{"maxlength":"255","hint":"","regex":"simple_text"}18101
1222Aktuell11{"length":120,"required":false,"icon":"icon-notification"}23101
-WI---------------------------------------------------------------------------------------------------------------------
952Profil1112101
 */
	}

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
		/*if (!self::compare())
		{
			return;
		}

		self::groups();
		$rMap  = self::roles();
		$raMap = self::roleAssociations($rMap);*/

		// Fax was added as an attribute type by someone who didn't understand the difference between attributes and types.
		$atMap = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 12 => 7];
		$aMap  = self::attributes($atMap);
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

			$table->save($migrant);
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