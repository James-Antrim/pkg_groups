<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

require_once 'content.php';

use Joomla\CMS\Uri\Uri;
use THM\Groups\Adapters\Database as DB;
use THM\Groups\Helpers\{Categories, Profiles, Users};
use THM\Groups\Adapters\Input;

/**
 * Class providing helper functions for batch select options
 */
class THM_GroupsHelperRouter
{
    /**
     * Method to build the displayed URL
     *
     * @param   array  $params    the parameters used to build internal links
     * @param   bool   $asString  true if the url should be functional, false if an array of segments
     *
     * @return mixed string if the URL should be complete, otherwise an array of terms to use in the URL
     * @throws Exception
     */
    public static function build($params, $asString = true)
    {
        $default = $asString ? '' : [];
        if (empty($params['view']) or empty($params['profileID'])) {
            return $default;
        }

        $params['profileAlias'] = Users::alias($params['profileID']);
        if (empty($params['profileAlias'])) {
            return $default;
        }
        elseif (empty($params['view'])) {
            $params['view'] = 'profile';
        }

        return !empty(JFactory::getConfig()->get('sef')) ?
            self::buildSEFURL($params, $asString) : self::buildRawURL($params, $asString);
    }

    /**
     * Method to build the displayed raw URL
     *
     * @param   array  $params    the parameters used to build internal links
     * @param   bool   $complete  true if the url should be functional, false if an array of segments
     *
     * @return mixed string if the URL should be complete, otherwise an array of terms to use in the URL
     * @throws Exception
     */
    private static function buildRawURL($params, $complete)
    {
        $invalidContent = (empty($params['id']) or empty(THM_GroupsHelperContent::getAlias($params['id'])));
        if ($params['view'] === 'content' and $invalidContent) {
            $params['view'] = 'profile';
        }

        $query = ['option' => 'com_thm_groups', 'view' => $params['view'], 'profileID' => $params['profileID']];
        if ($query['view'] === 'content') {
            $query['id'] = $params['id'];
        }
        elseif ($query['view'] === 'profile' and !empty($params['format'])) {
            $query['format'] = $params['format'];
        }

        return $complete ? URI::base() . '?' . http_build_query($query) : $query;
    }

    /**
     * Method to build the displayed SEF URL
     *
     * @param   array  $params    the parameters used to build internal links
     * @param   bool   $complete  true if the url should be functional, false if an array of segments
     *
     * @return mixed string if the URL should be complete, otherwise an array of terms to use in the URL
     * @throws Exception
     */
    private static function buildSEFURL($params, $complete)
    {
        $return   = [];
        $return[] = $params['profileAlias'];
        switch ($params['view']) {
            case 'content':
                if (!empty($params['id'])) {
                    $return[] = THM_GroupsHelperContent::getAlias($params['id']);
                }
                break;
            case 'content_manager':
                $return[] = JText::_('COM_THM_GROUPS_CONTENT_MANAGER_ALIAS');
                break;
            case 'profile':
                if (!empty($params['format'])) {
                    $return[] = $params['format'];
                }
                break;
            case 'profile_edit':
                $return[] = JText::_('COM_THM_GROUPS_EDIT_ALIAS');
                break;
        }

        if (!$complete) {
            return $return;
        }

        return URI::base() . implode('/', $return);
    }

    /**
     * Attempts to resolve a URL path to a menu item
     *
     * @param   string  $possibleMenuPath  the path string to check against
     *
     * @return array the id, title and url of the menu item on success, otherwise empty
     */
    public static function getMenuByPath(string $possibleMenuPath): array
    {
        $dbo   = JFactory::getDbo();
        $query = $dbo->getQuery(true);

        $query->select('id, title')->select($query->concatenate(["'" . Uri::base() . "'", 'path']) . ' AS URL')
            ->from('#__menu')
            ->where("path = '$possibleMenuPath'")->where("link LIKE '%option=com_thm_groups%'");
        $dbo->setQuery($query);

        $menu = DB::array();

        return empty($menu) ? [] : $menu;
    }

    /**
     * Retrieves the path items free of masked subdomains, query items, extension coding, the word index, and empty items.
     *
     * @param   string  $url  the URL to be parsed into path items.
     *
     * @return array the relevant items in the url path
     */
    public static function getPathItems($url)
    {
        $rawPath = str_replace(URI::base(), '', $url);

        // The URL is external and therefore irrelevant
        if ($rawPath === $url) {
            return [];
        }

        // Attempt to resolve using modern rules
        $queryFreePath     = preg_replace('/\?.+/', '', $rawPath);
        $extensionFreePath = str_replace(['.html', '.htm', '.php'], '', $queryFreePath);
        $indexFreePath     = preg_replace('/\/?index/', '', $extensionFreePath);
        $rawPathItems      = explode('/', $indexFreePath);

        return is_array($rawPathItems) ? array_filter($rawPathItems) : [];
    }

