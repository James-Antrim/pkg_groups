<?php
/**
 * @package     Groups
 * @extension   plg_groups_system
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Plugin\System\Groups\Extension;

require_once JPATH_ADMINISTRATOR . '/components/com_groups/services/autoloader.php';

use Joomla\CMS\{Plugin\CMSPlugin, Router\Router, Session\Session, Uri\Uri};
use Joomla\Event\SubscriberInterface;
use THM\Groups\Adapters\{Application, Database as DB, HTML, Input, Text, User};
use THM\Groups\Helpers\{Groups as GH, Pages, Profiles, Users};

require_once JPATH_ROOT . '/media/com_thm_groups/helpers/profiles.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/renderer.php';
require_once JPATH_ROOT . '/media/com_thm_groups/helpers/router.php';
require_once 'GroupsParser.php';
require_once 'GroupsRedirector.php';
require_once 'GroupsValidator.php';

/**
 * Class tries to resolve teacher stub calls from thm organizer to thm groups profiles.
 */
class Groups extends CMSPlugin implements SubscriberInterface
{
    /** @inheritDoc */
    public static function getSubscribedEvents(): array
    {
        return [
            'onAfterInitialise'   => 'resolve',
            'onContentBeforeSave' => 'disassociate',
            'onContentPrepare'    => 'replace'
        ];
    }

    /**
     * Determines whether the given path is a menu alias.
     *
     * @param   string  $alias  the potential menu alias.
     *
     * @return int int the id of the menu item or 0
     */
    private function isMenu(string $alias): int
    {
        $query = DB::query()->select('*')->from(DB::qn('#__menu'))->where(DB::qc('path', $alias));
        DB::set($query);

        if (!$row = DB::array()) {
            return 0;
        }

        if ($row['link'] !== 'index.php?option=com_groups&view=profile') {
            return $row['id'];
        }

        $this->loadLanguage();
        $referrer = Input::referrer();
        $user     = User::instance();

        if (!$userID = $user->id) {
            Application::message('401', 'error');
            Application::redirect($referrer, 401);
        }

        if (!array_diff(array_keys($user->groups), GH::STANDARD_GROUPS)) {
            Application::message('404', 'error');
            Application::redirect($referrer, 404);
        }

        $items = ['option' => 'com_thm_groups', 'view' => 'profile', 'profileID' => $userID];
        self::redirect($items);

        return 0;
    }

