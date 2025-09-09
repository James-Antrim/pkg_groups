<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Joomla\CMS\Table\Content as Core;
use Joomla\Database\{DatabaseDriver, DatabaseInterface};
use THM\Groups\Adapters\Application;

/**
 * Class representing the content <=> person relations.
 */
class Content extends Core
{
    use Ordered;
    use Resettable;

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0'
     * @var int
     * @UNDOCUMENTED
     */
    public int $access = 0;

    /**
     * VARCHAR(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT ''
     * @var string
     * @UNDOCUMENTED
     */
    public string $alias = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.'
     * @var int
     * @UNDOCUMENTED
     */
    public int $asset_id = 0;

    /**
     * VARCHAR(5120) NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $attribs = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     * @UNDOCUMENTED
     */
    public int $catid = 0;

    /**
     * INT(10) UNSIGNED DEFAULT NULL
     * @var int|null
     */
    public int|null $checked_out = null;

    /**
     * DATETIME DEFAULT NULL
     * @var string|null
     * @UNDOCUMENTED
     */
    public string|null $checked_out_time = null;

    /**
     * DATETIME NOT NULL
     * @var string|null
     * @UNDOCUMENTED
     */
    public string|null $created;

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int|null
     */
    public int|null $created_by = 0;

    /**
     * VARCHAR(255) NOT NULL DEFAULT ''
     * @var string
     */
    public string $created_by_alias = '';

    /**
     * TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Set if article is featured.'
     * @var int
     */
    public int $featured = 0;

    /**
     * LONGTEXT NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $fulltext = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     * @UNDOCUMENTED
     */
    public int $hits = 0;

    /**
     * INT(10) UNSIGNED NOT NULL AUTO_INCREMENT
     * @var int
     */
    public int $id = 0;

    /**
     * MEDIUMTEXT NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $images = '';

    /**
     * LONGTEXT NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $introtext = '';

    /**
     * CHAR(7) NOT NULL COMMENT 'The language code for the article.'
     * @var string
     * @UNDOCUMENTED
     */
    public string $language = '';

    /**
     * MEDIUMTEXT NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $metadata = '';

    /**
     * MEDIUMTEXT NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $metadesc = '';

    /**
     * TEXT DEFAULT NULL
     * @var string|null
     * @UNDOCUMENTED
     */
    public string|null $metakey = null;

    /**
     * DATETIME NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $modified = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public int $modified_by = 0;

    /**
     * VARCHAR(255) NOT NULL DEFAULT ''
     * @var string
     */
    public string $note = '';

    /**
     * DATETIME DEFAULT NULL
     * @var string|null
     * @UNDOCUMENTED
     */
    public string|null $publish_down = null;

    /**
     * DATETIME DEFAULT NULL
     * @var string|null
     * @UNDOCUMENTED
     */
    public string|null $publish_up = null;

    /**
     * TINYINT(3) NOT NULL DEFAULT 0
     * @var int
     */
    public int $state = 0;

    /**
     * The resource's title.
     * VARCHAR(255) NOT NULL DEFAULT ''
     * @var string
     */
    public string $title = '';

    /**
     * MEDIUMTEXT NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $urls = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 1
     * @var int
     */
    public int $version = 1;

    /** @inheritDoc */
    public function __construct(DatabaseInterface $dbo = null)
    {
        $dbo = $dbo ?? Application::database();

        /** @var DatabaseDriver $dbo */
        parent::__construct($dbo);
    }
}