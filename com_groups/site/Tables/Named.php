<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use THM\Groups\Adapters\Application;

trait Named
{
    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public string $name_de;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public string $name_en;

    /**
     * Gets the localized entry name.
     *
     * @param   int  $id
     *
     * @return string     *
     */
    public function getName(int $id): string
    {
        if (!$id) {
            return '';
        }

        if (!$this->load($id)) {
            return '';
        }

        if (Application::tag() === 'en') {
            return $this->name_en;
        }

        return $this->name_de;
    }
}