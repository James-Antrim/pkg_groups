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

use Joomla\CMS\MVC\View\FormView as Core;
use Joomla\CMS\Uri\Uri;
use THM\Groups\Adapters\{Document, HTML, Input};
use THM\Groups\Views\Named;

/**
 * Class loads form data into the HTML view context.
 */
abstract class FormView extends Core
{
    use Attributed;
    use Configured;
    use Named;
    use Tasked;
    use Titled;

    protected string $baseURL = '';

    protected string $defaultTask = '';

    /** @inheritdoc */
    public $item;

    /**
     * The name of the layout to use during rendering.
     * @var string
     */
    protected string $layout = 'edit';

    public array $toDo = [];

    /**
     * Seems to be used somewhere to decide between Joomla Core UI (true) and bootstrap (false)
     * @var bool
     * @noinspection PhpUnused
     */
    public bool $useCoreUI = true;

    /** @inheritDoc */
    public function __construct(array $config)
    {
        // Joomla ignores the property value and overwrites it.
        if ($config['layout'] === 'default') {
            $config['layout'] = $this->layout;
        }
        else {
            $this->layout = $config['layout'];
        }

        $this->baseURL = $this->baseURL ?: Uri::base() . 'index.php?option=com_organizer';

        parent::__construct($config);

        $this->configure();
    }

    /**
     * Checks user authorization and initiates redirects accordingly. General access is now regulated through the
     * below-mentioned functions. Views with public access can be further restricted here as necessary.
     * @return void
     * @see Controller::display(), Can::view()
     */
    protected function authorize(): void
    {
        // See comment.
    }

    /** @inheritDoc */
    protected function addToolbar(array $buttons = [], string $constant = ''): void
    {
        Input::set('hidemainmenu', true);
        $buttons    = $buttons ?: ['apply', 'save'];
        $controller = $this->getName();
        $constant   = $constant ?: strtoupper($controller);

        $new = empty($this->item->id);

        $title = $new ? "ADD_$constant" : "EDIT_$constant";
        $this->title($title);

        $toolbar = Document::toolbar();

        if (count($buttons) > 1) {
            $saveGroup = $toolbar->dropdownButton('save-group');
            $saveBar   = $saveGroup->getChildToolbar();

            foreach ($buttons as $button) {
                switch ($button) {
                    case 'apply':
                        $saveBar->apply("$controller.apply");
                        break;
                    case 'save':
                        $saveBar->save("$controller.save");
                        break;
                    case 'save2copy':
                        if (!$new) {
                            $saveBar->save2copy("$controller.save2copy");
                        }
                        break;
                    case 'save2new':
                        $saveBar->save2new("$controller.save2new");
                        break;
                }
            }
        }
        else {
            $toolbar->save("$controller.save");
        }

        $toolbar->cancel("$controller.cancel");

        //TODO help!
    }

    /** @inheritDoc */
    public function display($tpl = null): void
    {
        $this->authorize();

        parent::display($tpl);
    }

    /** @inheritDoc */
    protected function initializeView(): void
    {
        parent::initializeView();
        $this->subTitle();
        $this->supplement();
        $this->modifyDocument();
    }

    /**
     * Adds scripts and stylesheets to the document.
     */
    protected function modifyDocument(): void
    {
        HTML::stylesheet(Uri::root() . 'components/com_groups/css/global.css');
    }
}