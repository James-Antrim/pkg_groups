<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use Joomla\CMS\MVC\{Factory\MVCFactoryInterface, Model\BaseDatabaseModel};
use THM\Groups\Adapters\{Database as DB, Input, Text};
use THM\Groups\Helpers\{Attributes, Groups, Users};
use THM\Groups\Tools\Migration;

/** @inheritDoc */
class Overview extends BaseDatabaseModel
{
    use Grouped;

    /** @inheritDoc */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        // Groups are irrelevant while the overview is being used as a search tool.
        if (!$terms = Input::string('search') or !Text::trim($terms)) {
            $this->groups();
        }

        parent::__construct($config, $factory);
    }

    /**
     * Retrieves the profiles available whose surnames sorted by the first letter of the first surname
     *
     * @return array an array of profiles grouped by letter
     */
    public function profiles(): array
    {
        $select  = DB::qn(['surnames', 'forenames', 'alias']);
        $aliased = DB::qn([['u.id', 'post.value', 'pre.value'], ['userID', 'post', 'pre']]);
        $gc      = ['GROUP_CONCAT(DISTINCT ' . DB::qn('ra.id') . ' AS ' . DB::qn('roles')];
        $query   = DB::query();
        $query->select(array_merge($aliased, $gc, $select));
        $query->from(DB::qn('#__users', 'u'))
            ->innerJoin(DB::qn('#__user_usergroup_map', 'uugm'), DB::qc('uugm.user_id', 'u.id'))
            ->leftJoin(DB::qn('#__groups_role_associations', 'ra'), DB::qc('ra.mapID', 'uugm.mapID'))
            ->leftJoin(
                DB::qn('#__groups_profile_attributes', 'post'),
                DB::qcs([['post.attributeID', Attributes::SUPPLEMENT_POST], ['post.userID', 'u.id']])
            )
            ->leftJoin(
                DB::qn('#__groups_profile_attributes', 'pre'),
                DB::qcs([['pre.attributeID', Attributes::SUPPLEMENT_PRE], ['pre.userID', 'u.id']])
            )
            ->where(DB::qc('u.published', Users::PUBLISHED))
            ->group(DB::qn('u.id'))
            ->order(implode(',', DB::qn(['u.surnames', 'u.forenames'])));

        if ($terms = Input::string('search') and $terms = Text::trim($terms)) {
            $terms      = Text::filter(Text::transliterate($terms));
            $conditions = explode('-', $terms);
            foreach ($conditions as $key => $term) {
                $conditions[$key] = DB::qc('alias', "%$term%", 'LIKE', true);
            }
            $query->where($conditions)->whereNotIn(DB::qn('uugm.group_id'), array_keys(Groups::STANDARD_GROUPS));
        }
        elseif ($this->groups) {
            $query->whereIn(DB::qn('uugm.group_id'), array_keys($this->groups));
        }
        else {
            $query->whereNotIn(DB::qn('uugm.group_id'), array_keys(Groups::STANDARD_GROUPS));
        }

        DB::set($query);

        // todo add this back as necessary for role resolution
        //$addContext = (count($this->groups) !== 1);
        $profiles = [];
        foreach (DB::arrays() as $profile) {
            switch ($letter = strtoupper(mb_substr($profile['surnames'], 0, 1))) {
                case 'Ä':
                    $letter = 'A';
                    break;
                case 'Ö':
                    $letter = 'O';
                    break;
                case 'Ü':
                    $letter = 'U';
                    break;
            }
            if (!array_key_exists($letter, $profiles)) {
                $profiles[$letter] = [];
            }

            // todo see if this is an actual iterable array or needs to be parsed into one
            echo "<pre>" . print_r($profile['roles'], true) . "</pre>";
            $profiles[$letter][] = $profile;
        }

        return $profiles;
    }
}
