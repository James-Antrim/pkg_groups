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
use Joomla\CMS\MVC\Model\FormModel as Core;
use THM\Groups\Adapters\{Application, FormFactory, MVCFactory};

/** @inheritDoc */
abstract class FormModel extends Core
{
    use Named;

    /** @inheritDoc */
    public function __construct($config, MVCFactory $factory, FormFactory $formFactory)
    {
        parent::__construct($config, $factory, $formFactory);

        $this->setContext();
    }

    /** @inheritDoc */
    public function getForm($data = [], $loadData = true): ?FormAlias
    {
        $options = ['control' => '', 'load_data' => $loadData];

        try {
            return $this->loadForm($this->context, strtolower($this->name), $options);
        }
        catch (Exception $exception) {
            Application::handleException($exception);
        }

        return null;
    }
}