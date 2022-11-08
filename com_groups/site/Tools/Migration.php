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
id  typeID  label   showLabel   icon    showIcon    options     ordering    published   required    viewLevelID
96  4       Bild    0                   0           {"mode":1}  0           1           0           1
105 4       Bild    0                   0           {"mode":1}  0           1           0           1
95  4       Bild    0                   0           {"mode":1}  0           1           0           1
104 4       Bild    0                   0           {"mode":1}  0           1           0           1
97  4       Bild    0                   0           {"mode":1}  7           1           0           1
97  4       Bild    0                   0           {"mode":1}  4           1           0           1
104 4       Bild    0                   0           {"mode":1}  0           1           0           1
104 4       Bild    0                   0           {"mode":1}  0           1           0           1
104 4       Bild    0                   0           {"mode":1}  0           1           0           1
96  4       Bild    0                   0           {"mode":1}  0           1           0           1

-BAU--------------------------------------------------------------------------------------------------------------------
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
46Email1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
1007Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
1017Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
1063Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}8101
1022Anschrift1icon-location1{"buttons":0}9101
1032Sprechstunden1icon-comment1{"buttons":0}10101
1042Weitere  Informationen1icon-info1{}11101
-EI---------------------------------------------------------------------------------------------------------------------
5   9       Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
1   8       Vorname00{"hint":"Maxine"}2101
2   8       Nachname00{"hint":"Mustermann"}3111
7   9       Namenszusatz  (nach)11{"hint":"M.Sc."}4101
4   6       EMail1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
91  7       Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
92  7       Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
97  3       Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}8101
93  2       Anschrift1icon-location1{"buttons":0}9101
94  2       Sprechstunden1icon-comment1{"buttons":0}10101
95  2       Weitere  Informationen1icon-info1{}11101
-ME---------------------------------------------------------------------------------------------------------------------
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
46EMail1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
911Raum1icon-home1{"hint":"A1.0.01"}8101
922Sprechzeiten1icon-comment1{"buttons":0}10101
937Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
947Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
962Weitere-Informationen1icon-info1{}11101
972Anschrift1icon-location1{"buttons":0}9101
-LSE--------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
46EMail1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}6111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
917Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}9101
927Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}10101
952Kontakt1icon-location1{"buttons":"0","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}7101
972Sprechstunden1icon-comment1{"buttons":0}11101
1002Weitere  Informationen1icon-info0{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}18101
1053Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}12101
1061Raum1icon-home1{"hint":"A1.0.01"}8101
1077weiteres  Telefon1icon-phone1{"maxlength":"255","hint":"","validate":"1","regex":"european_telephone"}16101
1101weiterer  Raum1icon-home1{"maxlength":"255","hint":"","regex":"simple_text"}15101
1122weiterer  Kontakt1icon-location-21{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}14101
11312weiteres  Fax1icon-printer1{"maxlength":"255","hint":"","regex":"simple_text"}17101
1151Quelle  Bild00{"maxlength":"255","hint":"","regex":"simple_text"}5101
1161Leerzeile  zwischen  Kontakten00{"maxlength":"255","hint":"","regex":"simple_text"}13101
-GES--------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}0001
28Nachname00{"hint":"Mustermann"}0011
46Email1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}0011
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}0001
79Namenszusatz  (nach)11{"hint":"M.Sc."}0001
-MNI--------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}3101
28Nachname00{"hint":"Mustermann"}4111
46Email1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}1111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}5101
79Namenszusatz  (nach)11{"hint":"M.Sc."}6101
913Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}8101
927Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}11101
931Raum1icon-home1{"hint":"A1.0.01"}9101
942Sprechstunden1icon-comment1{"buttons":0}14101
952Zur  Person1icon-user1{}15101
962Weitere  Informationen1icon-info1{}16101
1001Ansprechpartner1icon-user-check1{"hint":"Prof.  Dr.  Kontakt  Person"}17101
1041Raum  21icon-home1{"hint":"A1.0.01"}10101
1057Telefon  21icon-phone1{"hint":"+49  (0)  641  309  1234"}12101
1062Anschrift1icon-location1{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}0101
1077Fax1icon-phone-21{"maxlength":"255","hint":"","validate":1,"regex":"european_telephone"}13101
1086E-Mail-20icon-mail1{"maxlength":"255","hint":"","validate":1,"regex":"email"}2101
-W----------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}1101
28Nachname00{"hint":"Mustermann"}2111
46EMail1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}6111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}0101
79Namenszusatz  (nach)11{"hint":"M.Sc."}3101
913Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}5101
927Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}10101
931Raum1icon-home1{"hint":"A1.0.01"}13101
942Sprechstunden1icon-comment1{"buttons":0}14101
952Weitere  Informationen1icon-info1{}15101
1012Publikationen1icon-stack1{}17101
1022Lebenslauf1icon-list1{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}16101
1036E-Mail  21icon-mail1{"maxlength":"255","hint":"maxine.mustermann@fb.thm.de","validate":"1","regex":"email"}7101
1046E-Mail  31icon-envelope1{"maxlength":"255","hint":"","validate":1,"regex":"email"}8101
1061XING0icon-xing-21{"maxlength":"255","hint":"","regex":"simple_text"}12101
1073Twitter0icon-twitter1{"maxlength":"255","hint":"","validate":1,"regex":"url"}11101
1086E-Mail  41icon-envelope1{"maxlength":"255","hint":"","validate":1,"regex":"email"}9101
-IEM--------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
46EMail1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
917Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
927Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
952Anschrift1icon-location0{"buttons":0}14001
972Sprechstunde1icon-comment1{"buttons":"0","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}15101
1002Weitere  Informationen1icon-info0{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}19101
1053Webseite1icon-new-tab1{"maxlength":"255","hint":"www.thm.de \/fb \/maxine-mustermann","validate":"1","regex":"url"}8101
1061Raum1icon-home1{"maxlength":"255","hint":"","regex":"simple_text"}9101
1081Fachgebiete1icon-grid-view1{"maxlength":"255","hint":"","regex":"simple_text"}12101
1092Weiterführende  Links1icon-new-tab1{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}13101
1102Funktionen1icon-list-21{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}11101
1112Weitere  Profile1icon-users1{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}10101
1122Forschungsgebiete10{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}17101
1132Projekte10{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}18101
1142Veranstaltungen10{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}16101
1152Ohne  Überschrift00{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}20101
-M----------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
46EMail1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
917Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
927Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
952Anschrift1icon-location1{"buttons":0}10101
972Sprechstunden1icon-comment1{"buttons":0}8101
1002Weitere  Informationen1icon-info1{}11101
1053Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}9101
-MND--------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
46Email1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
1007Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}7101
1017Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}9101
1023Web1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}12101
1031Raum1icon-home1{"hint":"A1.0.01"}13101
1062Arbeitsgebiete11{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}22101
1071Besondere  Funktion1icon-cog1{"maxlength":"255","hint":"","regex":"simple_text"}17101
1102Anschrift1icon-location1{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}201012
1147Telefon_privat1icon-phone1{"hint":"+49  (0)  641  309  1234"}81012
1157Fax_privat1icon-print1{"hint":"+49  (0)  641  309  1235"}101012
1167Mobil1icon-mobile1{"hint":"+49  (0)  167  123  1235"}111012
1175Geburtstag1icon-calendar1{"required":false}191012
1191Fachgebiet1icon-grid-view1{"maxlength":"255","hint":"","regex":"simple_text"}18101
1212Sprechzeiten1icon-comment1{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}21101
1222Aktuell11{"length":120,"required":false,"icon":"icon-notification"}23101
1236Email21icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}6001
-WI---------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
46EMail1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
917Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
927Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
932Anschrift1icon-location1{"buttons":0}9101
942Sprechstunden1icon-comment1{"buttons":0}10101
952Profil1112101
992Weitere  Informationen1icon-info1{}11101
1003Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}8101
-MUK--------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
46Email1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
917Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
927Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
932Anschrift1icon-location1{"buttons":0}9101
942Sprechstunden1icon-comment1{"buttons":0}10101
952Weitere  Informationen1icon-info1{}11101
964Bild00{"mode":1}0101
973Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}8101
-FSZ--------------------------------------------------------------------------------------------------------------------
18Vorname00{"hint":"Maxine"}1101
28Nachname00{"hint":"Mustermann"}2111
34Bild00{"mode":1}5101
46Email1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}8111
59Namenszusatz  (vor)00{"hint":"Prof.  Dr."}0101
67Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
79Namenszusatz  (nach)00{"hint":"M.Sc."}3101
87Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
93Homepage1icon-new-tab1{"hint":"www.thm.de/fb/maxine-mustermann"}0101
101Büro1icon-home1{"hint":"A1.0.01"}0101
112Sprechstunden1icon-comment1{"buttons":0}0101
122Anschrift1icon-location1{"buttons":0}4101
132Weitere  Informationen1icon-info0{}9101
-ZDH--------------------------------------------------------------------------------------------------------------------
1034Profilbild00{"path":" \/images \/com_thm_groups \/profile \/","required":false}0101
18Vorname00{"hint":"Maxine"}2101
28Nachname00{"hint":"Mustermann"}3111
46Email1icon-mail1{"hint":"maxine.mustermann@fb.thm.de"}5111
59Namenszusatz  (vor)11{"hint":"Prof.  Dr."}1101
79Namenszusatz  (nach)11{"hint":"M.Sc."}4101
1007Telefon1icon-phone1{"hint":"+49  (0)  641  309  1234"}6101
1017Fax1icon-print1{"hint":"+49  (0)  641  309  1235"}7101
1022Anschrift1icon-location1{"buttons":0}8101
1052Weitere  Informationen1icon-info1{}9101
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