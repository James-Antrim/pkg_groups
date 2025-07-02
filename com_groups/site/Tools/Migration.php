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
use THM\Groups\Adapters\{Application, Database as DB, Text};
use THM\Groups\Helpers\{Attributes, Groups, Profiles, Types};
use THM\Groups\Tables;

/**
 * Has functions for migrating resources from the old structures.
 */
class Migration
{
    private const FORENAMES = 1, SURNAMES = 2;

    // Banner => 4 non-existent before
    private const
        ABOUT_ME = 24,
        ACTIVITIES = 15,
        ADDRESS = 5,
        CLASSES = 21,
        CURRENT = 14,
        DUTIES = 18,
        EMAIL1 = 7,
        EMAIL2 = 8,
        FAX1 = 9,
        FAX2 = 10,
        FIELDS = 16,
        HOURS = 20,
        INFORMATION = 22,
        LINKS = 23,
        OFFICE = 6,
        PHONE1 = 11,
        PHONE2 = 12,
        PICTURE = 3,
        RESEARCH_FIELDS = 17,
        SUPPLEMENT_POST = 1,
        SUPPLEMENT_PRE = 2,
        URL = 13;

    /**
     * Provides standard preparation for attributes.
     *
     * @param   array   $data   the migrated data to be saved to the attributes table
     * @param   array   $map    the map of the deprecated attribute ids to the new attribute ids
     * @param   string  $label  the label for the migrated attribute
     * @param   int     $oldID  the id of the deprecated attribute entry
     *
     * @return bool true if an existing migration of the attribute exists, otherwise false
     */
    private static function attributePrep(array &$data, array &$map, string $label, int $oldID): bool
    {
        $new              = new Tables\Attributes();
        $data['label_de'] = $label;

        if ($new->load($data)) {
            $map[$oldID] = $new->id;
            // Dynamic attribute has already been recreated.
            return true;
        }

        // Overwritten as necessary
        $data['label_en'] = $label;
        $data['options']  = '{}';

        // Dynamic attribute has not yet been recreated.
        return false;
    }

