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

use THM\Groups\Adapters\Application;
use THM\Groups\Inputs\Input;
use THM\Groups\Tables\Types as Table;

class Types implements Selectable
{
    public const DATE = 8, EMAIL = 3, HTML = 7, IMAGE = 5, NAME = 2, SUPPLEMENT = 4, TELEPHONE = 6, TEXT = 1;

    /**
     * URL
     * BUTTON
     * ROOM
     * LIST
     * URL LIST
     * NESTED LIST
     */

    /**
     * @inheritDoc
     */
    public static function getAll(): array
    {
        $db    = Application::getDB();
        $query = $db->getQuery(true);
        $id    = 'DISTINCT ' . $db->quoteName('id');
        $types = $db->quoteName('#__groups_types');
        $query->select($id)->from($types);
        $db->setQuery($query);

        $return = [];

        if (!$typeIDs = $db->loadColumn())
        {
            return $return;
        }

        foreach ($typeIDs as $typeID)
        {
            $type = new Table();
            $type->load($typeID);

            $input = Inputs::INPUTS[$type->inputID];
            $input = "THM\Groups\Inputs\\$input";

            /** @var Input $input */
            $return[] = new $input($type);
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public static function getOptions(): array
    {
        $options = [];

        /** @var  Input $field */
        foreach (self::getAll() as $input)
        {
            $options[$input->getName()] = (object)[
                'text' => $input->getName(),
                'value' => $input->id
            ];
        }

        ksort($options);

        return $options;
    }
}