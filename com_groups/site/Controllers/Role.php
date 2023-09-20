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

use Joomla\CMS\Table\Table;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Can;
use THM\Groups\Helpers\Roles as Helper;
use THM\Groups\Tables\Incremented;

class Role extends Form
{
    protected string $list = 'Roles';

    /**
     * @inheritDoc
     */
    protected function authorize(): void
    {
        if (!Can::administrate()) {
            Application::error(403);
        }
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(): array
    {
        return [
            'name_de' => Input::getString('name_de'),
            'name_en' => Input::getString('name_en'),
            'plural_de' => Input::getString('plural_de'),
            'plural_en' => Input::getString('plural_en')
        ];

    }

    /**
     * @inheritDoc
     */
    protected function store(Table $table, array $data, int $id = 0): int
    {
        if ($id and !$table->load($id)) {
            Application::message('GROUPS_412', Application::ERROR);
            return $id;
        }

        if (empty($table->ordering)) {
            $data['ordering'] = Helper::getMaxOrdering('roles') + 1;
        }

        if ($table->save($data)) {
            /** @var Incremented $table */
            return $table->id;
        }

        Application::message($table->getError(), Application::ERROR);
        return $id;
    }

}