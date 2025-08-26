<?php
/**
 * @package     Groups
 * @extension   plg_groups_system
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2019 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Plugin\System\Groups\Extension;

require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

use Joomla\CMS\{Application\CMSApplication, Uri\Uri};
use THM\Groups\Adapters\{Application, Input, Text};
use THM\Groups\Helpers\{Pages, Users};

class Redirector
{
    /**
     * Redirects the user according to the query.
     */
    public static function redirect($query): void
    {
        $msg     = '';
        $msgType = 'message';
        $url     = URI::root();

        if (Application::configuration()->get('sef', Input::YES)) {
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
            $lang = Application::language();
            $lang->load('com_groups');
            $msg     = Text::_('_409');
            $msgType = Application::NOTICE;
            $url     .= "&search={$query['search']}";
        }
    }
}