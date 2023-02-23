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
use THM\Groups\Helpers\Attributes;
use THM\Groups\Helpers\Groups;
use THM\Groups\Helpers\Roles;
use THM\Groups\Helpers\Types;
use THM\Groups\Tables;

/**
 * Has functions for migrating resources from the old structures.
 */
class Migration
{
    /**
     * Migrates the attributes table.
     *
     * @return array an array mapping the existing attributes table to the new one
     */
    private static function attributes(): array
    {
        $db  = Application::getDB();
        $map = [];

        $query         = $db->getQuery(true);
        $oldAttributes = $db->quoteName('#__thm_groups_attributes');
        $query->select('*')->from($oldAttributes);
        $db->setQuery($query);

        // Migration
        if ($oldAttributes = $db->loadObjectList('label'))
        {
            $labelMap = [
                'Aktuell' => 12,
                'Email' => 2,
                'Email2' => 11,
                'E-Mail 2' => 11,
                'E-Mail-2' => 11,
                'Fax' => 9,
                'Nachname' => 1,
                'Namenszusatz (nach)' => 4,
                'Namenszusatz (vor)' => 5,
                'Telefon' => 7,
                'Telefon 2' => 8,
                'Vorname' => 3,
                'weiteres Fax' => 10,
                'Weiteres Fax' => 10,
                'weitere Informationen' => 13,
                'Weitere Informationen' => 13,
                'weiteres Telefon' => 8,
                'Weiteres Telefon' => 8,
                'Zur Person' => 14
            ];

            foreach ($labelMap as $label => $newAttributeID)
            {
                // New standard attribute not found in existing attributes
                if (!$oldAttribute = $oldAttributes[$label] ?? false)
                {
                    continue;
                }

                $map[$oldAttribute->id] = $newAttributeID;

                // No need to iterate over these in the dynamic section
                unset($oldAttributes[$label]);

            }

            // Everything left is dynamic / not yet supported
            foreach ($oldAttributes as $label => $oldAttribute)
            {
                $data         = [];
                $newAttribute = new Tables\Attributes();

                switch ($label)
                {
                    case 'E-Mail 3':
                    case 'E-Mail 4':

                        $data['label_de'] = $label;

                        // Dynamic attribute has already been recreated.
                        if ($newAttribute->load($data))
                        {
                            $map[$oldAttribute->id] = $newAttribute->id;
                            continue 2;
                        }

                        $data['label_en']      = $oldAttribute->label;
                        $data['typeID']        = Types::EMAIL;
                        $data['configuration'] = '{}';

                        break;

                    case 'Fax_privat':
                    case 'Telefon_privat':

                        $tmpLabel         = str_replace('_privat', '', $label);
                        $data['label_de'] = "$tmpLabel (privat)";

                        // Dynamic attribute has already been recreated.
                        if ($newAttribute->load($data))
                        {
                            $map[$oldAttribute->id] = $newAttribute->id;
                            continue 2;
                        }

                        $tmpLabel              = $tmpLabel === 'Fax' ? $tmpLabel : 'Telephone';
                        $data['label_en']      = "$tmpLabel (private)";
                        $data['typeID']        = Types::TELEPHONE;
                        $data['configuration'] = '{}';

                        break;

                    case 'Mobil':

                        $data['label_de'] = $label;

                        // Dynamic attribute has already been recreated.
                        if ($newAttribute->load($data))
                        {
                            $map[$oldAttribute->id] = $newAttribute->id;
                            continue 2;
                        }

                        $data['label_en']      = 'Cell';
                        $data['typeID']        = Types::TELEPHONE;
                        $data['configuration'] = '{}';

                        break;

                    case 'Geburtstag':

                        $data['label_de'] = $oldAttribute->label;

                        // Dynamic attribute has already been recreated.
                        if ($newAttribute->load($data))
                        {
                            $map[$oldAttribute->id] = $newAttribute->id;
                            continue 2;
                        }

                        $data['label_en']      = 'Birthday';
                        $data['typeID']        = Types::DATE;
                        $data['configuration'] = '{}';

                        break;

                    // Mostly unused MNI cross-reference between supervisor and supervised.
                    case 'Ansprechpartner':

                        // Formatting aid LSE department.
                    case 'Leerzeile zwischen Kontakten':

                        // Explicitly ignoring attributes which will not be migrated.
                        $map[$oldAttribute->id] = 0;
                        continue 2;

                    // Not yet supported.
                    default:
                        continue 2;
                }

                $data['icon']        = $oldAttribute->icon ?? '';
                $data['context']     = Attributes::PROFILES_CONTEXT;
                $data['required']    = $oldAttribute->viewLevelID;
                $data['viewLevelID'] = $oldAttribute->viewLevelID;

                $newAttribute->save($data);
                $map[$oldAttribute->id] = $newAttribute->id;
            }
        }

        return $map;

        /*
        Round 1-----------------------------------------------------------------------------------------------------------------
        Label                   attributeID     icon            type/ID             levelID
        -----                   -----------     ----            -------             -------
        Aktuell                 12              notification    Types::HTML         1
        Email                   2               mail            Types::EMAIL        1
        E-Mail 2                11              mail            Types::EMAIL        1
        E-Mail-2                11              mail            Types::EMAIL        1
        Email2                  11              mail            Types::EMAIL        1
        E-Mail 3                x               envelope        Types::EMAIL        1
        E-Mail 4                x               envelope        Types::EMAIL        1
        Fax                     9               print           Types::TELEPHONE    1
        Fax_privat              x               print           Types::TELEPHONE    x
        Geburtstag              x               calendar        Types::DATE         x
        Mobil                   x               phone           Types::TELEPHONE    x
        Nachname                1               -               Types::NAME         1
        Namenszusatz (nach)     4               -               Types::SUPPLEMENT   1
        Namenszusatz (vor)      5               -               Types::SUPPLEMENT   1
        Telefon                 7               phone           Types::TELEPHONE    1
        Telefon 2               8               phone           Types::TELEPHONE    1
        Telefon_privat          x               phone           Types::TELEPHONE    x
        Vorname                 3               -               Types::NAME         1
        weiteres Fax            10              print           Types::TELEPHONE    1
        Weitere Informationen   13              -               Types::HTML         1
        weiteres Telefon        8               phone           Types::TELEPHONE    1
        Zur Person              14              user            Types::HTML         1

        --Ignore
        Ansprechpartner                 not migrated
        Leerzeile zwischen Kontakten    not migrated

        Links & such------------------------------------------------------------------------------------------------------------

        -Button: url displayed as an icon with optional localized tip
        -Link: url displayed as text with optional localized text
        -Room: room name, optional url, optional localized tip

        Label           attributeID     icon        type/ID         levelID     configuration
        -----           -----------     ----        -------         -------     -------------
        Büro            x               home        Types::ROOM     1           {"hint":"A10.2.01","regex":"room?"}
        Homepage        x               new-tab     Types::LINK     1           {"hint":"www.thm.de/fb/maxine-mustermann"}
        Raum            x               home        Types::ROOM     1           {"hint":"A10.2.01","regex":"room?"}
        Raum 2          x               home        Types::ROOM     1           {"hint":"A10.2.01","regex":"room?"}
        Twitter         x               twitter     Types::BUTTON   1           {"hint":"twitter?","regex":"twitter?"}
        Web             x               new-tab     Types::LINK     1           {"hint":"www.thm.de/fb/maxine-mustermann"}
        Webseite        x               new-tab     Types::LINK     1           {"hint":"www.thm.de/fb/maxine-mustermann"}
        XING            x               xing        Types::BUTTON   1           {"hint":"xing?","regex":"xing?"}
        weiterer Raum   x               home        Types::ROOM     1           {"hint":"A10.2.01","regex":"room?"}

        Images-----------------------------------------------------------------------------------------------------------------

        -localized caption
        -source with optional url
        -cropping dimensions

        Label       attributeID     icon    type/ID         levelID     configuration
        -----       -----------     ----    -------         -------     -------------
        Bild        6               -       Types::IMAGE    1           {"caption_de":"","caption_en":"","source":"","source_url":""}
        Profilbild  6               -       Types::IMAGE    1           {"caption_de":"","caption_en":"","source":"","source_url":""}

        --Additional optional localized field group
        Quelle Bild....source & source_url

        Subforms & such---------------------------------------------------------------------------------------------------------
        Label           attributeID     icon        type/ID     levelID     comment
        -----           -----------     ----        -------     -------     -------
        Anschrift       x               location    x           1           so many options...
        Sprechstunde    x               comment     x           1           weekdays & times + checkbox for 'by appointment'
        Sprechstunden   x               comment     x           1           see Sprechstunde
        Sprechzeiten    x               comment     x           1           see Sprechstunde

        106 2(x/HTML)   Arbeitsgebiete          11{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}22101
        107 1(Text)     Besondere Funktion      1icon-cog1{"maxlength":"255","hint":"","regex":"simple_text"}17101
        119 1(Text)     Fachgebiet              1icon-grid-view1{"maxlength":"255","hint":"","regex":"simple_text"}18101
        108 1(Text)     Fachgebiete             1icon-grid-view1{"maxlength":"255","hint":"","regex":"simple_text"}12101
        112 2(x/HTML)   Forschungsgebiete       10{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}17101
        110 2(x/HTML)   Funktionen              1icon-list-21{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}11101
        102 2(x/HTML)   Lebenslauf              1icon-list1{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}16101
        111 2(x/HTML)   Weitere Profile         1icon-users1{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}10101
        109 2(x/HTML)   Weiterführende Links    1icon-new-tab1{}13101

        --- Subform with THM Room Patterned text boxes + optional link field for labs
        - Migrate Raum 2 / weiterer Raum values to Raum values
        - Laboratories, Offices, Rooms

        Outliers----------------------------------------------------------------------------------------------------------------
        Label                   attributeID     icon            type/ID             levelID
        -----                   -----------     ----            -------             -------
        --LSE incomplete addresses with no standardization
        95  2(x/HTML)   Kontakt     1icon-location1{"buttons":"0","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}7101
        112 2(x/HTML)   weiterer  Kontakt1icon-location-21{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}14101

        --IEM ???
        113 2(x/HTML)   Projekte10{"buttons":1,"hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}18101
        114 2(x/HTML)   Veranstaltungen10{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}16101
        115 2(x/HTML)   Ohne  Überschrift00{"buttons":"1","hide":"ebevent,ebregister,thm_groups_profiles,snippets,betterpreview,sliders,thmvcard,thmcontact,widgetkit,module,menu,contact,fields,jresearch_automatic_bibliography_generation,jresearch_automatic_citation,modals,pagebreak,readmore"}20101

        --W mixed bag, but mostly a link to a 'schriftenverzeichnis'
        101 2(x/HTML)   Publikationen   1icon-stack1{}17101

        --WI ???
        95  2(x/HTML)       Profil1112101
        */
    }

