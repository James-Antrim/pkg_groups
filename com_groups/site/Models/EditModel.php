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
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;
use stdClass;
use THM\Groups\Adapters\{Application, Input, FormFactory, MVCFactory};

/**
 * Class for editing a single resource record, based loosely on AdminModel, but without all the extra code it now caries
 * with it.
 */
abstract class EditModel extends FormModel
{
    /**
     * The data representing the resource.
     *
     * @var null|object
     */
    protected null|object $item = null;

    /**
     * The resource's table class.
     * @var string
     */
    protected string $tableClass = '';

    /**
     * @inheritDoc
     * Wraps the parent constructor to ensure inheriting classes specify their respective table classes.
     */
    public function __construct($config, MVCFactory $factory, FormFactory $formFactory)
    {
        if (empty($this->tableClass)) {
            $childClass = get_called_class();
            $exception  = new Exception("$childClass has not specified its associated table.");
            Application::handleException($exception);
        }

        parent::__construct($config, $factory, $formFactory);
    }

    /**
     * Retrieves a resource record. Inheriting classes will have to override this function to add table external values.
     * @return  object  object on success, false on failure.
     */
    public function getItem(): object
    {
        if (!$this->item) {
            $rowID = Input::selectedID();
            $table = $this->getTable();
            $table->load($rowID);
            $properties = $table->getProperties();

            $this->item = ArrayHelper::toObject($properties);
        }

        return $this->item;
    }

    /**
     * Method to get a table object.
     *
     * @param   string  $name     the table name, unused
     * @param   string  $prefix   the class prefix, unused
     * @param   array   $options  configuration array for model, unused
     *
     * @return  Table  a table object
     */
    public function getTable($name = '', $prefix = '', $options = []): Table
    {
        $fqn = "\\THM\\Groups\\Tables\\$this->tableClass";

        return new $fqn();
    }

    /** @inheritDoc */
    protected function loadFormData(): ?stdClass
    {
        return $this->getItem();
    }
}