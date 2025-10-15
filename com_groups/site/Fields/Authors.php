<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\Field\ListField;
use THM\Groups\Adapters\{Database as DB, Text};
use THM\Groups\Helpers\Categories;

/**
 * Provides a list of roles.
 */
class Authors extends ListField
{
    /**
     * Method to get the group options.
     *
     * @return  array  the group option objects
     */
    protected function getOptions(): array
    {
        $options = [];
        if ($root = Categories::root()) {
            $query = DB::query();
            $query->select(['DISTINCT ' . DB::qn('u.id', 'value'), DB::qn('u.surnames'), DB::qn('u.forenames')])
                ->from(DB::qn('#__users', 'u'))
                ->innerJoin(DB::qn('#__categories', 'c'), DB::qc('c.created_user_id', 'u.id'))
                ->where(DB::qc('c.parent_id', $root))
                ->order(DB::qn(['u.surnames', 'u.forenames']));
            DB::set($query);

            if ($results = DB::arrays('value')) {

                $pKey = null;

                foreach ($results as $result) {
                    $author = $result['forenames'] ? $result['surnames'] . ', ' . $result['forenames'] : $result['surnames'];
                    $value  = $result['value'];
                    if ($pKey) {
                        $pOption = $options[$pKey];
                        if (strpos($pOption->text, $author)) {
                            if (!preg_match('/[0-9]/', $pOption->text)) {
                                $pOption->text = "$author ($pKey)";
                            }

                            $author = "$author ($pKey)";
                        }
                    }
                    $pKey           = $value;
                    $options[$pKey] = (object) ['text' => $author, 'value' => $value];
                }
            }
            else {
                $options[] = (object) ['text' => Text::_('NONE'), 'value' => ''];
            }
        }

        return array_merge(parent::getOptions(), $options);
    }
}