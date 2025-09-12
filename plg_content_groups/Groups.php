<?php
/**
 * @package     Groups
 * @extension   plg_content_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2017 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Plugin\Content;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\{Event, SubscriberInterface};
use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Controllers\Page as Controller;
use THM\Groups\Helpers\{Categories, Pages};

/**
 * Groups content plugin
 */
class Groups extends CMSPlugin implements SubscriberInterface
{
    /** @inheritDoc */
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

        return Controller::page($article->id, $cUserID);
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
        [$context, $article] = array_values($event->getArguments());

        if (($context != 'com_content.form' and $context != 'com_content.article')) {
            return;
        }

        if (!$userID = Categories::userID($article->catid)) {
            Controller::unpage($article->id);
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

        [$context, $article] = array_values($event->getArguments());

        if ($context == 'com_finder.indexer') {
            return;
        }

        $sef = Application::configuration()->get('sef', Input::YES);

        Pages::replaceContentURLS($article->text);
        Pages::removeProfileParameters($article->text);

        if ($sef) {
            Pages::replaceGroupsQueries($article->text);
        }
    }
}
