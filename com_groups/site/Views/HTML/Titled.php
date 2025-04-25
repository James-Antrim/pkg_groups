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
     * Creates a subtitle element from the term name and the start and end dates of the course.
     * @return void modifies the course
     */
    protected function setSubTitle(): void
    {
        // Overwritten as necessary.
    }

    /**
     * Adds supplemental information to the display output.
     * @return void modifies the object property supplement
     */
    protected function setSupplement(): void
    {
        // Overwritten as necessary.
    }

    /**
     * Prepares the title for standard HTML output.
     *
     * @param   string  $standard     the title to display
     * @param   string  $conditional  the conditional title to display
     *
     * @return void
     */
    protected function setTitle(string $standard, string $conditional = ''): void
    {
        $params = Input::getParams();

        if ($params->get('show_page_heading') and $params->get('page_title')) {
            $title = $params->get('page_title');
        }
        else {
            $title = empty($conditional) ? Text::_($standard) : Text::_($conditional);
        }

        // Joomla standard title/toolbar output property declared dynamically by Joomla => direct access creates inspection error.
        Toolbar::setTitle($title);

        // Internally implemented title & toolbar output for frontend use.
        $this->title = $title;

        Document::setTitle(strip_tags($title) . ' - ' . Application::instance()->get('sitename'));
    }
}