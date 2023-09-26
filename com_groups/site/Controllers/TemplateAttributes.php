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

use THM\Groups\Adapters\Input;

class TemplateAttributes extends Labeled
{
    /**
     * @inheritdoc
     * Not used here due to the attributes themselves being managed in their own context.
     */
    protected string $item = 'TemplateAttribute';

    /**
     * Closes the form view without saving changes.
     * @return void
     */
    public function cancel(): void
    {
        $this->setRedirect("$this->baseURL&view=Templates");
    }

    /**
     * @inheritdoc
     */
    public function toggle(string $column, bool $value): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool($column, $selectedIDs, $value);

        $this->farewell($selected, $updated, false, false);

        $referrer = Input::getReferrer();
        parse_str($referrer, $params);

        $url = $this->baseURL;
        $url .= empty($params['id']) ? '&view=Templates' : "&view=TemplateAttributes&id={$params['id']}";

        $this->setRedirect($url);
    }
}