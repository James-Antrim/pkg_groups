<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use Joomla\{Filter\OutputFilter as OF, String\StringHelper};
use THM\Groups\Adapters\{Application, Input, User};
use THM\Groups\Helpers\Categories;
use THM\Groups\Tables\Content as Table;

/** @inheritDoc */
class Content extends FormController
{
    protected string $list = 'Contents';

    /**
     * Checks a new alias to ensure it is unique in its category context.
     *
     * @param   array  $data
     *
     * @return void
     */
    private function checkAlias(array &$data): void
    {
        $check = new Table();

        if ($check->load(['alias' => $data['alias'], 'catid' => $data['catid']])) {
            Application::message('ALIAS_EXISTS', Application::WARNING);
        }

        $titles = $this->newTitles($data['catid'], $data['alias'], $data['title']);

        $data['alias'] = end($titles);
    }

    /** @inheritDoc */
    protected function prepareData(): array
    {
        $data = Input::post();

        // The category and its relation to the root category are necessary for implicit property values
        $categoryID = $data['catid'] ?? 0;
        if (empty($categoryID) or !$authorID = Categories::userID($categoryID)) {
            Application::message('412', Application::ERROR);
            return [];
        }

        $task = Input::task();

        $copy  = $task === 'save2copy';
        $table = new Table();

        if ($id = $copy ? 0 : (int) $data['id']) {
            $table->load($id);
        }


        /**
         * Content properties not added to form and explicitly ignored:
         * -too advanced in features and access rights for most users
         * --attribs tab, images tab, metadata tab, metakey, metadesc, rules, urls tab, version, version_note
         * -inappropriate or useless
         * --created_by_alias, checked_out, checked_out_time, featured_up, featured_down, hits, note, publish_up, publish_down
         * -dual use: fields exist in both tables and do slightly different things
         * --featured, ordering
         * -wrong use
         * --fulltext, introtext
         * -wtf
         * --schema
         */

        $implicit = [
            'created'     => $table->created ?? date('Y-m-d H:i:s'),
            'created_by'  => $authorID,
            'modified'    => date('Y-m-d H:i:s'),
            'modified_by' => User::id(),
        ];

        /**
         * Properties needing work:
         * catid: chosen catid limited to children of the root category
         * -created_by implicitly takes the value from the category
         */

        $slugs = Application::configuration()->get('unicodeslugs');

        // Alter the title for save as copy
        if ($copy) {
            $original = new Table();
            $original->load($data['id']);

            if ($data['title'] === $original->title) {
                [$title, $alias] = $this->newTitles($data['catid'], $data['alias'], $data['title']);
                $data['title'] = $title;
                $data['alias'] = $alias;
            }
            elseif ($data['alias'] === $original->alias) {
                $this->checkAlias($data);
            }
        }
        // New and no alias entered
        elseif (empty($data['id']) and empty($data['alias'])) {
            $data['alias'] = $slugs ? OF::stringUrlUnicodeSlug($data['title']) : OF::stringUrlSafe($data['title']);
            $this->checkAlias($data);
        }
        // The alias has been changed
        elseif ($table->id and $table->alias !== $data['alias']) {
            // The alias has been deleted
            if (empty($data['alias'])) {
                $data['alias'] = $slugs ? OF::stringUrlUnicodeSlug($data['title']) : OF::stringUrlSafe($data['title']);
            }
            $this->checkAlias($data);
        }

        // todo: featured value has a different meaning that has to be specially handled

        return $data;
    }

    /**
     * Increments the title and alias as necessary within a category context
     *
     * @param   int     $categoryID  the id of the category providing context for incrementation
     * @param   string  $alias
     * @param   string  $title
     *
     * @return array
     */
    private function newTitles(int $categoryID, string $alias, string $title): array
    {
        $table = new Table();
        while ($table->load(['alias' => $alias, 'catid' => $categoryID])) {
            if ($title === $table->title) {
                $title = StringHelper::increment($title);
            }

            $alias = StringHelper::increment($alias, 'dash');
        }

        return [$title, $alias];
    }
}