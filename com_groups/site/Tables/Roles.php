<?php /** @noinspection PhpMissingFieldTypeInspection */

/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseInterface;
use THM\Groups\Adapters\Application;

/**
 * Class representing the roles table.
 */
class Roles extends Table
{
    use Named, Ordered;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public string $plural_de;

    /**
     * VARCHAR(100) NOT NULL
     * @var string
     */
    public string $plural_en;

    /**
     * TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     * @bool
     */
    public int $protected;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct('#__groups_roles', 'id', $dbo);
    }

    /**
     * Gets the localized entry name.
     *
     * @param   int  $id
     *
     * @return string     *
     */
    public function getPlural(int $id): string
    {
        if (!$id) {
            return '';
        }

        if (!$this->load($id)) {
            return '';
        }

        if (Application::tag() === 'en') {
            return $this->plural_en;
        }

        return $this->plural_de;
    }
}