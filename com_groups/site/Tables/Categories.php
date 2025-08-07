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

use Joomla\CMS\Table\Category;
use Joomla\Database\{DatabaseDriver, DatabaseInterface};
use THM\Groups\Adapters\Application;

/**
 * Class representing the category <=> person relations.
 */
class Categories extends Category
{
    use Resettable;

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     * @KEY cat_idx (`extension`,`published`,`access`)
     * @KEY idx_access (`access`)
     * @UNDOCUMENTED
     */
    public int $access = 0;

    /**
     * @inheritdoc
     *
     * VARCHAR(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT ''
     * @var string
     * @KEY idx_alias (`alias`(100)) not sure what 100 of 400 implies...
     */
    public $alias = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.'
     * @var int
     * @UNDOCUMENTED
     */
    public int $asset_id = 0;

    /**
     * INT(10) UNSIGNED DEFAULT NULL
     * @var int|null
     * @KEY idx_checkout (`checked_out`)
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
     * @var string
     * @UNDOCUMENTED
     */
    public string $created_time = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int|null
     * @CORRECTED INT(11) DEFAULT NULL
     */
    public int|null $created_user_id = null;

    /**
     * MEDIUMTEXT DEFAULT NULL
     * @var string|null
     */
    public string|null $description = null;

    /**
     * VARCHAR(50) NOT NULL DEFAULT ''
     * @var string
     * @KEY cat_idx (`extension`,`published`,`access`)
     */
    public string $extension = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public int $hits = 0;

    /**
     * INT(11) NOT NULL AUTO_INCREMENT
     * @var int
     * @PRIMARYKEY
     * @UNDOCUMENTED
     */
    public int $id = 0;

    /**
     * CHAR(7) NOT NULL DEFAULT ''
     * @var string
     * @KEY idx_language (`language`)
     */
    public string $language = '';

    /**
     * @inheritdoc
     *
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public $level = 0;

    /**
     * @inheritdoc
     *
     * INT(11) NOT NULL DEFAULT 0
     * @var int
     * @KEY idx_left_right (`lft`,`rgt`)
     */
    public $lft = 0;

    /**
     * VARCHAR(2048) NOT NULL DEFAULT '' COMMENT 'JSON encoded metadata properties.'
     * @var string
     */
    public string $metadata = '';

    /**
     * VARCHAR(1024) NOT NULL DEFAULT '' COMMENT 'The meta description for the page.'
     * @var string
     */
    public string $metadesc = '';

    /**
     * VARCHAR(1024) NOT NULL DEFAULT '' COMMENT 'The meta keywords for the page.'
     * @var string
     */
    public string $metakey = '';

    /**
     * DATETIME NOT NULL
     * @var string
     * @UNDOCUMENTED
     */
    public string $modified_time = '';

    /**
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int|null
     * @CORRECTED INT(11) DEFAULT NULL
     */
    public int|null $modified_user_id = null;

    /**
     * VARCHAR(255) NOT NULL DEFAULT ''
     * @var string
     */
    public string $note = '';

    /**
     * A JSON string containing category parameters.
     *
     * TEXT DEFAULT NULL
     * @var string|null
     */
    public string|null $params = null;

    /**
     * @inheritdoc
     *
     * Although technically a reference to the superordinate category, the structure is not identical and therefore no
     * true reference can be made. No typing due to inheritance.
     *
     * INT(10) UNSIGNED NOT NULL DEFAULT 0
     * @var int
     */
    public $parent_id = 0;

    /**
     * A chain of URL segments denoting the hierarchy of categories upto and including this one.
     *
     * VARCHAR(400) NOT NULL DEFAULT ''
     * @var string
     * @KEY idx_path (`path`(100)) not sure what 100 of 400 implies...
     * @UNDOCUMENTED
     */
    public string $path = '';

    /**
     * TINYINT(1) NOT NULL DEFAULT 0
     * @var int
     * @KEY cat_idx (`extension`,`published`,`access`)
     * @bool
     * @UNDOCUMENTED
     */
    public int $published = 0;

    /**
     * @inheritdoc
     *
     * INT(11) NOT NULL DEFAULT 0
     * @var int
     * @KEY idx_left_right (`lft`,`rgt`)
     */
    public $rgt = 0;

    /**
     * VARCHAR(255) NOT NULL DEFAULT ''
     * @var string
     */
    public string $title = '';

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