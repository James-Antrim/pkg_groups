<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */


namespace THM\Groups\Views\HTML;

use THM\Groups\Adapters\{Application, Document, Input, Text, Toolbar};

trait Titled
{
    public string $subtitle = '';
    public string $supplement = '';
    public string $title = '';

    /**
     * Creates a subtitle element for the resource.
     * @return void
     */
    protected function subTitle(): void
    {
        // Overwritten as necessary.
    }

    /**
     * Adds supplemental information typically related to the context of the resource to its output.
     * @return void
     */
    protected function supplement(): void
    {
        // Overwritten as necessary.
    }

    /**
     * Prepares the title for standard HTML output. (Localizes)
     *
     * @param   string  $standard     the title to display
     * @param   string  $conditional  the conditional title to display
     *
     * @return void
     */
    protected function title(string $standard, string $conditional = ''): void
    {
        $params = Input::parameters();

        if ($params->get('show_page_heading')) {
            $title = $params->get('page_heading') ?: $params->get('page_title');
        }
        else {
            $title = empty($conditional) ? Text::_($standard) : Text::_($conditional);
        }

        // Joomla standard title/toolbar output property declared dynamically by Joomla => direct access creates inspection error.
        Toolbar::setTitle($title);

        // Internally implemented title & toolbar output for frontend use.
        $this->title = $title;

        Document::title(strip_tags($title) . ' - ' . Application::instance()->get('sitename'));
    }
}