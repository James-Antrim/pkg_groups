<?php
/**
 * @category    Joomla plugin
 * @package     THM_Groups
 * @subpackage  plg_thm_groups_system.site
 * @name        GroupsRedirector
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2019 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

defined('_JEXEC') or die;

use Joomla\CMS\{Application\CMSApplication, Language\Text, Uri\Uri};
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\{Pages, Users};

class GroupsRedirector
{
    /**
     * Redirects the user according to the query.
     *
     * @throws Exception
     */
    public static function redirect($query): void
    {
        $msg     = '';
        $msgType = 'message';
        $url     = URI::root();

        if (JFactory::getConfig()->get('sef', 1)) {
            $code = 308;
            self::redirectSEF($query, $code, $msg, $msgType, $url);
        }
        else {
            $code = 301;
            $url  .= '?';
            self::redirectRaw($query, $code, $msg, $msgType, $url);
        }

        http_response_code($code);

        /** @var CMSApplication $app */
        $app = JFactory::getApplication();

        if ($msg) {
            $app->enqueueMessage($msg, $msgType);
        }

        Application::redirect($url, $code);
    }

    /**
     * Sets parameters used for a raw redirect URL
     *
     * @param   array    $query    the parameters used for building the URL
     * @param   int     &$code     the http status code to use on redirection
     * @param   string  &$msg      the message to display on redirection
     * @param   string  &$msgType  the message style to use for displaying the message
     * @param   string  &$url      the url to redirect to
     *
     * @return void modifies the parameters code, msg, msgType and url
     */
    private static function redirectRaw(array $query, int &$code, string &$msg, string &$msgType, string &$url): void
    {
        unset($query['lang']);
        ksort($query);
        $url .= http_build_query($query);
        if (!empty($query['search'])) {
            $code = 409;
            $lang = JFactory::getLanguage();
            $lang->load('com_thm_groups');
            $msg     = Text::_('COM_THM_GROUPS_ERROR_409');
            $msgType = 'notice';
            $url     .= "&search={$query['search']}";
        }
    }

    /**
     * Sets parameters used for a SEF redirect URL
     *
     * @param   array    $query    the parameters used for building the URL
     * @param   int     &$code     the http status code to use on redirection
     * @param   string  &$msg      the message to display on redirection
     * @param   string  &$msgType  the message style to use for displaying the message
     * @param   string  &$url      the url to redirect to
     *
     * @return void modifies the parameters code, msg, msgType and url
     * @throws Exception
     */
    private static function redirectSEF(array $query, int &$code, string &$msg, string &$msgType, string &$url): void
    {
        $lang = JFactory::getLanguage();
        $lang->load('com_thm_groups');
        $pathParts    = [];
        $profileAlias = empty($query['profileID']) ? '' : Users::alias($query['profileID']);

        switch ($query['view']) {
            case 'content':
                $pathParts[] = $profileAlias;
                $pathParts[] = Pages::alias($query['id']);
                break;
            case 'content_manager':
                $pathParts[] = $profileAlias;
                $pathParts[] = Text::_('COM_THM_GROUPS_CONTENT_MANAGER_ALIAS');
                break;
            case 'profile':
                $pathParts[] = $profileAlias;
                if (!empty($query['format'])) {
                    $pathParts[] = $query['format'];
                    unset($query['format']);
                }
                break;
            case 'profile_edit':
                $pathParts[] = $profileAlias;
                $pathParts[] = Text::_('COM_THM_GROUPS_EDIT_ALIAS');
                break;
            case 'overview':
                $lang->load('com_thm_groups');
                if (!empty($query['search'])) {
                    // The given names did not deliver a distinct result
                    $code        = 409;
                    $msg         = Text::_('COM_THM_GROUPS_ERROR_409');
                    $msgType     = 'notice';
                    $pathParts[] = Text::_('COM_THM_GROUPS_DISAMBIGUATION_ALIAS');
                    $pathParts[] = $query['search'];
                }
                break;
        }

        $url .= implode('/', $pathParts);

        unset($query['id'], $query['lang'], $query['option'], $query['profileID'], $query['search'], $query['view']);

        if (count($query)) {
            ksort($query);
            $url .= '?' . http_build_query($query);
        }
    }
}