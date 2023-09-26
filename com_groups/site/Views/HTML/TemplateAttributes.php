<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use THM\Groups\Adapters\{Application, HTML, Input, Text};
use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Helpers\{Attributes as AH, TemplateAttributes as Helper, Templates as TH};
use THM\Groups\Layouts\ListItem;
use THM\Groups\Tables\Attributes as AT;

/**
 * View class for displaying available attribute types.
 * @todo Add a type migration dialog.
 */
class TemplateAttributes extends ListView
{
    /**
     * @inheritDoc
     */
    protected function addToolbar(): void
    {
        // Manage access is a prerequisite for getting this far
        $toolbar = Application::getToolbar();
        $toolbar->addNew('TemplateAttributes.add');
        $toolbar->delete('TemplateAttributes.delete', 'GROUPS_DELETE')->message('JGLOBAL_CONFIRM_DELETE')->listCheck(true);
        $toolbar->cancel('TemplateAttributes.cancel');
        $toolbar->divider();

        $templateID = Input::getID();
        ToolbarHelper::title(TH::getName($templateID));
    }

    /**
     * @inheritDoc
     */
    protected function completeItems(): void
    {
        $label               = 'label_' . Application::getTag();
        $unlabeledAttributes = AH::getUnlabeled();
        $unlabeledTip        = Text::_('GROUPS_TOGGLE_TIP_UNLABELED');

        foreach ($this->items as $rowNo => $item) {

            $attribute = new AT();
            $attribute->load($item->attributeID);
            $item->name = $attribute->$label;
            $neither    = in_array($item->id, $unlabeledAttributes) ? $unlabeledTip : '';

            if (in_array($item->attributeID, AH::PROTECTED)) {
                $context    = "groups-attribute-$item->id";
                $item->name = HTML::icon('fa fa-lock') . " $item->name";
                $tip        = Text::_('GROUPS_PROTECTED_ATTRIBUTE');
                $item->name = HTML::tip($item->name, $context, $tip);
            }

            $item->showIcon  = HTML::toggle($rowNo, Helper::showIconStates[$item->showIcon], 'TemplateAttributes', $neither);
            $item->showLabel = HTML::toggle($rowNo, Helper::showLabelStates[$item->showLabel], 'TemplateAttributes', $neither);
        }
    }

    /**
     * @inheritDoc
     */
    public function display($tpl = null): void
    {
        $this->todo = [
            'Make it so the protected items are unaffected during ordering/toggle.',
            'Add \'Add\' buttons for any attributes not already present.',
            'Add delete functionality.',
            'Add toggle functionality.',
            'Fix redirection on add/delete/toggle.'
        ];

        parent::display($tpl);
    }

    /**
     * @inheritDoc
     */
    protected function initializeHeaders(): void
    {
        $this->headers = [
            'check' => ['type' => 'check'],
            'ordering' => ['active' => false, 'type' => 'ordering'],
            'name' => [
                'link' => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_ATTRIBUTE'),
                'type' => 'value'
            ],
            'showIcon' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_SHOW_ICON'),
                'type' => 'value'
            ],
            'showLabel' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title' => Text::_('GROUPS_SHOW_LABEL'),
                'type' => 'value'
            ]
        ];
    }
}
