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
use THM\Groups\Tables\Incremented;

class User extends FormController
{
    use Associated;

    protected string $list = 'Users';

    /** @inheritDoc */
    protected function authorize(): void
    {
        if (!Can::saveUser(Input::getID())) {
            Application::error(403);
        }
    }

    /** @inheritDoc */
    protected function prepareData(): array
    {
        $forename = Input::getString('forename');
        $surname  = Input::getString('surname');
        $name     = $forename ? "$forename $surname" : $surname;

        return [
            // id handled separately later

            /** User Access **/
            // "user_details
            'email'          => Input::getString('email'),
            'forename'       => $forename,
            'name'           => $name,
            'surname'        => $surname,
            'password'       => Input::getString('password'), // hmmm
            'password2'      => Input::getString('password2'), // hmmm
            'username'       => Input::getString('username'),
            // "profile"
            // Profile attribute values...
            // "settings"
            'language'       => Input::getString('language'),
            'timezone'       => Input::getString('timezone'),
            // "accessibility"
            'a11y_contrast'  => Input::getString('a11y_contrast'), // 0/'high_contrast'
            'a11y_font'      => Input::getString('a11y_font'), // 0/'fontsize'
            'a11y_highlight' => Input::getString('a11y_highlight'), // 0/'highlight'
            'a11y_mono'      => Input::getString('a11y_mono'), // 0/'monochrome'

            /** Admin Access **/
            // "user_details"
            'block'          => (int) Input::getBool('block'),
            // Groups field...
            'requireReset'   => (int) Input::getBool('requireReset'),
            // "settings"
            'admin_style'    => Input::getInt('admin_style'),
            'admin_language' => Input::getString('admin_language'),
            'editor'         => Input::getString('editor'),

            /** Trash **/
            // "user_details"
            'lastResetTime'  => Input::getString('lastResetTime'), // immutable and stupid?
            'lastvisitDate'  => Input::getString('lastvisitDate'), // existing: keep, new: irrelevant, field: stupid?
            'registerDate'   => Input::getString('registerDate'), // existing: keep, new: now, field: unnecessary?
            'resetCount'     => Input::getInt('resetCount'), // immutable and stupid?
            'sendEmail'      => 0, // No
        ];
    }

    /**
     * Code common in storing resource data.
     * @return int
     */
    protected function process(): int
    {
        Application::error(503);
        $this->checkToken();
        $this->authorize();
        $data  = $this->prepareData();
        $id    = Input::getID();
        $table = $this->getTable();

        return $this->store($table, $data, $id);
    }

    /** @inheritDoc */
    protected function store(Table $table, array $data, int $id = 0): int
    {
        if ($id and !$table->load($id)) {
            Application::message('GROUPS_412', Application::ERROR);

            return $id;
        }

        if ($table->save($data)) {
            /** @var Incremented $table */
            return $table->id;
        }

        Application::message($table->getError(), Application::ERROR);

        return $id;
    }

}