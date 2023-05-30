<?php
/**
 * @package     Mapped LDAP
 * @extension   plg_mapped_ldap
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2021 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Table\Usergroup;
use Joomla\Utilities\ArrayHelper;
use THM\Groups\Helpers\{Groups as GH, Users as UH};
use THM\Groups\Tables\{Groups as GT, Users as UT};
use THM\Groups\Adapters\Application;
use THM\Groups\Tools\Cohesion;

/**
 * Groups User Plugin
 */
class PlgUserGroups extends CMSPlugin
{
    private const NO = false;

    /**
     * Method fills user related table information.
     *
     * @param array $properties the user table properties
     *
     * @return void
     * @throws Exception
     */
    public function onUserAfterSave(array $properties): void
    {
        $accountID = $properties['id'];
        $user      = new UT();

        if (!$user->load($accountID)) {
            return;
        }

        $groupIDs  = ArrayHelper::toInteger($properties['groups']);
        $displayed = (bool) array_diff($groupIDs, GH::DEFAULT);

        // The person is only associated with default groups and should therefore irrelevant to THM Groups
        if (!$displayed) {

            // Since role associations fk the map table there is no longer any need to delete them.

            // If the person is no longer associated with a displayable group unpublish their profile.
            if ($user->published) {
                $user->published = self::NO;
                $user->store();
            }

            return;
        }

        $updated = false;

        if (empty($user->surnames)) {
            list($surnames, $forenames) = Cohesion::parseNames($user->name);

            $user->forenames = $forenames;
            $user->surnames  = $surnames;
            $updated         = true;
        }

        if (empty($user->alias) or $updated) {
            $user->alias = UH::createAlias($user->id, "$user->forenames $user->surnames");
            $updated     = true;
        }

        if ($updated) {
            $user->store();
        }
    }

    /**
     * Creates a groups group entry. The current language anchors the default name column.
     *
     * @param string $context com_users.group
     * @param Usergroup $usergroups the group table object
     * @param bool $isNew whether the group is new to the instance
     * @param array $data the form data
     *
     * @return void
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUndefinedFieldInspection
     */
    public function onUserAfterSaveGroup(string $context, Usergroup $usergroups, bool $isNew, array $data): void
    {
        $groupID = $usergroups->id;
        $title   = $usergroups->title;

        $group = new GT();

        if ($group->load($groupID)) {

            // Update the localization for the current language. Update the other language if no value is set.
            switch (Application::getTag()) {
                case 'en':
                    if ($group->name_en !== $title) {
                        $group->name_en = $title;
                        $group->name_de = $group->name_de ?: $title;
                        $group->store();
                    }
                    break;
                case 'de':
                default:
                    if ($group->name_de !== $title) {
                        $group->name_de = $title;
                        $group->name_en = $group->name_en ?: $title;
                        $group->store();
                    }
                    break;
            }
            return;
        }

        $data = ['id' => $groupID, 'name_de' => $title, 'name_en' => $title];
        $group->save($data);
    }
}
