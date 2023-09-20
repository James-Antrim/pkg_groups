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
use Joomla\CMS\Form\Form as FormAlias;
use Joomla\CMS\MVC\Model\FormModel;
use THM\Groups\Adapters\Application;

abstract class Form extends FormModel
{
    use Named;

    /**
     * @inheritDoc
     */
    public function getForm($data = array(), $loadData = true): ?FormAlias
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