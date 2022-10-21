<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Joomla\CMS\Form\Form as Base;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Language\Text;
use SimpleXMLElement;
use function get_class;

/**
 * Form Class for the Joomla Platform.
 *
 * This class implements a robust API for constructing, populating, filtering, and validating forms.
 * It uses XML definitions to construct form fields and a variety of field and rule classes to
 * render and validate the form.
 *
 * @link   https://www.w3.org/TR/html4/interact/forms.html
 * @link   https://html.spec.whatwg.org/multipage/forms.html
 * @since  1.7.0
 */
class Form extends Base
{
	/**
	 * Method to instantiate the form object.
	 *
	 * @param   string  $name     The name of the form.
	 * @param   array   $options  An array of form options.
	 *
	 * @since   1.7.0
	 */
	public function __construct($name, array $options = [])
	{
		parent::__construct($name, $options);

		FormHelper::addFieldPath(JPATH_SITE . '/components/com_groups/Fields');
		FormHelper::addFilterPath(JPATH_SITE . '/components/com_groups/forms');
		FormHelper::addFormPath(JPATH_SITE . '/components/com_groups/forms');
	}

	/**
	 * @inheritDoc
	 */
	protected function loadField($element, $group = null, $value = null)
	{
		// Make sure there is a valid SimpleXMLElement.
		if (!($element instanceof SimpleXMLElement))
		{
			$error = sprintf('%s::%s `xml` is not an instance of SimpleXMLElement', get_class($this), __METHOD__);
			Application::message($error, 'error');

			return false;
		}

		// Get the field type.
		$type = $element['type'] ? (string) $element['type'] : 'text';

		$fields = $this->getFieldClasses();
		if (!in_array($type, $fields))
		{
			return parent::loadField($element, $group, $value);
		}

		// Load the FormField object for the field.
		$field = $this->loadFieldClass($type);

		/*
		 * Get the value for the form field if not set.
		 * Default to the translated version of the 'default' attribute
		 * if 'translate_default' attribute if set to 'true' or '1'
		 * else the value of the 'default' attribute for the field.
		 */
		if ($value === null)
		{
			$default = (string) ($element['default'] ? $element['default'] : $element->default);

			if (($translate = $element['translate_default']) && ((string) $translate === 'true' || (string) $translate === '1'))
			{
				$lang = Application::getLanguage();

				if ($lang->hasKey($default))
				{
					$debug   = $lang->setDebug(false);
					$default = Text::_($default);
					$lang->setDebug($debug);
				}
				else
				{
					$default = Text::_($default);
				}
			}

			$value = $this->getValue((string) $element['name'], $group, $default);
		}

		$field->setForm($this);

		if ($field->setup($element, $value, $group))
		{
			return $field;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Checks for the available Table classes.
	 *
	 * @return array
	 */
	private function getFieldClasses(): array
	{
		$fields = [];
		foreach (glob(JPATH_SITE . '/components/com_groups/Fields/*') as $field)
		{
			$field    = str_replace(JPATH_SITE . '/components/com_groups/Fields/', '', $field);
			$fields[] = str_replace('.php', '', $field);
		}

		return $fields;
	}

	/**
	 * Loads a reasonably namespaced form field.
	 *
	 * @param   string  $field  the name of the field class to load
	 *
	 * @return FormField
	 */
	private function loadFieldClass(string $field): FormField
	{
		$fqName = 'THM\\Groups\\Fields\\' . $field;

		$field = new $fqName($this);
		$field->setDatabase($this->getDatabase());

		return $field;
	}
}
