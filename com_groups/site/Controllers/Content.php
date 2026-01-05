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

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\{Filter\OutputFilter as OF, String\StringHelper};
use THM\Groups\Adapters\{Application, Input, User};
use THM\Groups\Helpers\{Categories, Pages as PHelper};
use THM\Groups\Tables\{Content as CTable, Pages as PTable};

/** @inheritDoc */
class Content extends FormController
{
    protected string $list = 'Contents';

    /**
     * Checks a new alias to ensure it is unique in its category context.
     *
     * @param array $data
     *
     * @return void
     */
    private function checkAlias(array &$data): void
    {
        $check = new CTable();

        if ($check->load(['alias' => $data['alias'], 'catid' => $data['catid']])) {
            Application::message('ALIAS_EXISTS', Application::WARNING);
        }

        $titles = $this->newTitles($data['catid'], $data['alias'], $data['title']);

        $data['alias'] = end($titles);
    }

    /** @inheritDoc */
    public function display($cachable = false, $urlparams = []): BaseController
    {
        if (!Categories::root()) {
            Application::message('NO_ROOT', Application::WARNING);
            Application::redirect(Input::referrer(), 412);
        }

        return parent::display($cachable, $urlparams);
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
        $table = new CTable();

        if ($id = $copy ? 0 : (int) $data['id']) {
            $table->load($id);
        }

        $state = (!isset($data['state']) or !in_array($data['state'], array_keys(PHelper::STATES))) ? PHelper::HIDDEN : (int) $data['state'];

        $data['created']          = $table->created ?? date('Y-m-d H:i:s');
        $data['created_by']       = $authorID;
        $data['created_by_alias'] = '';
        $data['featured']         = in_array($data['featured'], array_keys(PHelper::FEATURED_STATES)) ? $data['featured'] : PHelper::UNFEATURED;
        $data['modified']         = date('Y-m-d H:i:s');
        $data['modified_by']      = User::id();
        $data['state']            = $state;

        $data['images']     = '';
        $data['metadata']   = ['author' => '', 'rights' => '', 'robots' => '', 'xreference' => ''];
        $data['transition'] = '';
        $data['urls']       = '';

        /**
         * Properties needing work:
         * catid: chosen catid limited to children of the root category
         * -created_by implicitly takes the value from the category
         */

        $slugs = Application::configuration()->get('unicodeslugs');

        // Alter the title for save as copy
        if ($copy) {
            $original = new CTable();
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

        return $data;
    }

    /**
     * Code common in storing resource data.
     * @return int
     */
    protected function process(): int
    {
        $this->checkToken();
        $this->authorize();

        $id = Input::id();

        $this->data = $this->prepareData();
        // For save to copy, will otherwise be identical.
        $this->data['id'] = $id;

        $featured               = $this->data['featured'];
        $this->data['featured'] = PHelper::UNFEATURED;

        $table = new CTable();
        if ($id and !$table->load($id)) {
            Application::message('412', Application::ERROR);

            return $id;
        }

        if (!$table->save($this->data)) {
            Application::message('NOT_SAVED');
            return $id;
        }

        Application::message('SAVED');
        $contentID = $table->id;

        $table  = new PTable();
        $userID = Categories::userID($this->data['catid']);
        $data   = ['contentID' => $contentID, 'userID' => $userID];

        if ($table->load($data)) {
            $table->featured = $featured;
            $table->store();
        }
        else {
            $data['featured'] = $featured;
            $table->save($data);
        }

        return $contentID;
    }


    /**
     * Increments the title and alias as necessary within a category context
     *
     * @param int    $categoryID the id of the category providing context for incrementation
     * @param string $alias
     * @param string $title
     *
     * @return array
     */
    private function newTitles(int $categoryID, string $alias, string $title): array
    {
        $table = new CTable();
        while ($table->load(['alias' => $alias, 'catid' => $categoryID])) {
            if ($title === $table->title) {
                $title = StringHelper::increment($title);
            }

            $alias = StringHelper::increment($alias, 'dash');
        }

        return [$title, $alias];
    }
}