    /**
     * Sets the path for dynamic groups content
     *
     * @return void sets the pathway items
     * @throws Exception
     */
    public static function setPathway()
    {
        $app       = JFactory::getApplication();
        $profileID = Input::integer('profileID');

        if (empty($profileID)) {
            return;
        }

        // Get the pathway and empty and default items from Joomla

        $contentID   = Input::id();
        $pathway     = $app->getPathway();
        $profileName = Profiles::name($profileID);
        $profileURL  = THM_GroupsHelperRouter::build(['view' => 'profile', 'profileID' => $profileID]);
        $session     = JFactory::getSession();

        $pathway->setPathway([]);

        if (empty($contentID)) {

            $profileAlias = Users::alias($profileID);
            $pathItems    = self::getPathItems(Input::referrer());

            // Redirect back from a profile dependent item.
            if (in_array($profileAlias, $pathItems)) {
                $referrerName = $session->get('referrerName', '', 'thm_groups');
                $referrerURL  = $session->get('referrerUrl', '', 'thm_groups');
                if (empty($referrerName) or empty($referrerURL)) {
                    $pathway->addItem(JText::_('COM_THM_GROUPS_HOME'), URI::base());
                }
                else {
                    $pathway->addItem($referrerName, $referrerURL);
                }
            }
            else {
                $possibleMenuPath = implode('/', $pathItems);
                $menu             = self::getMenuByPath($possibleMenuPath);
                if (empty($menu)) {
                    $pathway->addItem(JText::_('COM_THM_GROUPS_HOME'), URI::base());
                }
                else {
                    $session->set('referrerName', $menu['title'], 'thm_groups');
                    $session->set('referrerUrl', $menu['URL'], 'thm_groups');
                    $pathway->addItem($menu['title'], $menu['URL']);
                }
            }

            $pathway->addItem($profileName, $profileURL);
        }
        else {

            $referrerName = $session->get('referrerName', '', 'thm_groups');
            $referrerURL  = $session->get('referrerUrl', '', 'thm_groups');
            if (empty($referrerName) or empty($referrerURL)) {
                $pathway->addItem(JText::_('COM_THM_GROUPS_HOME'), URI::base());
            }
            else {
                $pathway->addItem($referrerName, $referrerURL);
            }

            $pathway->addItem($profileName, $profileURL);

            $contentTitle  = THM_GroupsHelperContent::getTitle($contentID);
            $contentParams = ['view' => 'content', 'profileID' => $profileID, 'id' => $contentID];
            $contentURL    = THM_GroupsHelperRouter::build($contentParams);
            $pathway->addItem($contentTitle, $contentURL);
        }
    }

    /**
     * Translates content parameters into Groups parameters as relevant.
     *
     * @param $query
     *
     * @return bool
     * @throws Exception
     */
    public static function translateContent(&$query)
    {
        $relevantViews = ['article', 'category'];
        if (!empty($query['view']) and !in_array($query['view'], $relevantViews)) {
            return false;
        }

        if (empty($query['view']) or $query['view'] === 'article') {
            if (!empty($query['catid'])) {
                if (is_numeric($query['catid'])) {
                    $profileID = THM_GroupsHelperContent::resolve($query['catid']);
                }
                elseif (preg_match('/^(\d+)/', $query['catid'], $matches)) {
                    // true for root, false for irrelevant, otherwise profileID
                    $profileID = Categories::resolve($matches[0]);
                }
            }
            if (!empty($query['a_id']) or !empty($query['id'])) {
                $tmpID = empty($query['a_id']) ? $query['id'] : $query['a_id'];
                if (is_numeric($tmpID)) {
                    $contentID = THM_GroupsHelperContent::resolve($tmpID);
                }
                elseif (preg_match('/^(\d+)/', $tmpID, $matches)) {
                    //0 or contentID
                    $contentID = THM_GroupsHelperContent::resolve($matches[0]);
                }
            }

            if (empty($profileID) and empty($contentID)) {
                return false;
            }
            elseif ($contentID) {
                $query['id']        = $contentID;
                $query['profileID'] = THM_GroupsHelperContent::getProfileID($contentID);
                $query['view']      = 'content';
                unset($query['catid']);
            }
            elseif ($profileID and $profileID === true) {
                $query['search'] = '';
                $query['view']   = 'overview';
                unset($query['id']);
            }
            else {
                $query['profileID'] = $profileID;
                $query['view']      = 'profile';
                unset($query['id']);
            }
            $query['option'] = 'com_thm_groups';
            unset($query['a_id'], $query['catid'], $query['lang'], $query['layout']);

            return true;
        }

        if (!empty($query['id'])) {
            if (is_numeric($query['id'])) {
                $profileID = Categories::resolve($query['id']);
            }
            elseif (preg_match('/^(\d+)/', $query['id'], $matches)) {
                // true for root, false for irrelevant, otherwise profileID
                $profileID = Categories::userID($matches[0]);
            }
        }


        if (empty($profileID)) {
            return false;
        }
        elseif ($profileID and $profileID === true) {
            $query['search'] = '';
            $query['view']   = 'overview';
        }
        else {
            $query['profileID'] = $profileID;
            $query['view']      = 'profile';
        }
        $query['option'] = 'com_thm_groups';
        unset($query['a_id'], $query['id'], $query['lang'], $query['layout']);

        return true;
    }
}
