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

use Exception;
use Joomla\CMS\MVC\View\HtmlView;
use THM\Groups\Adapters\Application;

/**
 * View class for setting general context variables.
 */
abstract class BaseView extends HtmlView
{
    use Configured;

    protected string $layout;

    public bool $useCoreUI = true;

    /** @inheritDoc */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->configure();
    }

    /**
     * @inheritdoc
     * Does not dump the responsibility for exception handling onto inheriting classes.
     */
    public function display($tpl = null): void
    {
        try {
            parent::display($tpl);
        } catch (Exception $exception) {
            Application::handleException($exception);
        }
    }

    /** @inheritDoc */
    public function getLayout(): string
    {
        return $this->layout ?: strtolower($this->_name);
    }
}