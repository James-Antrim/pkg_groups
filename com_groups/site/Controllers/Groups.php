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

use THM\Groups\Adapters\Application;

class Groups extends ListController
{
    /** @inheritDoc */
    protected string $item = 'Group';

    /** @inheritDoc */
    public function delete(): void
    {
        Application::message('GROUPS_503');
    }
}