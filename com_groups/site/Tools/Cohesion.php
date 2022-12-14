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

use Exception;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Profiles;

class Cohesion
{
    /**
     * Supplements any profile entry with a blank alias Profile aliases are unique so there can only be one.
     *
     * @return void
     */
    private static function correctAliases()
    {
        $db    = Application::getDB();
        $alias = $db->quoteName('alias');

        $query = $db->getQuery(true);
        $query->select($db->quoteName('id'))
            ->from($db->quoteName('#__groups_profiles'))
            ->where("$alias = ''");
        $db->setQuery($query);

        if (!$incompleteIDs = $db->loadColumn())
        {
            return;
        }

        foreach ($incompleteIDs as $incompleteID)
        {
            self::createAlias($incompleteID);
        }
    }

    /**
     * Removes non-existent users from the user <-> usergroup map.
     *
     * @return void
     */
    private static function correctMap()
    {
        $db = Application::getDB();

        $mapID  = $db->quoteName('user_id');
        $userID = $db->quoteName('id');
        $users  = $db->quoteName('#__users');

        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__user_usergroup_map'))
            ->where("$mapID NOT IN (SELECT $userID FROM $users)");
        $db->setQuery($query);

        try
        {
            $db->execute();
        }
        catch (Exception $exception)
        {
            Application::message($exception->getMessage(), 'error');
        }
    }

    /**
     * Sets the profile alias based on the profile's fore- and surename attributes
     *
     * @param   int  $profileID  the id of the profile for which the alias is to be set
     *
     * @return bool true on success, otherwise false
     *
     * @throws Exception
     */
    public static function createAlias(int $profileID)
    {
        $names = Profiles::getNames($profileID);

        if (empty($names))
        {
            return false;
        }

        $alias = empty($names['forename']) ? $names['surname'] : "{$names['forename']}-{$names['surname']}";
        $alias = THM_GroupsHelperComponent::trim($alias);
        $alias = THM_GroupsHelperComponent::transliterate($alias);
        $alias = THM_GroupsHelperComponent::filterText($alias);
        $alias = str_replace(' ', '-', $alias);

        // Check for an existing alias which matches the base alias for the profile and react. (duplicate names)
        $initial = true;
        $number  = 1;
        while (true)
        {
            $tempAlias   = $initial ? $alias : "$alias-$number";
            $uniqueQuery = $dbo->getQuery(true);
            $uniqueQuery->select('id')
                ->from('#__thm_groups_profiles')
                ->where("alias = '$tempAlias'")
                ->where("id != $profileID");
            $dbo->setQuery($uniqueQuery);

            try
            {
                $existingID = $dbo->loadAssoc();
            }
            catch (Exception $exception)
            {
                JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

                return false;
            }

            if (empty($existingID))
            {
                $alias = $tempAlias;
                break;
            }
            else
            {
                $initial = false;
                $number++;
            }
        }

        $updateQuery = $dbo->getQuery(true);
        $updateQuery->update('#__thm_groups_profiles')->set("alias = '$alias'")->where("id = $profileID");
        $dbo->setQuery($updateQuery);

        try
        {
            $success = $dbo->execute();
        }
        catch (Exception $exception)
        {
            JFactory::getApplication()->enqueueMessage($exception->getMessage(), 'error');

            return false;
        }

        return empty($success) ? false : true;
    }

    public static function createBasicAttributes(int $userID)
    {

    }
}