    /**
     * Migrates the attributes table.
     * @return array an array mapping the old attribute ids to the new ones
     */
    private static function attributes(): array
    {
        $map           = [];
        $query         = DB::query();
        $oldAttributes = DB::qn('#__thm_groups_attributes');
        $query->select('*')->from($oldAttributes);
        DB::set($query);

        // Basic attributes (forenames and surnames have already been migrated to the entry)
        if ($oldAttributes = DB::objects('label')) {
            $labelMap = [
                'Aktuell'               => self::CURRENT,
                'Anschrift'             => self::ADDRESS,
                'Arbeitsgebiete'        => self::ACTIVITIES,
                'Besondere Funktion'    => self::DUTIES,
                'Bild'                  => self::PICTURE,
                'Büro'                  => self::OFFICE,
                'Email'                 => self::EMAIL1,
                'E-Mail 2'              => self::EMAIL2,
                'E-Mail-2'              => self::EMAIL2,
                'Email2'                => self::EMAIL2,
                'Fax'                   => self::FAX1,
                'Fachgebiet'            => self::FIELDS,
                'Fachgebiete'           => self::FIELDS,
                'Forschungsgebiete'     => self::RESEARCH_FIELDS,
                'Funktionen'            => self::DUTIES,
                'Homepage'              => self::LINKS,
                'Namenszusatz (nach)'   => self::SUPPLEMENT_POST,
                'Namenszusatz (vor)'    => self::SUPPLEMENT_PRE,
                'Profilbild'            => self::PICTURE,
                'Raum'                  => self::OFFICE,
                'Sprechstunde'          => self::HOURS,
                'Sprechstunden'         => self::HOURS,
                'Sprechzeiten'          => self::HOURS,
                'Telefon'               => self::PHONE1,
                'Telefon 2'             => self::PHONE2,
                'Veranstaltungen'       => self::CLASSES,
                'Web'                   => self::URL,
                'Webseite'              => self::URL,
                'Weitere Informationen' => self::INFORMATION,
                'Weitere-Informationen' => self::INFORMATION,
                'weiteres Fax'          => self::FAX2,
                'weiteres Telefon'      => self::PHONE2,
                'Weiterführende Links'  => self::LINKS,
                'Zur Person'            => self::ABOUT_ME
            ];

            foreach ($labelMap as $label => $newAttributeID) {

                // Ensure normal spacing for later comparisons
                $label = Text::trim($label);

                // New standard attribute not found in existing attributes
                if (!$oldAttribute = $oldAttributes[$label] ?? false) {
                    continue;
                }

                $map[$oldAttribute->id] = $newAttributeID;

                // No need to iterate over these in the dynamic section
                unset($oldAttributes[$label]);

            }

            // Everything left is dynamic
            foreach ($oldAttributes as $label => $oldAttribute) {
                $data  = [];
                $icon  = '';
                $oldID = $oldAttribute->id;

                switch ($label) {

                    /**
                     * Explicitly ignoring attributes which will not be migrated, or where values will be migrated to
                     * other attributes.
                     * Ansprechpartner: mostly unused MNI cross-reference between supervisor and supervised. => drop
                     * Leerzeile zwischen Kontakten: formatting aid for LSE department => drop
                     * Quelle Bild: source attributes for the profile picture
                     * Raum 2, weiterer Raum => office numbers
                     */
                    case 'Ansprechpartner':
                    case 'Leerzeile zwischen Kontakten':
                    case 'Quelle Bild':
                    case 'Raum 2':
                    case 'weiterer Raum':

                        $map[$oldAttribute->id] = 0;
                        continue 2;

                    case 'Projekte':
                    case 'Weitere Profile':

                        if (self::attributePrep($data, $map, $label, $oldID)) {
                            continue 2;
                        }

                        $data['label_en'] = match ($label) {
                            'Projekte' => 'Projects',
                            'Weitere Profile' => 'Other Profiles',
                        };

                        $data['typeID'] = Types::LIST;

                        $icon = match ($label) {
                            'Weitere Profile' => 'fa fa-external-link-alt',
                            default => ''
                        };

                        break;

                    case 'E-Mail 3':
                    case 'E-Mail 4':

                        if (self::attributePrep($data, $map, $label, $oldID)) {
                            continue 2;
                        }

                        $data['typeID'] = Types::EMAIL;
                        $icon           = 'fa fa-envelope';

                        break;

                    case 'Fax_privat':
                    case 'Telefon_privat':

                        $tmpLabel = str_replace('_privat', '', $label);
                        $labelDE  = "$tmpLabel (privat)";

                        if (self::attributePrep($data, $map, $labelDE, $oldID)) {
                            continue 2;
                        }

                        $data['label_en'] = $tmpLabel === 'Fax' ? 'Fax (private)' : 'Telephone (private)';
                        $data['typeID']   = Types::PHONE;
                        $icon             = $tmpLabel === 'Fax' ? 'fa fa-fax' : 'fa fa-phone';

                        break;

                    case 'Geburtstag':

                        if (self::attributePrep($data, $map, $label, $oldID)) {
                            continue 2;
                        }

                        $data['label_en'] = 'Birthday';
                        $data['typeID']   = Types::DATE;
                        $icon             = 'fa fa-calendar';

                        break;

                    case 'Kontakt':

                        $new              = new Tables\Attributes();
                        $data['label_de'] = $label;

                        // Attribute name has not yet been updated
                        if (!$new->load($data)) {

                            // Load using the default name
                            $data['label_de'] = 'Anschrift';
                            $new->load($data);

                            // Update names
                            $new->label_de = $label;
                            $new->label_en = 'Contact';
                            $new->store();
                        }

                        $map[$oldID] = $new->id;

                        continue 2;

                    case 'Mobil':

                        if (self::attributePrep($data, $map, $label, $oldID)) {
                            continue 2;
                        }

                        $data['label_en'] = 'Cell';
                        $data['typeID']   = Types::PHONE;
                        $icon             = 'fa fa-mobile';

                        break;

                    case 'Ohne  Überschrift':

                        if (self::attributePrep($data, $map, 'Traueranzeige', $oldID)) {
                            continue 2;
                        }

                        $data['label_en'] = 'Obituary';
                        $data['typeID']   = Types::HTML;
                        break;

                    // TODO data migration study during W migration subform?
                    case 'Publikationen':

                        if (self::attributePrep($data, $map, $label, $oldID)) {
                            continue 2;
                        }

                        $data['label_en'] = 'Publications';
                        $data['typeID']   = Types::HTML;
                        $icon             = 'fa fa-copy';
                        break;

                    case 'Social  Media':
                    case 'Twitter':
                    case 'XING':

                        $label = match ($label) {
                            // Only linked in URLs stored here
                            'Social  Media' => 'LinkedIn',
                            default => $label
                        };

                        if (self::attributePrep($data, $map, $label, $oldID)) {
                            continue 2;
                        }

                        //TODO add regex to the configuration
                        $data['label_en'] = $label;
                        $data['typeID']   = Types::BUTTON;
                        $icon             = strtolower($label);

                        break;

                    case 'weiterer Kontakt':

                        if (self::attributePrep($data, $map, $label, $oldID)) {
                            continue 2;
                        }

                        $data['label_en'] = 'Additional Contact';
                        $data['typeID']   = Types::ADDRESS;
                        $icon             = 'fa fa-map-pin';

                        break;

                    // Not yet supported.
                    default:
                        continue 2;
                }

                $data['icon']        = $oldAttribute->icon ?? $icon;
                $data['context']     = Attributes::PERSONS_CONTEXT;
                $data['viewLevelID'] = $oldAttribute->viewLevelID;
                $data['ordering']    = Attributes::getMaxOrdering('attributes');

                $new = new Tables\Attributes();
                $new->save($data);
                $map[$oldID] = $new->id;
            }
        }

        return $map;
    }

