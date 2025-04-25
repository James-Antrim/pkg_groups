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

use Joomla\CMS\Toolbar\ToolbarHelper;
use THM\Groups\Adapters\{Application, HTML, Input, Text, Toolbar};
use THM\Groups\Helpers\{Attributes as AH, TemplateAttributes as Helper, Templates as TH};
use stdClass;
use THM\Groups\Layouts\ListItem;
use THM\Groups\Tables\Attributes as AT;

/**
 * View class for displaying available attribute types.
 * @todo Add a type migration dialog.
 */
class TemplateAttributes extends ListView
{
    /** @inheritDoc */
    protected function addToolbar(): void
    {
        $templateID = Input::getID();
        $toolbar    = Toolbar::getInstance();

        if ($available = $this->getAvailable()) {
            $addGroup = $toolbar->dropdownButton('add-group');
            $addBar   = $addGroup->getChildToolbar();

            foreach ($available as $attributeID => $attribute) {
                $addBar->addNew("TemplateAttributes.add.$templateID.$attributeID", $attribute);
            }
        }

        $toolbar->delete('TemplateAttributes.delete', 'GROUPS_DELETE')->message('JGLOBAL_CONFIRM_DELETE')->listCheck(true);
        $toolbar->cancel('TemplateAttributes.cancel');
        $toolbar->divider();

        ToolbarHelper::title(TH::getName($templateID), 'fa fa-list-ol');
    }

    /** @inheritDoc */
    protected function completeItem(int $index, stdClass $item, array $options = []): void
    {
        $attribute = new AT();
        $attribute->load($item->attributeID);
        $item->name = $attribute->{$options['label']};
        $neither    = in_array($item->attributeID, $options['unlabeledIDs']) ? $options['unlabeledTip'] : '';

        if (in_array($item->attributeID, AH::PROTECTED)) {
            $context    = "groups-attribute-$item->id";
            $item->name = HTML::icon('fa fa-lock') . " $item->name";
            $tip        = Text::_('GROUPS_PROTECTED_ATTRIBUTE');
            $item->name = HTML::tip($item->name, $context, $tip);
        }

        $item->showIcon  = HTML::toggle($index, Helper::showIconStates[$item->showIcon], 'TemplateAttributes', $neither);
        $item->showLabel = HTML::toggle($index, Helper::showLabelStates[$item->showLabel], 'TemplateAttributes', $neither);
    }

    /** @inheritDoc */
    protected function completeItems(array $options = []): void
    {
        $options = [
            'label'        => 'label_' . Application::tag(),
            'unlabeledIDs' => AH::getUnlabeled(),
            'unlabeledTip' => Text::_('TOGGLE_TIP_UNLABELED')
        ];

        parent::completeItems($options);
    }

    /**
     * Retrieves the attributes not already associated with this template.
     * @return array
     */
    private function getAvailable(): array
    {
        $associatedIDs = [];
        foreach ($this->items as $association) {
            $associatedIDs[] = $association->attributeID;
        }

        $all    = AH::resources();
        $allIDs = array_keys($all);

        if (!$availableIDs = array_diff($allIDs, $associatedIDs)) {
            return [];
        }

        $available = [];
        $label     = 'label_' . Application::tag();

        foreach ($availableIDs as $attributeID) {
            $attribute               = $all[$attributeID];
            $available[$attributeID] = $attribute->$label;
        }

        return $available;
    }

    /** @inheritDoc */
    protected function initializeColumns(): void
    {
        $this->headers = [
            'check'     => ['type' => 'check'],
            'ordering'  => ['active' => false, 'type' => 'ordering'],
            'name'      => [
                'link'       => ListItem::DIRECT,
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_ATTRIBUTE'),
                'type'       => 'value'
            ],
            'showIcon'  => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_SHOW_ICON'),
                'type'       => 'value'
            ],
            'showLabel' => [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('GROUPS_SHOW_LABEL'),
                'type'       => 'value'
            ]
        ];
    }
}