    /**
     * Attempts to log in the user using the nested form data from the login module.
     *
     * @return void
     * @noinspection PhpUnused
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private static function login(): void
    {
        $password   = Input::string('password');
        $referrer   = Input::referrer();
        $username   = Input::string('username');
        $validToken = (Session::checkToken() or Session::checkToken('get'));

        // Error messages due to false credentials are handled in the login function
        if ($validToken and $username and $password) {
            Application::login(['username' => $username, 'password' => $password]);
        }
        elseif (!$validToken) {
            Application::message('JINVALID_TOKEN_NOTICE', Application::WARNING);
        }

        Application::redirect($referrer);
    }

    /**
     * Attempts to log in the user using the nested form data from the login module.
     *
     * @return void
     * @noinspection PhpUnused
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    public static function logout(): void
    {
        Application::logout();
        Application::redirect(Input::referrer());
    }

    /**
     * Checks whether profile users were directly addressed in the url
     *
     * @return void
     */
    public function resolve(): void
    {
        if (Application::backend()) {
            return;
        }

        $uri    = Uri::getInstance();
        $pItems = THM_GroupsHelperRouter::getPathItems($uri::current());
        $qItems = $uri->getQuery(true);

        // Language switch performed on a menu item
        $onlyLanguage = (count($qItems) === 1 and !empty($qItems['lang']));
        $path         = implode('/', $pItems);
        if ($this->isMenu($path) and (empty($qItems) or $onlyLanguage)) {
            return;
        }

        // Link to site
        if (empty($pItems) and empty($qItems['option'])) {
            $qItems['option'] = 'com_content';
        }

        $relevantTasks = ['user.login' => 'login', 'user.logout' => 'logout'];
        $task          = Input::task();
        if ($task and !array_key_exists($task, $relevantTasks)) {
            return;
        }
        elseif ($task) {
            $wrapperFunction = $relevantTasks[$task];
            self::$wrapperFunction();
        }

        $relevant = ['content', 'groups'];

        if ($qItems and !empty($qItems['option']) and $qItems['option'] === 'com_content') {
            // Relevant content
            if (THM_GroupsHelperRouter::translateContent($qItems)) {
                GroupsRedirector::redirect($qItems);
            }
            else {
                return;
            }
        }

        $sef = Application::configuration()->get('sef', Input::YES);

        if ($sef and $pItems and in_array('component', $pItems) and !array_intersect($relevant, $pItems)) {
            return;
        }

        $validQuery = GroupsValidator::validate($qItems);
        if ($validQuery === false) {
            // The query is flagrantly invalid.
            return;
        }
        elseif ($validQuery) {
            if ($sef) {
                // The query is valid but the formatting requires a redirect.
                GroupsRedirector::redirect($qItems);
            }

            // The query is valid and SEF is inactive.
            self::route($qItems);

            return;
        }

        $query     = GroupsParser::groupsSEF($pItems);
        $validPath = GroupsValidator::validate($query);

        // No parameters should be lost.
        $query = array_merge($query, $qItems);
        if ($validPath === false) {
            // The path is flagrantly invalid.
            return;
        }
        elseif ($validPath) {
            if (!$sef) {
                // The query is valid but the formatting requires a redirect. Temporary redirect because we want SEF.
                GroupsRedirector::redirect($query);
            }

            // The SEF URL is valid and SEF is active.
            switch ($query['view']) {
                case 'content':
                case 'content_manager':
                case 'profile':
                case 'profile_edit':
                    $profileAlias = Users::alias($query['profileID']);
                    if (!in_array($profileAlias, $pItems)) {
                        GroupsRedirector::redirect($query);
                    }
                    break;
                case 'overview':
                    $disambiguation = Text::_('DISAMBIGUATION_ALIAS');
                    $translated     = in_array($disambiguation, $pItems);

                    // No unfiltered listing right now
                    //$overview       = Text::_('OVERVIEW_ALIAS');
                    //$translated = ($translated or in_array($overview, $pItems));
                    if (!$translated) {
                        GroupsRedirector::redirect($query);
                    }
                    break;
            }

            self::route($query);

            return;
        }

        // SEF and query taken together are valid.
        if (GroupsValidator::validate($query)) {
            GroupsRedirector::redirect($query);
        }

        $query           = GroupsParser::groupsLegacySEF($pItems);
        $validLegacyPath = GroupsValidator::validate($query);
        if ($validLegacyPath === false) {
            return;
        }
        elseif ($validLegacyPath) {
            $query = array_merge($query, $qItems);
            GroupsRedirector::redirect($query);
        }

        // Attempt to resolve for standard joomla sef construction of old links
        $query           = GroupsParser::groupsJoomla($pItems);
        $validJoomlaPath = GroupsValidator::validate($query);
        if ($validJoomlaPath === false) {
            return;
        }
        elseif ($validJoomlaPath) {
            $query = array_merge($query, $qItems);
            GroupsRedirector::redirect($query);
        }

        if (count($pItems) >= 2) {
            $query             = GroupsParser::category(end($pItems));
            $validCategoryPath = GroupsValidator::validate($query);
            if ($validCategoryPath) {
                $query = array_merge($query, $qItems);
                GroupsRedirector::redirect($query);
            }
        }
    }

    /**
     * Replaces user stubs with the corresponding configured output
     *
     * @return void
     */
    public function onAfterRender(): void
    {
        if (Application::backend()) {
            return;
        }

        $output = Application::body();
        $this->replaceStubs($output);

        THM_GroupsHelperRenderer::contentURLS($output);

        if (Input::view() !== 'form') {
            THM_GroupsHelperRenderer::modProfilesParams($output);
        }

        if (Application::configuration()->get('sef', Input::YES)) {
            THM_GroupsHelperRenderer::groupsQueries($output);
        }

        Application::body($output);
    }

