<?php
/**
 * @package     Groups
 * @extension   plg_user_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2021 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Plugin\User;

use Joomla\CMS\Event\{Model\AfterSaveEvent as modelASE, User\AfterSaveEvent as userASE};
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Utilities\ArrayHelper;
use THM\Groups\Controllers\Profile as Controller;
use THM\Groups\Helpers\{Groups as GH, Users as UH};
use THM\Groups\Tables\{Groups as GT, Users as UT};
use THM\Groups\Adapters\Application;

/**
 * Groups User Plugin
 */
final class Groups extends CMSPlugin implements SubscriberInterface
{
    /** @inheritDoc */
    public static function getSubscribedEvents(): array
    {
        return [
            'onUserAfterSave'      => 'supplementUser',
            'onUserAfterSaveGroup' => 'supplementGroup',
        ];
    }

    /**
     * Creates a groups group entry. The current language anchors the default name column.
     *
     * @param   modelASE  $event  the event triggered by the dispatcher
     *
     * @return void
     */
    public function supplementGroup(modelASE $event): void
    {
        $usergroups = $event->getItem();
        $groupID    = $usergroups->id;
        $title      = $usergroups->title;

        $group = new GT();

        if ($group->load($groupID)) {

            // Update the localization for the current language. Update the other language if no value is set.
            switch (Application::tag()) {
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

    /**
     * Method fills user related table information.
     *
     * @param   userASE  $event  the event triggered by the dispatcher
     *
     * @return void
     */
    public function supplementUser(userASE $event): void
    {
        $properties = $event->getUser();
        $user       = new UT();

        if (!$user->load($properties['id'])) {
            return;
        }

        $groupIDs  = ArrayHelper::toInteger($properties['groups']);
        $displayed = (bool) array_diff($groupIDs, GH::STANDARD_GROUPS);

        // The person is only associated with default groups and should therefore irrelevant to Groups
        if (!$displayed) {
            // If the person is no longer associated with a displayable group hide their profile.
            if ($user->published) {
                $user->published = UH::HIDDEN;
                $user->store();
            }

            return;
        }

        if (empty($user->surnames)) {
            Controller::create($user->id);
        }
    }
}