    /**
     * Migrates the existing store of usergroups to groups.
     */
    private static function groups()
    {
        $db    = Application::getDB();
        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__groups_groups'))
            ->columns([$db->quoteName('id'), $db->quoteName('name_de'), $db->quoteName('name_en')])
            ->values(":id, :name_de, :name_en")
            ->bind(':id', $groupID, ParameterType::INTEGER)
            ->bind(':name_de', $name)
            ->bind(':name_en', $name);

        // Make no exception for standard groups here, in order to have comparable output to the users component.
        foreach (UserGroupsHelper::getInstance()->getAll() as $groupID => $group)
        {
            $table = new Tables\Groups();

            // Already there
            if ($table->load($groupID))
            {
                continue;
            }

            $name = $group->title;

            $db->setQuery($query);
            $db->execute();
        }
    }

    /**
     * Migrates exiting data to the new tables.
     */
    public static function migrate()
    {
        $session = Application::getSession();

        if (!$session->get('com_groups.migrated.groups'))
        {
            self::groups();
            $session->set('com_groups.migrate.groups', true);
        }

        if (!$session->get('com_groups.migrated.profiles'))
        {
            self::profiles();
            $session->set('com_groups.migrate.profiles', true);
        }

        if (!$session->get('com_groups.migrated.roles'))
        {
            $rMap  = self::roles();
            $raMap = self::roleAssociations($rMap);
            self::profileAssociations($raMap);
            $session->set('com_groups.migrate.roles', true);
        }

        if (!$session->get('com_groups.migrated.attributes'))
        {
            $aMap = self::attributes();
            self::profileAttributes($aMap);
            $session->set('com_groups.migrated.attributes', true);
        }
    }

