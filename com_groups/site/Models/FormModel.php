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

use Exception;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\FormModel as Base;
use THM\Groups\Adapters\Application;

abstract class FormModel extends Base
{
    use Named;

    /**
     * @inheritDoc
     */
    public function getForm($data = array(), $loadData = true): ?Form
    {
        $options = ['control' => '', 'load_data' => $loadData];

        try {
            return $this->loadForm($this->context, strtolower($this->name), $options);
        } catch (Exception $exception) {
            Application::handleException($exception);
        }

        return null;
    }
}