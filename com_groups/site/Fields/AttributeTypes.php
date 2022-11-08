<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\Field\ListField;
use THM\Groups\Helpers\AttributeTypes as Helper;

/**
 * Provides a list of context relevant groups.
 */
class AttributeTypes extends ListField
{
	protected $type = 'AttributeTypes';

	/**
	 * Method to get the group options.
	 *
	 * @return  array  the group option objects
	 */
	protected function getOptions(): array
	{
		$defaultOptions = parent::getOptions();
		$options        = Helper::getOptions();

		return array_merge($defaultOptions, $options);
	}
}