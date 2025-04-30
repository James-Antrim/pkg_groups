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

use THM\Groups\Views\Named;

/**
 * Class sets commonly configured view properties.
 */
trait Configured
{
    use Named;

    /**
     * Corrects basic configuration that is used by all HTML Views.
     */
    public function configure(): void
    {
        $this->_basePath = JPATH_SITE . '/components/com_groups';
        $this->_name     = $this->getName();

        // Set the default template search path
        $this->_setPath('template', $this->_basePath . '/templates');
    }
}