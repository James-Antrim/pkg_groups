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
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\{Attributes as AH, TemplateAttributes as Helper};
use THM\Groups\Adapters\Text;
use THM\Groups\Tables\TemplateAttributes as Table;

class TemplateAttributes extends Attributed
{
    /**
     * @inheritdoc
     * Not used here due to the attributes themselves being managed in their own context.
     */
    protected string $item = 'TemplateAttribute';

    public function add(): void
    {
        $referrer = Input::getReferrer();
        if ($args = Input::getArray('args') and count($args) === 2) {
            [$templateID, $attributeID] = $args;
            $data  = ['attributeID' => $attributeID, 'templateID' => $templateID];
            $table = new Table();
            if ($table->load($data)) {
                Application::message('GROUPS_EXISTS', Application::NOTICE);
            }
            else {
                $data['ordering'] = Helper::getMaxOrdering('template_attributes', ['templateID' => $templateID]) + 1;
                if ($table->save($data)) {
                    Application::message('GROUPS_SAVED');
                }
                else {
                    Application::message('GROUPS_FAILED', Application::ERROR);
                }
            }
        }
        $this->setRedirect($referrer);
    }

    /**
     * Closes the form view without saving changes.
     * @return void
     */
    public function cancel(): void
    {
        $this->setRedirect("$this->baseURL&view=Templates");
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     * @return  void
     */
    public function saveOrderAjax(): void
    {
        $this->checkToken();
        $this->authorizeAJAX();

        $ordering       = 1;
        $associationIDs = Input::getArray('cid');

        foreach ($associationIDs as $associationID) {
            $attributeID = Helper::getAttributeID($associationID);
            $table       = new Table();
            $table->load($associationID);

            if (in_array($attributeID, AH::PROTECTED)) {
                $table->ordering = 0;
            }
            else {
                $table->ordering = $ordering;
                $ordering++;
            }

            $table->store();

        }

        echo Text::_('Request performed successfully.');

        $this->app->close();
    }

    /** @inheritDoc */
    public function toggle(string $column, bool $value): void
    {
        $this->checkToken();
        $this->authorize();

        $selectedIDs = Input::getSelectedIDs();
        $selected    = count($selectedIDs);
        $updated     = $this->updateBool($column, $selectedIDs, $value);

        $this->farewell($selected, $updated);
    }

    /** @inheritDoc */
    protected function farewell(int $selected = 0, int $updated = 0, bool $delete = false, bool $autoRedirect = false): void
    {
        parent::farewell($selected, $updated, $delete, $autoRedirect);

        $referrer = Input::getReferrer();
        parse_str($referrer, $params);

        $url = $this->baseURL;
        $url .= empty($params['id']) ? '&view=Templates' : "&view=TemplateAttributes&id={$params['id']}";

        $this->setRedirect($url);
    }
}