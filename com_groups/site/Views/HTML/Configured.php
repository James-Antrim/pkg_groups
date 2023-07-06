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

use THM\Groups\Adapters\Application;
use THM\Groups\Views\Named;

trait Configured
{
    use Named;

    /**
     * Whether the client accessed was administrative.
     * @var bool
     */
    public bool $backend;

    /**
     * Whether the client accessed was administrative.
     * @var bool
     */
    public bool $mobile;

    /**
     * Corrects basic configuration that is used by all HTML Views.
     */
    public function configure(): void
    {
        $this->_name = $this->getName();

        // Set the default template search path
        $this->_setPath('template', JPATH_SITE . '/components/com_groups/templates');

        $this->backend = Application::backend();
        $this->mobile  = Application::mobile();
    }


}