    /**
     * Migrates the THM Groups category => user associations to the new component table.
     * @return void
     */
    private static function categories(): void
    {
        $query = DB::query();
        $query->select(DB::qn(['id', 'profileID'], ['id', 'created_user_id']))
            ->from(DB::qn('#__thm_groups_categories'));
        DB::set($query);

        foreach (DB::arrays() as $result) {
            $table = new Tables\Categories();

            // Already there
            if ($table->load($result['id']) and $table->created_user_id === $result['created_user_id']) {
                continue;
            }

            $table->save($result);
        }
    }

    /**
     * Migrates the existing store of usergroups to groups.
     */
    private static function groups(): void
    {
        $query = DB::query();
        $query->insert(DB::qn('#__groups_groups'))
            ->columns([DB::qn('id'), DB::qn('name_de'), DB::qn('name_en')])
            ->values(":id, :name_de, :name_en")
            ->bind(':id', $groupID, ParameterType::INTEGER)
            ->bind(':name_de', $name)
            ->bind(':name_en', $name);

        // Make no exception for standard groups here, in order to have comparable output to the users component.
        foreach (UserGroupsHelper::getInstance()->getAll() as $groupID => $group) {
            $table = new Tables\Groups();

            // Already there
            if ($table->load($groupID)) {
                continue;
            }

            $name = $group->title;

            DB::set($query);
            DB::execute();
        }
    }

    /**
     * Migrates exiting data to the new tables.
     */
    public static function migrate(): void
    {
        //Integration::getTestResults();
        $session = Application::session();

        if (!$session->get('com_groups.migrated.settings')) {
            self::settings();
            $session->set('com_groups.migrated.settings', true);
        }

        if (!$session->get('com_groups.migrated.categories')) {
            self::categories();
            $session->set('com_groups.migrated.categories', true);
        }

        if (!$session->get('com_groups.migrated.groups')) {
            self::groups();
            $session->set('com_groups.migrated.groups', true);
        }

        if (!$session->get('com_groups.migrated.profiles')) {
            self::profiles();
            Integration::fillIDs();
            $session->set('com_groups.migrated.profiles', true);
        }

        if (!$session->get('com_groups.migrated.roles')) {
            $rMap = self::roles();
            self::roleAssociations($rMap);
            $session->set('com_groups.migrated.roles', true);
        }

        if (!$session->get('com_groups.migrated.attributes')) {
            $aMap = self::attributes();
            self::personAttributes($aMap);
            $tMap = self::templates();
            self::templateAttributes($aMap, $tMap);
            $session->set('com_groups.migrated.attributes', true);
        }

        // todo migrate menu assignments
        // todo migrate
    }

    /**
     * Migrates the person attribute mappings and values to the new table.
     *
     * @param   array  $map
     *
     * @return void
     */
    private static function personAttributes(array $map): void
    {
        $oldKeys = array_keys($map);
        $query   = DB::query();
        $query->select('*')
            ->from(DB::qn('#__thm_groups_profile_attributes'))
            ->whereIn(DB::qn('attributeID'), $oldKeys);
        DB::set($query);

        if (!$pas = DB::objects()) {
            return;
        }

        foreach ($pas as $pa) {
            $data = ['attributeID' => $map[$pa->attributeID], 'userID' => $pa->profileID,];

            $table = new Tables\ProfileAttributes();

            if ($table->load($data)) {
                continue;
            }

            /**
             * TODO add structured value migration here
             * Type URL
             * url => {text_de => url, text_en => url, url => url}
             * LSE / Kontakt => keep actual addresses here and format them
             */
            $data['value']     = $pa->value;
            $data['published'] = $pa->published;

            $table->save($data);
        }


        /**
         * TODO: Migrate these attributes after the fact
         * Kontakt => Anschrift, Büro, Zentren...
         * Quelle  Bild => source for image
         * Profile => everywhere
         * weiterer Kontakt => ???
         * Raum 2, weiterer Raum => add entry to office
         */
    }

