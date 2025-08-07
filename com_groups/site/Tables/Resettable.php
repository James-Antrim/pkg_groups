<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Tables;

use Exception;
use ReflectionClass;
use THM\Groups\Adapters\Application;

trait Resettable
{
    /**
     * Method to reset class properties to the defaults set in the class
     * definition.
     * - Ignores the primary key and private class properties.
     * - Override fixes problem that NOT NULL is being ignored by the 'Default' value from 'SHOW FULL COLUMNS' statement.
     * -- Consequently allows inheriting tables to complete their property typing regardless of whether they are default null.
     *
     * @return  void
     */
    public function reset(): void
    {
        $reflection = new ReflectionClass($this);

        // Get the default values for the class from the table.
        foreach ($this->getFields() as $column => $definition) {
            // If the property is not the primary key or private, skip it.
            if (in_array($column, $this->_tbl_keys) or (str_starts_with($column, '_'))) {
                continue;
            }

            // Text derivatives default irredeemably to null, which will always conflict with PHP typing.
            if (in_array($definition->Type, ['longtext', 'mediumtext', 'text'])) {
                $definition->Default = '';
            }

            if ($definition->Null === 'NO' and $definition->Default === null) {
                try {
                    if ($property = $reflection->getProperty($column)) {

                        if (str_contains($property->getDocComment(), 'DEFAULT')) {
                            $definition->Default = $property->getDefaultValue();
                            continue;
                        }

                        if ($type = $property->getType()) {
                            switch ($type->getName()) {
                                case 'float':
                                    $definition->Default = 0.0;
                                    break;
                                // Bool isn't directly supported by SQL, mapped as int now.
                                case 'int':
                                    $definition->Default = 0;
                                    break;
                                case 'string':
                                    $definition->Default = '';
                                    break;
                            }
                        }
                    }
                }
                catch (Exception $exception) {
                    Application::handleException($exception);
                }

            }

            $this->$column = $definition->Default;
        }
    }
}