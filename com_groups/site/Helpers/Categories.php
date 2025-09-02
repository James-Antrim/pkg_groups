<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Database as DB;
use THM\Groups\Adapters\Input;
use THM\Groups\Tables\Categories as Table;

/**
 * Class for the handling of category related information.
 */
class Categories
{
    public const HIDDEN = 0, PUBLISHED = 1;

    /**
     * Retrieves the id of the root category if configured.
     *
     * @return int
     */
    public static function root(): int
    {
        if (!$rootID = (int) Input::parameters()->get('root-category')) {
            Application::message('NO_ROOT', Application::WARNING);
            return 0;
        }

        return $rootID;
    }

    /**
     * Attempts to resolve the given string to a valid user.
     *
     * @param   int|string  $segment  the url segment being checked
     *
     * @return bool|int true if root | int userID if associated with user | false
     */
    public static function resolve(int|string $segment): bool|int
    {
        if (is_numeric($segment)) {
            $categoryID = (int) $segment;
        }
        elseif (preg_match('/^(\d+)-[a-zA-Z\-]+$/', $segment, $matches)) {
            $categoryID = (int) $matches[1];
        }
        else {
            $categoryID = $segment;
        }

        if (!$categoryID) {
            return false;
        }

        if ($categoryID === self::root()) {
            return true;
        }

        return self::userID($categoryID) ?: false;
    }

    /**
     * Retrieves the id of the user associated with the given identifier.
     *
     * @param   int|string  $identifier  the information identifying the category
     *
     * @return int
     */
    public static function userID(int|string $identifier): int
    {
        $query = DB::query();
        $query->select(DB::qn(['id', 'alias', 'path', 'created_user_id']))->from(DB::qn('#__categories'));

        if (is_numeric($identifier)) {
            $literal    = false;
            $identifier = (int) $identifier;
            $subject    = 'id';
        }
        else {
            $literal = true;
            $subject = 'alias';
        }

        $query->where(DB::qc($subject, $identifier, '=', $literal));
        DB::set($query);

        if (!$results = DB::array()) {
            return 0;
        }

        $userID    = $results['created_user_id'];
        $userAlias = Users::alias($userID);

        // Category information is already set correctly
        if ($results['alias'] === $userAlias and $results['path'] === $userAlias) {
            return $userID;
        }

        preg_match('/\d+/', $userAlias, $autoIncrement);
        $title = Profiles::name($userID);

        if ($autoIncrement) {
            $title = "$title $autoIncrement[0]";
        }

        $table = new Table();
        $table->load($results['id']);
        $table->alias = $userAlias;
        $table->path  = $userAlias;
        $table->title = $title;

        return $table->store() ? $userID : 0;
    }
}