    /**
     * Migrates the persons table.
     */
    private static function profiles(): void
    {
        $query = DB::query();
        $query->select('*')->from(DB::qn('#__thm_groups_profiles'));
        DB::set($query);

        // No existing data
        if (!$profiles = DB::objects('id')) {
            return;
        }

        $query = DB::query();
        $query->select([DB::qn('sn.value', 'surnames'), DB::qn('fn.value', 'forenames')])
            ->from(DB::qn('#__thm_groups_profile_attributes', 'sn'))
            ->join('left', DB::qn('#__thm_groups_profile_attributes', 'fn'), DB::qc('fn.profileID', 'sn.profileID'))
            ->where(DB::qcs([
                ['fn.attributeID', self::FORENAMES],
                ['sn.attributeID', self::SURNAMES],
                ['sn.profileID', ':profileID']
            ]))
            ->bind(':profileID', $profileID);

        foreach ($profiles as $profileID => $profile) {
            $user = new Tables\Users();

            if (!$user->load($profileID)) {
                continue;
            }

            $user->alias     = $profile->alias ?? null;
            $user->content   = $profile->contentEnabled ?? false;
            $user->editing   = $profile->canEdit ?? false;
            $user->published = $profile->published ?? false;

            DB::set($query);
            if ($names = DB::array()) {
                $user->surnames  = $names['surnames'];
                $user->forenames = $names['forenames'];
            }

            $user->store();
        }
    }

    /**
     * Migrates the role associations table.
     *
     * @param   array  $rMap  an array mapping the existing roles table to the new one
     *
     * @return void
     */
    private static function roleAssociations(array $rMap): void
    {
        $query = DB::query();
        $query->select([DB::qn('pa.profileID'), DB::qn('ra.groupID'), DB::qn('ra.roleID')])
            ->from(DB::qn('#__thm_groups_profile_associations', 'pa'))
            ->innerJoin(DB::qn('#__thm_groups_role_associations', 'ra'), DB::qc('ra.id', 'pa.role_associationID'))
            ->innerJoin(DB::qn('#__thm_groups_roles', 'r'), DB::qc('r.id', 'ra.roleID'))
            ->where(DB::qc('r.name', 'Mitglied', '!=', true));
        DB::set($query);

        // rMap has to be filled for this to return results
        if ($assocs = DB::objects()) {
            foreach ($assocs as $assoc) {
                // Mapping to standard groups is not valid
                if (in_array($assoc->groupID, Groups::STANDARD_GROUPS)) {
                    continue;
                }

                // Non-migrated roles (member) are not processed
                if (empty($rMap[$assoc->roleID])) {
                    continue;
                }

                $map  = new Tables\UserUsergroupMap();
                $data = ['group_id' => $assoc->groupID, 'user_id' => $assoc->profileID];

                if (!$map->load($data) or !$map->id) {
                    continue;
                }

                $table = new Tables\RoleAssociations();
                $data  = ['mapID' => $map->id, 'roleID' => $rMap[$assoc->roleID]];

                if ($table->load($data)) {
                    continue;
                }

                $table->save($data);
            }
        }
    }