    /**
     * Migrates the profile associations table.
     *
     * @param array $assocMap
     *
     * @return void
     */
    private static function profileAssociations(array $assocMap)
    {
        $db = Application::getDB();

        // Direct migration from the old table to the new table with the assoc ids updated according to the map.
        $query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__thm_groups_profile_associations'));
        $db->setQuery($query);

        if ($oldAssocs = $db->loadObjectList())
        {
            foreach ($oldAssocs as $oldAssoc)
            {
                $assoc = [
                    'assocID' => $assocMap[$oldAssoc->role_associationID],
                    'profileID' => $oldAssoc->profileID
                ];

                $table = new Tables\ProfileAssociations();

                if (!$table->load($assoc))
                {
                    $table->save($assoc);
                }
            }
        }

        // Get the users associated with groups in the Joomla user => usergroup map
        $query = $db->getQuery(true);
        $query->select('DISTINCT ' . $db->quoteName('user_id'))
            ->from($db->quoteName('#__user_usergroup_map'))
            ->where($db->quoteName('group_id') . ' = :groupID')
            ->bind(':groupID', $groupID, ParameterType::INTEGER);

        foreach (Groups::getIDs() as $groupID)
        {
            // Ignore standard groups which would only be used for site privileges.
            if (in_array($groupID, Groups::DEFAULT))
            {
                continue;
            }

            $db->setQuery($query);

            // The group has not directly associated users, nothing to supplement.
            if (!$userIDs = $db->loadColumn())
            {
                continue;
            }

            $rAssocTable = new Tables\RoleAssociations();

            // Get the basic group => member role association entry and supplement the profile as necessary.
            if ($assocID = $rAssocTable->getAssocID($groupID, Roles::MEMBER))
            {
                foreach ($userIDs as $profileID)
                {
                    $pAssoc      = ['assocID' => $assocID, 'profileID' => $profileID];
                    $pAssocTable = new Tables\ProfileAssociations();

                    if (!$pAssocTable->load($pAssoc))
                    {
                        $pAssocTable->save($pAssoc);
                    }
                }
            }
            else
            {
                $group = new Tables\Groups();
                $name  = $group->getName($groupID);
                Application::message("Group \"$name\" does not have a member association.", 'error');
            }
        }
    }

