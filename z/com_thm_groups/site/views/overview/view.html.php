<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      Dennis Priefer, <dennis.priefer@mni.thm.de>
 * @author      Niklas Simonis, <niklas.simonis@mni.thm.de>
 * @author      Alexander Boll, <alexander.boll@mni.thm.de>
 * @author      Dieudonne Timma Meyatchie, <dieudonne.timma.meyatchie@mni.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

defined('_JEXEC') or die;
require_once HELPERS . 'router.php';

use THM\Groups\Helpers\{Groups, Profiles as Helper};
use THM\Groups\Views\HTML\Titled;

/**
 * Class provides an overview of group profiles.
 */
class THM_GroupsViewOverview extends JViewLegacy
{
    use Titled;

    public $columnCount;

    public $maxColumnSize;

    public $profiles = [];

    /**
     * Method to get display
     *
     * @param   Object  $tpl  template
     *
     * @return void
     * @throws Exception
     */
    public function display($tpl = null)
    {
        $app          = JFactory::getApplication();
        $this->params = $app->getParams();
        $input        = $app->input;

        $this->profiles = $this->getModel()->getProfiles();
        $groupID        = $this->params->get('groupID');
        if (empty($groupID)) {
            $totalProfiles = 0;
            foreach ($this->profiles as $profiles) {
                $totalProfiles += count($profiles);
            }

            if (empty($input->get('search'))) {
                $this->columnCount   = 3;
                $this->maxColumnSize = (int) ceil($totalProfiles / $this->columnCount) + $this->columnCount;
            }
            else {
                $this->columnCount   = 1;
                $this->maxColumnSize = $totalProfiles;
            }
        }
        else {
            $totalProfiles       = count(Groups::profileIDs($groupID));
            $this->columnCount   = (int) $this->params->get('columnCount', 3);
            $this->maxColumnSize = (int) ceil($totalProfiles / $this->columnCount) + $this->columnCount;
        }

        $this->modifyDocument();

        $input   = JFactory::getApplication()->input;
        $groupID = $this->params->get('groupID');

        // If there is a group ID the view was called from a menu item
        if ($groupID) {
            $title = Groups::name($groupID);
        }
        elseif (empty($input->get('search'))) {
            $title = 'OVERVIEW';
        }
        else {
            $title = 'DISAMBIGUATION';
        }

        $this->title($title);

        parent::display($tpl);
    }

    /**
     * Generates the header image if set in the menu settings.
     *
     * @return string the html of the header image
     */
    public function getHeaderImage()
    {
        if (!$this->params->get('groupID')) {
            return '';
        }

        $headerImage = '';
        if (!$this->params->get('jyaml_header_image_disable', false)
            and !empty($this->params->get('jyaml_header_image'))) {
            $path = $this->params->get('jyaml_header_image');

            $headerImage .= '<div class="headerimage" >';
            $headerImage .= '<img src="' . $path . '" class="contentheaderimage nothumb" alt = "" />';
            $headerImage .= '</div >';
        }

        return $headerImage;
    }

    /**
     * Creates a link to the profile view for the given profile
     *
     * @param   int  $profileID  the profile id
     *
     * @return  string  the HTML output for the profile link
     * @throws Exception
     */
    public function getProfileLink($profileID)
    {
        $url           = THM_GroupsHelperRouter::build(['view' => 'profile', 'profileID' => $profileID]);
        $showTitles    = (bool) $this->params->get('showTitles', 1);
        $displayedText = Helper::lnfName($profileID, $showTitles, true);

        return JHtml::link($url, $displayedText);
    }

    /**
     * Adds css and javascript files to the document
     *
     * @return  void  modifies the document
     */
    private function modifyDocument()
    {
        $document = JFactory::getDocument();
        $document->addStyleSheet('media/com_thm_groups/css/overview.css');
        JHtml::_('bootstrap.framework');
    }
}