    /**
     * Creates any role entries not included in the standard installation.+
     */
    private static function roles(): array
    {
        $map   = [];
        $query = DB::query();
        $query->select('*')->from(DB::qn('#__thm_groups_roles'));
        DB::set($query);

        // No existing data
        if (!$oldRoles = DB::objects()) {
            return $map;
        }

        // Create a prepared statement to find roles based on their name.
        $query = DB::query();
        $query->select(DB::qn('id'))
            ->from(DB::qn('#__groups_roles'))
            ->where(DB::qc('name_de', ':thmName', 'LIKE'))
            ->bind(':thmName', $name);

        $oldOrdering = [];

        foreach ($oldRoles as $oldRole) {
            $oldID   = $oldRole->id;
            $thmName = $oldRole->name;

            // Members are now inferred but explicitly referenced
            if ($thmName === 'Mitglied') {
                continue;
            }

            $oldOrdering[$oldRole->ordering] = $oldID;

            // Attempt name resolution
            $table = new Tables\Roles();

            // Exact match 50% of THM roles
            if ($table->load(['name_de' => $thmName])) {
                $map[$oldID] = $table->id;
                continue;
            }

            // Two known changes that wouldn't work with like.
            if ($thmName === 'Koordinatorin') {
                $map[$oldID] = 8;
                continue;
            }

            if ($thmName === 'ProfessorInnen') {
                $map[$oldID] = 9;
                continue;
            }

            //  German gender changes (+:in/:innen)
            $name = trim($thmName) . '%';
            DB::set($query);

            if ($groupsID = DB::integer()) {
                $map[$oldID] = $groupsID;
                continue;
            }

            // Non-standard/additional roles
            $migrant = [
                'name_de'   => $thmName,
                'name_en'   => $thmName,
                'plural_de' => $thmName,
                'plural_en' => $thmName,

                // Ordering has no default value, will be set correctly in the next portion of the function.
                'ordering'  => 0
            ];

            $table->save($migrant);
            $map[$oldID] = $table->id;
        }

        $roleIDs  = array_unique(array_values($map));
        $ordering = 1;
        ksort($oldOrdering);
        $oldOrdering = array_flip($oldOrdering);

        foreach (array_keys($oldOrdering) as $oldID) {
            $roleID = $map[$oldID];

            if (!$position = array_search($roleID, $roleIDs)) {
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

    /**
     * Migrates the thm groups component settings.
     *
     * @return void
     */
    private static function settings(): void
    {
        $query = DB::query();
        $query->select(DB::qn('params'))
            ->from(DB::qn('#__extensions'))
            ->where(DB::qc('element', 'com_thm_groups', '=', true));
        DB::set($query);

        if ($deprecated = DB::string()) {
            $deprecated = json_decode($deprecated, true);
            $params     = [
                'automatic-publishing' => Profiles::ENABLED,
                'module-context'       => !isset($deprecated['dynamicContext']) ? null : $deprecated['dynamicContext'],
                'profile-management'   => !isset($deprecated['editownprofile']) ?
                    Profiles::DECENTRALIZED : $deprecated['editownprofile'],
                'profile-content'      => !isset($deprecated['enabled']) ? Profiles::DISABLED : $deprecated['enabled'],
                'root-category'        => !isset($deprecated['rootCategory']) ? null : $deprecated['rootCategory'],
            ];

            $query = DB::query();
            $query->update(DB::qn('#__extensions'))
                ->set(DB::qc('params', json_encode($params), '=', true))
                ->where(DB::qc('element', 'com_groups', '=', true));
            DB::set($query);
            DB::execute();
        }
    }

    /**
     * Migrates the person attribute mappings and values to the new table.
     *
     * @param   array  $aMap  a map of old attribute ids to new attribute ids
     * @param   array  $tMap  a map of old template ids to new template ids
     *
     * @return void
     */
    private static function templateAttributes(array $aMap, array $tMap): void
    {
        $oldKeys = array_keys($aMap);
        $query   = DB::query();
        $query->select('*')
            ->from(DB::qn('#__thm_groups_template_attributes'))
            // only those attributes which have been migrated
            ->whereIn(DB::qn('attributeID'), $oldKeys)
            // only those associations which were actually used
            ->where(DB::qc('published', 1));
        DB::set($query);

        if (!$tas = DB::objects()) {
            return;
        }

        foreach ($tas as $ta) {
            $data = ['attributeID' => $aMap[$ta->attributeID], 'templateID' => $tMap[$ta->templateID]];

            $table = new Tables\TemplateAttributes();

            if ($table->load($data)) {
                continue;
            }

            $data['ordering']  = $ta->ordering;
            $data['showIcon']  = $ta->showIcon;
            $data['showLabel'] = $ta->showLabel;

            $table->save($data);
        }
    }

    /**
     * Migrates the old templates to the new table.
     * @return array a map of the old template ids to the new
     */
    private static function templates(): array
    {
        $map   = [];
        $query = DB::query();
        $query->select('*')->from(DB::qn('#__thm_groups_templates'));
        DB::set($query);

        // No existing data
        if (!$oldTemplates = DB::objects()) {
            return $map;
        }

        foreach ($oldTemplates as $old) {
            $new  = new Tables\Templates();
            $data = ['name_de' => $old->templateName];
            $new->load($data);

            if ($new->id) {
                $map[$old->id] = $new->id;
                continue;
            }

            $data['name_en'] = $old->templateName;
            $new->save($data);
            $map[$old->id] = $new->id;
        }

        return $map;
    }
}