<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Helpers;

use THM\Groups\Tables\TemplateAttributes as Table;

class TemplateAttributes
{
    use Persistent;

    public const SHOWN = 1, HIDDEN = 0;

    public const showIconStates = [
        self::SHOWN => [
            'class' => 'publish',
            'column' => 'showIcon',
            'task' => 'hideIcon',
            'tip' => 'GROUPS_TOGGLE_TIP_SHOWN'
        ],
        self::HIDDEN => [
            'class' => 'unpublish',
            'column' => 'showIcon',
            'task' => 'showIcon',
            'tip' => 'GROUPS_TOGGLE_TIP_HIDDEN'
        ]];

    public const showLabelStates = [
        self::SHOWN => [
            'class' => 'publish',
            'column' => 'showLabel',
            'task' => 'hideLabel',
            'tip' => 'GROUPS_TOGGLE_TIP_SHOWN'
        ],
        self::HIDDEN => [
            'class' => 'unpublish',
            'column' => 'showLabel',
            'task' => 'showLabel',
            'tip' => 'GROUPS_TOGGLE_TIP_HIDDEN'
        ]];

    /**
     * Gets the attribute id if the association.
     *
     * @param int $id the id of the association
     *
     * @return int
     */
    public static function getAttributeID(int $id): int
    {
        /** @var Table $table */
        $table = self::getTable();

        return ($table->load($id) and $table->attributeID) ? $table->attributeID : 0;
    }
}