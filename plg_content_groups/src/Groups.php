<?php
/**
 * @package     Groups
 * @extension   plg_content_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2017 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Plugin\Content\Groups\Extension;

require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Table\Content as CoreTable;
use Joomla\Event\{Event, SubscriberInterface};
use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Controllers\Page as Controller;
use THM\Groups\Helpers\{Categories, Pages};

/**
 * Groups content plugin
 */
class Groups extends CMSPlugin implements SubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'onContentAfterSave'  => 'associate',
            'onContentBeforeSave' => 'disassociate',
            'onContentPrepare'    => 'replace'
        ];
    }

    /**
     * Ensures consistency for content saved in the context of com_thm_groups
     *
     * @param   Event  $event  the event to which the function is subscribed
     *
     * @return  bool
     */
    public function associate(Event $event): bool
    {
        /** @var CoreTable $article */
        [$context, $article] = array_values($event->getArguments());

        if (($context != 'com_content.form' and $context != 'com_content.article')) {
            return true;
        }

        // Irrelevant
        if (!$cUserID = Categories::userID($article->catid)) {
            return true;
        }

        if ($pUserID = Pages::userID($article->id) and $pUserID != $cUserID) {
            return true;
        }

        return Controller::associate($article->id, $cUserID);
    }

    /**
     * Ensures consistency for content saved in the context of com_thm_groups
     *
     * @param   Event  $event  the event to which the function is subscribed
     *
     * @return  void
     */
    public function disassociate(Event $event): void
    {
        /** @var CoreTable $article */
        [$context, $article] = array_values($event->getArguments());

        if (($context != 'com_content.form' and $context != 'com_content.article')) {
            return;
        }

        if (!$userID = Categories::userID($article->catid)) {
            Controller::disassociate($article->id);
        }

        $article->created_by = $userID;
    }

    /**
     * Removes Groups parameters from articles texts.
     *
     * @param   Event  $event  the event to which the function is subscribed
     *
     * @return  void
     */
    public function replace(Event $event): void
    {
        if (Application::backend()) {
            return;
        }

        /** @var CoreTable $article */
        [$context, $article] = array_values($event->getArguments());

        if ($context == 'com_finder.indexer') {
            return;
        }

        $sef = Application::configuration()->get('sef', Input::YES);

        THM_GroupsHelperRenderer::contentURLS($article->text);
        THM_GroupsHelperRenderer::modProfilesParams($article->text);

        if ($sef) {
            THM_GroupsHelperRenderer::groupsQueries($article->text);
        }
    }
}