    /**
     * Migrates the profile attribute mappings and values to the new table.
     *
     * @param array $map
     *
     * @return void
     */
    private static function profileAttributes(array $map)
    {
        $db      = Application::getDB();
        $oldKeys = array_keys($map);

        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->quoteName('#__thm_groups_profile_attributes'))
            ->whereIn($db->quoteName('attributeID'), $oldKeys);
        $db->setQuery($query);

        if (!$pas = $db->loadObjectList())
        {
            return;
        }

        foreach ($pas as $pa)
        {
            $data = ['profileID' => $pa->profileID, 'attributeID' => $map[$pa->attributeID]];

            $table = new Tables\ProfileAttributes();

            if ($table->load($data))
            {
                continue;
            }

            $data['value']     = $pa->value;
            $data['published'] = $pa->published;

            $table->save($data);
        }
    }

    /**
     * Migrates the profiles table.
     */
    private static function profiles()
    {
        $db = Application::getDB();

        $query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__thm_groups_profiles'));
        $db->setQuery($query);

        // No existing data
        if (!$profiles = $db->loadObjectList('id'))
        {
            return;
        }

        $alias          = null;
        $id             = 0;
        $canEdit        = false;
        $contentEnabled = false;
        $published      = false;

        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__groups_profiles'))
            ->columns([
                $db->quoteName('alias'),
                $db->quoteName('id'),
                $db->quoteName('canEdit'),
                $db->quoteName('contentEnabled'),
                $db->quoteName('published')
            ])
            ->values(":alias, :id, :canEdit, :contentEnabled, :published")
            ->bind(':alias', $alias)
            ->bind(':id', $id, ParameterType::INTEGER)
            ->bind(':canEdit', $canEdit, ParameterType::BOOLEAN)
            ->bind(':contentEnabled', $contentEnabled, ParameterType::BOOLEAN)
            ->bind(':published', $published, ParameterType::BOOLEAN);

        foreach ($profiles as $profileID => $profile)
        {
            $alias          = $profile->alias ?? null;
            $id             = $profileID;
            $canEdit        = $profile->canEdit ?? false;
            $contentEnabled = $profile->contentEnabled ?? false;
            $published      = $profile->published ?? false;

            $table = new Tables\Profiles();

            if ($table->load($profileID))
            {
                $table->alias          = $alias;
                $table->canEdit        = $canEdit;
                $table->contentEnabled = $contentEnabled;
                $table->published      = $published;

                $table->store(true);
                continue;
            }

            $db->setQuery($query);
            $db->execute();
        }
    }

    /**
     * Migrates the role associations table.
     *
     * @param array $rMap an array mapping the existing roles table to the new one
     *
     * @return array an array mapping the existing role associations table to the new one
     */
    private static function roleAssociations(array $rMap): array
    {
        $db = Application::getDB();

        $query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__thm_groups_role_associations'));
        $db->setQuery($query);

        $map = [];

        // rMap has to be filled for this to return results
        if ($assocs = $db->loadObjectList())
        {
            foreach ($assocs as $assoc)
            {
                // Mapping to standard groups is not valid
                if (in_array($assoc->groupID, Groups::DEFAULT))
                {
                    continue;
                }

                $table = new Tables\RoleAssociations();
                $data  = ['groupID' => $assoc->groupID, 'roleID' => $rMap[$assoc->roleID]];

                if ($table->load($data))
                {
                    $map[$assoc->id] = $table->id;
                    continue;
                }

                $table->save($data);
                $map[$assoc->id] = $table->id;
            }
        }
        // no existing data
        else
        {
            $groupIDs = Groups::getIDs();

            foreach ($groupIDs as $groupID)
            {
                if (in_array($groupID, Groups::DEFAULT))
                {
                    continue;
                }

                $table = new Tables\RoleAssociations();
                $assoc = ['groupID' => $groupID, 'roleID' => Roles::MEMBER];

                if ($table->load($assoc))
                {
                    continue;
                }

                $table->save($assoc);
            }
        }

        return $map;
    }

    /**
     * Creates any role entries not included in the standard installation.+
     *
     */
    private static function roles(): array
    {
        $db  = Application::getDB();
        $map = [];

        $query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__thm_groups_roles'));
        $db->setQuery($query);

        // No existing data
        if (!$oldRoles = $db->loadObjectList())
        {
            return $map;
        }

        $nameDE = $db->quoteName('name_de');

        // Create a prepared statement to find roles based on their name.
        $query = $db->getQuery(true);
        $query->select($db->quoteName('id'))
            ->from($db->quoteName('#__groups_roles'))
            ->where("$nameDE LIKE :thmName");

        $oldOrdering = [];

        foreach ($oldRoles as $oldRole)
        {
            $oldID = $oldRole->id;

            $oldOrdering[$oldRole->ordering] = $oldID;

            //name
            $table   = new Tables\Roles();
            $thmName = $oldRole->name;

            // Exact match 50% of THM roles
            if ($table->load(['name_de' => $thmName]))
            {
                $map[$oldID] = $table->id;
                continue;
            }

            // Two known changes that wouldn't work with like.
            if ($thmName === 'Koordinatorin')
            {
                $map[$oldID] = 9;
                continue;
            }

            if ($thmName === 'ProfessorInnen')
            {
                $map[$oldID] = 10;
                continue;
            }

            //  German gender changes (+:in/:innen)
            $name = trim($thmName) . '%';
            $query->bind(':thmName', $name);
            $db->setQuery($query);

            if ($groupsID = $db->loadResult())
            {
                $map[$oldID] = $groupsID;
                continue;
            }

            // Non-standard/additional roles
            $migrant = [
                'name_de' => $thmName,
                'name_en' => $thmName,
                'names_de' => $thmName,
                'names_en' => $thmName,

                // Ordering has no default value, will be set correctly in the next portion of the function.
                'ordering' => 0
            ];

            $table->save($migrant);
            $map[$oldID] = $table->id;
        }

        $roleIDs  = array_unique(array_values($map));
        $ordering = 1;
        ksort($oldOrdering);
        $oldOrdering = array_flip($oldOrdering);

        foreach (array_keys($oldOrdering) as $oldID)
        {
            $roleID = $map[$oldID];

            if (!$position = array_search($roleID, $roleIDs))
            {
                continue;
            }

            $table = new Tables\Roles();
            $table->load($roleID);
            $table->ordering = $ordering;
            $table->store();

            $ordering++;
            unset($roleIDs[$position]);
        }

        return $map;
    }
}