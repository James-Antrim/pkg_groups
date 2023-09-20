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

class TemplateAttributes
{
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
}