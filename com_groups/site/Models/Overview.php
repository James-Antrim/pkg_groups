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
        $query = DB::query();
        $query->select(DB::qn(['u.id', 'surnames', 'forenames', 'alias']));
        $query->from(DB::qn('#__users', 'u'))
            ->innerJoin(DB::qn('#__user_usergroup_map', 'uugm'), DB::qc('uugm.user_id', 'u.id'))
            ->where(DB::qc('u.published', Users::PUBLISHED))
            ->order(implode(',', DB::qn(['u.surnames', 'u.forenames'])));

        if (Input::parameters()->get('showTitles', Input::YES)) {
            $query->select(DB::qn(['post.value', 'pre.value'], ['post', 'pre']))
                ->leftJoin(
                    DB::qn('#__groups_profile_attributes', 'post'),
                    DB::qcs([
                        ['post.attributeID', Attributes::SUPPLEMENT_POST],
                        ['post.userID', 'u.id']
                    ])
                )
                ->leftJoin(
                    DB::qn('#__groups_profile_attributes', 'pre'),
                    DB::qcs([
                        ['pre.attributeID', Attributes::SUPPLEMENT_PRE],
                        ['pre.userID', 'u.id']
                    ])
                );
        }

        if ($terms = Input::string('search') and $terms = Text::trim($terms)) {
            $terms      = Text::filter(Text::transliterate($terms));
            $conditions = explode('-', $terms);
            foreach ($conditions as $key => $term) {
                $conditions[$key] = DB::qc('alias', "%$term%", 'LIKE', true);
            }
            $query->where($conditions);
        }
        elseif ($this->groups) {
            $query->whereIn(DB::qn('uugm.group_id'), array_keys($this->groups));
        }
        else {
            $query->whereNotIn(DB::qn('uugm.group_id'), array_keys(Groups::STANDARD_GROUPS));
        }

        DB::set($query);

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
            $profiles[$letter][] = $profile;
        }

        return $profiles;
    }
}