    /**
     * Add parse rule to router.
     *
     * @param   Router  $router  JRouter object.
     * @param   Uri     $uri     JUri object.
     *
     * @return array
     * @noinspection PhpUnusedParameterInspection
     */
    public function parse(Router $router, Uri $uri): array
    {
        $return = ['option' => 'com_thm_groups', 'view' => Input::view()];

        if ($id = Input::id()) {
            $return['id'] = $id;
        }

        if ($format = Input::format()) {
            $return['format'] = $format;
        }

        if ($profileID = Input::integer('profileID')) {
            $return['profileID'] = $profileID;
        }

        if ($search = Input::string('search')) {
            $return['search'] = $search;
        }

        return $return;
    }

    /**
     * Redirects the user according to the query.
     *
     * @param   array  $query
     *
     * @return void
     */
    private static function redirect(array $query): void
    {
        $lang = Application::language();
        $lang->load('com_groups');

        $msg     = '';
        $msgType = 'message';
        $url     = URI::root();

        if (Application::configuration()->get('sef', Input::YES)) {
            $code         = 308;
            $pathParts    = [];
            $profileAlias = empty($query['profileID']) ? '' : Users::alias($query['profileID']);

            switch ($query['view']) {
                case 'page':
                    $pathParts[] = $profileAlias;
                    $pathParts[] = Pages::alias($query['id']);
                    break;
                case 'pages':
                    $pathParts[] = $profileAlias;
                    $pathParts[] = Text::_('PAGES_ALIAS');
                    break;
                case 'profile':
                    $pathParts[] = $profileAlias;
                    if (!empty($query['layout']) and $query['layout'] == 'edit') {
                        $pathParts[] = Text::_('EDIT_ALIAS');
                    }
                    elseif (!empty($query['format'])) {
                        $pathParts[] = $query['format'];
                        unset($query['format']);
                    }
                    break;
                case 'overview':
                    // The given names did not deliver a distinct result
                    if (!empty($query['search'])) {
                        $code        = 409;
                        $msg         = Text::_('409');
                        $msgType     = Application::NOTICE;
                        $pathParts[] = Text::_('DISAMBIGUATION_ALIAS');
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
        else {
            $code = 301;
            $url  .= '?';
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

        http_response_code($code);

        if ($msg) {
            Application::message($msg, $msgType);
        }

        Application::redirect($url, $code);
    }

    /**
     * Replaces organizer stubs referencing persons via the username with profile links
     *
     * @param   string &$output  the output used for the application
     *
     * @return void modifies the output
     */
    private function replaceStubs(string &$output): void
    {
        preg_match_all("/\<span.*id=\"(.*)\".*class=\"thm-groups-stub\"\>(.*)<\/span\>/", $output, $matches);

        if (empty($matches[0])) {
            return;
        }

        $spans     = $matches[0];
        $userNames = $matches[1];
        $names     = $matches[2];

        $query = DB::query()
            ->select(DB::qn('id'))
            ->from(DB::qn('#__users', 'users'))
            ->where(DB::qc('username', ':username'))
            ->bind(':username', $userName);

        $profileIDs = [];
        foreach ($userNames as $key => $userName) {
            DB::set($query);

            if ($userID = DB::integer()) {
                $profileIDs[$key] = $userID;
            }
        }

        foreach ($profileIDs as $key => $profileID) {

            // User could not be resolved to a profile use the given name.
            if (empty($profileID)) {
                $output = str_replace($spans[$key], $names[$key], $output);

                continue;
            }

            $displayName = Profiles::name($profileID, true);

            // No Groups surname use the Organizer name.
            if (empty($displayName)) {
                $output = str_replace($spans[$key], $names[$key], $output);

                continue;
            }

            $url    = THM_GroupsHelperRouter::build(['view' => 'profile', 'profileID' => $profileID]);
            $link   = HTML::link($url, $displayName, ['target' => '_blank']);
            $output = str_replace($spans[$key], $link, $output);
        }

    }

    /**
     * Prepares the input object to route correctly.
     *
     * @param   array  $parameters
     *
     * @return void
     */
    private function route(array $parameters): void
    {
        Input::set('option', 'com_groups');
        if ($defaultMenuID = Input::parameters()->get('dynamicContext')) {
            Input::set('Itemid', $defaultMenuID);
        }

        foreach ($parameters as $key => $value) {
            Input::set($key, $value);
        }

        Application::router()->attachParseRule([$this, 'parse'], Router::PROCESS_BEFORE);
    }
}