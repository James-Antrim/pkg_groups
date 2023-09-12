<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Helpers\Roles as Helper;
use THM\Groups\Helpers\Can;
use THM\Groups\Tables\Roles as Table;

class Role extends EditModel
{
    protected string $tableClass = 'Roles';

    /**
     * @inheritDoc
     */
    public function save(): int
    {
        if (!Can::administrate()) {
            Application::error(403);
        }

        $data['name_de']   = Input::getString('name_de');
        $data['name_en']   = Input::getString('name_en');
        $data['plural_de'] = Input::getString('plural_de');
        $data['plural_en'] = Input::getString('plural_en');

        $table = new Table();

        $id = Input::getID();
        if ($id and !$table->load($id)) {
            Application::message($table->getError(), Application::ERROR);
            return 0;
        }

        if (empty($table->ordering)) {
            $data['ordering'] = Helper::getMaxOrdering('roles') + 1;
        }

        if (!$table->save($data)) {
            Application::message($table->getError(), Application::ERROR);
            return 0;
        }

        return $table->id;
    }
}