<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Inputs;

use THM\Groups\Adapters\Application;
use THM\Groups\Tables;

abstract class Input
{
	// autocomplete: FF, 'on'
	// autofocus: FF, false
	// defaultValue (default): FF, none
	// disabled: FF, false
	// form: FF, none and references a Form object, not form element
	public string $hint = '';
	public int $id = 0;
	public string $message_de = '';
	public string $message_en = '';
	// name: FF, none and is the name of the input, not the class
	public string $name_de;
	public string $name_en;
	// readOnly: FF, false
	// required: FF, false
	// size: FF, none
	public bool $supported = true;
	public string $type;
	public bool $validate = false;

	/**
	 * Creates the input derivative class.
	 *
	 * @param   Tables\AttributeTypes|null  $type  the type implementing the input
	 */
	public function __construct(Tables\AttributeTypes $type = null)
	{
		if ($type)
		{
			$inputName = $this->getName();

			$this->id      = $type->id;
			$this->name_de = $type->name_de;
			$this->name_en = $type->name_en;

			$typeName = $this->getName();

			if ($configuration = json_decode($type->configuration, true))
			{
				foreach ($configuration as $property => $value)
				{
					if (property_exists($this, $property))
					{
						$this->$property = $value;
					}
					else
					{
						echo "<pre>Attribute Type $typeName is configured with the property $property, which is unsupported in $inputName.</pre>";
					}
				}
			}

			if (empty($this->pattern))
			{
				$this->validate = false;
			}
		}
	}

	/**
	 * Gets the localized invalid message.
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		$name = 'message_' . Application::getTag();

		return $this->$name;
	}

	/**
	 * Gets the localized input type name.
	 *
	 * @return string
	 */
	public function getName(): string
	{
		$name = 'name_' . Application::getTag();

		return $this->$name;
	}
}