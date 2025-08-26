<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\Form\Field\ListField;
use THM\Groups\Adapters\{Database as DB, HTML};
use THM\Groups\Helpers\Categories;

require_once HELPERS . 'content.php';

/**
 * Class JFormFieldAuthors which returns authors of specific content.
 */
class JFormFieldAuthors extends ListField
{
    protected $type = 'authors';

    /**
     * Cached array of the category items.
     *
     * @var    array
     */
    protected static array $options = [];

    /**
     * Returns a list of all authors associated with groups content.
     *
     * @return  array[]
     */
    private function authors(): array
    {
        $query = DB::query();
        $query->select('DISTINCT ' . DB::qn('u') . '*')
            ->from(DB::qn('#__users', 'u'))
            ->innerJoin(DB::qn('#__groups_content', 'c'), DB::qc('c.userID', 'u.id'))
            ->order(DB::qn(['surnames', 'forenames']));
        DB::set($query);

        foreach ($authors = DB::arrays() as $index => $author) {
            $authors[$index]['text']  = $author['forenames'] ?
                "{$author['surnames']}, {$author['forenames']}" : $author['surnames'];
            $authors[$index]['value'] = $author['id'];
        }

        return $authors;
    }

    /** @inheritDoc */
    protected function getOptions(): array
    {
        $default      = parent::getOptions();
        $rootCategory = Categories::root();

        if (empty($rootCategory)) {
            return $default;
        }

        $options = [];
        foreach ($this->authors() as $value) {
            $options[] = HTML::_($value['value'], $value['text']);
        }

        return array_merge($default, $options);
    }
}
