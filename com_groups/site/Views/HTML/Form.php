<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Joomla\CMS\Form\Form as FormAlias;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Uri\Uri;
use THM\Groups\Adapters\{HTML, Input, Toolbar};
use THM\Groups\Views\Named;

/**
 * View class for handling lists of items.
 * - Overrides/-writes to avoid deprecated code in the platform or promote ease of use
 * - Supplemental functions to extract common code from list models
 */
abstract class Form extends BaseView
{
    use Configured;
    use Named;

    /**
     * The Form object
     * @var  FormAlias
     */
    protected $form;

    /**
     * Joomla hard coded default value.
     * @var string
     */
    protected string $layout = 'form';

    public array $todo = [];

    /**
     * Seems to be used somewhere to decide between Joomla Core UI (true) and bootstrap (false)
     * @var bool
     */
    public bool $useCoreUI = true;

    /**
     * Execute and display a template script. Should be sufficient in itself for most inheriting classes.
     *
     * @param   string  $tpl  unused
     *
     * @return  void
     */
    public function display($tpl = null): void
    {
        $this->state = $this->get('State');
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->addToolbar();

        HTML::stylesheet(Uri::root() . 'components/com_groups/css/global.css');

        parent::display($tpl);
    }

    /**
     * Adds resource related title, cancel/close and eventually help buttons.
     *
     * @param   string[]  $buttons  the names of the available button functions
     *
     * @return  void adds buttons to the global toolbar object
     */
    protected function addToolbar(array $buttons = []): void
    {
        Input::set('hidemainmenu', true);
        $buttons    = $buttons ?: ['save'];
        $controller = $this->getName();
        $constant   = strtoupper($controller);

        $new = empty($this->item->id);

        $title = $new ? "GROUPS_NEW_$constant" : "GROUPS_EDIT_$constant";
        ToolbarHelper::title(Text::_($title), '');

        $saveLabel = $new ? 'GROUPS_CREATE_AND_CLOSE' : 'GROUPS_SAVE_AND_CLOSE';
        $toolbar   = Toolbar::getInstance();

        if (count($buttons) > 1) {
            $saveGroup = $toolbar->dropdownButton('save-group');
            $saveBar   = $saveGroup->getChildToolbar();

            foreach ($buttons as $button) {
                switch ($button) {
                    case 'apply':
                        $applyLabel = $new ? 'GROUPS_CREATE' : 'GROUPS_APPLY';
                        $saveBar->apply("$controller.apply", $applyLabel);
                        break;
                    case 'save':
                        $saveBar->save("$controller.save", $saveLabel);
                        break;
                    case 'save2copy':
                        if (!$new) {
                            $saveBar->save2copy("$controller.save2copy", 'GROUPS_SAVE_AS_COPY');
                        }
                        break;
                    case 'save2new':
                        $newLabel = $new ? 'GROUPS_CREATE_AND_NEW' : 'GROUPS_SAVE_AND_NEW';
                        $saveBar->save2new("$controller.save2new", $newLabel);
                        break;
                }
            }
        }
        else {
            $toolbar->save("$controller.save", $saveLabel);
        }

        $closeLabel = $new ? 'GROUPS_CLOSE' : 'GROUPS_CANCEL';
        $toolbar->cancel("$controller.cancel", $closeLabel);

        //TODO help!
    }
}