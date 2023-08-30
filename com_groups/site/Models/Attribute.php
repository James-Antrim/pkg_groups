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
use Joomla\CMS\Object\CMSObject;
use THM\Groups\Helpers\Attributes as Helper;
use THM\Groups\Helpers\{Can, Icons, Types};
use THM\Groups\Tables\Attributes as Table;

class Attribute extends EditModel
{
    protected string $tableClass = 'Attributes';

    /**
     * @inheritDoc
     */
    protected function loadFormData(): ?CMSObject
    {
        $item    = $this->getItem();
        $options = empty($item->options) ? [] : json_decode($item->options, true);

        if (!empty($options['buttons'])) {
            $item->buttons = true;

            if (!empty($options['hide'])) {
                $property        = 'hide[]';
                $item->$property = $options['hide'];
            }
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function save(): int
    {
        if (!Can::administrate()) {
            Application::error(403);
        }

        $context         = Input::getInt('context');
        $data['context'] = in_array($context, Helper::CONTEXTS) ? $context : Helper::BOTH_CONTEXTS;

        $icon         = Input::getString('icon');
        $data['icon'] = Icons::supported($icon);

        $data['label_de']  = Input::getString('label_de');
        $data['label_en']  = Input::getString('label_en');
        $data['showIcon']  = (int) Input::getBool('showIcon');
        $data['showLabel'] = (int) Input::getBool('showLabel');

        $typeID         = Input::getInt('typeID');
        $typeID         = array_key_exists($typeID, Types::TYPES) ? $typeID : Types::TEXT;
        $data['typeID'] = $typeID;

        switch ($typeID) {
            case Types::HTML:
                $options = [];
                if (Input::getBool('buttons')) {
                    $options['buttons'] = 1;

                    if ($hide = Input::getArray('hide')) {
                        $options['hide'] = $hide;
                    }
                }
                $data['options'] = json_encode($options);
                break;
            default:
                $data['options'] = '{}';
                break;
        }

        $id    = Input::getID();
        $table = new Table();

        if ($id and !$table->load($id)) {
            Application::message($table->getError(), Application::ERROR);
            return 0;
        }

        if (!$table->save($data)) {
            Application::message($table->getError(), Application::ERROR);
            return 0;
        }

        return $table->id;
    }
}