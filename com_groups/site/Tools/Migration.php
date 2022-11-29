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
use THM\Groups\Helpers\Groups;
use THM\Groups\Helpers\Roles;
use THM\Groups\Tables;

/**
 * Has functions for migrating resources from the old structures.
 */
class Migration
{
    /**
     * Migrates the attributes table.
     *
     * @param array $atMap an array mapping the existing attribute types to the new ones
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
        $old = $db->loadObjectList('label');

        echo "<pre>old: " . print_r($old, true) . "</pre>";

        $query         = $db->getQuery(true);
        $oldAttributes = $db->quoteName('#__groups_attributes');
        $query->select('*')->from($oldAttributes);
        $db->setQuery($query);
        $new = $db->loadObjectList('label_de');

        $equivalences = [
            'Name'
        ];

        echo "<pre>---------------------------------------------------------------------------------------------</pre>";
        echo "<pre>---------------------------------------------------------------------------------------------</pre>";
        echo "<pre>new: " . print_r($new, true) . "</pre>";
        die;
        /*
         * ignore config: mode, path, regex (if it holds a constant), required
         *

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

        foreach (UserGroupsHelper::getInstance()->getAll() as $groupID => $group)
        {
            $table = new Tables\Groups($db);

            // Already there
            if ($table->load($groupID))
            {
                continue;
            }

            $id   = $groupID;
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
            die;
            $session->set('com_groups.migrate.roles', true);

        }
        die;

        if (!$session->get('com_groups.migrated.attributes'))
        {
            // Fax was added as an attribute type by someone who didn't understand the difference between attributes and types.
            //$atMap = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 12 => 7];
            //$aMap  = self::attributes($atMap);
            //$session->set('com_groups.migrated.attributes', true);
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

        $query = $db->getQuery(true);
        $query->select('*')->from($db->quoteName('#__thm_groups_profile_associations'));
        $db->setQuery($query);

        if ($oldAssocs = $db->loadObjectList())
        {
            foreach ($oldAssocs as $oldAssoc)
            {
                $assoc = ['assocID' => $oldAssoc->role_associationID, 'profileID' => $oldAssoc->profileID];
                $table = new Tables\ProfileAssociations($db);

                if (!$table->load($assoc))
                {
                    $table->save($assoc);
                }
            }
        }

        $uQuery = $db->getQuery(true);
        $uQuery->select('DISTINCT ' . $db->quoteName('user_id'))
            ->from($db->quoteName('#__user_usergroup_map'))
            ->where($db->quoteName('group_id') . ' = :groupID')
            ->bind(':groupID', $groupID, ParameterType::INTEGER);

        foreach (Groups::getIDs() as $groupID)
        {
            if (in_array($groupID, Groups::DEFAULT))
            {
                continue;
            }

            $db->setQuery($uQuery);

            if (!$userIDs = $db->loadColumn())
            {
                continue;
            }

            $rAssocTable = new Tables\RoleAssociations($db);

            if (!$assocID = $rAssocTable->getAssocID($groupID, Roles::MEMBER))
            {
                foreach ($userIDs as $profileID)
                {
                    $pAssoc      = ['assocID' => $assocID, 'profileID' => $profileID];
                    $pAssocTable = new Tables\ProfileAssociations($db);

                    if (!$pAssocTable->load($pAssoc))
                    {
                        $pAssocTable->save($pAssoc);
                    }
                }
            }
            else
            {
                $group = new Tables\Groups($db);
                $name  = $group->getName($groupID);
                Application::message("Group \"$name\" does not have a member association.", 'error');
            }
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

            $table = new Tables\Profiles($db);

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

                $table = new Tables\RoleAssociations($db);
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
            $table   = new Tables\Roles($db);
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