<?php
/**
 * @package     THM\Groups\Fields\Groups
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace THM\Groups\Fields;

use Joomla\CMS\Form\Field\ListField;
use THM\Groups\Helpers\Groups as Helper;

/**
 * Provides a list of context relevant groups.
 */
class Groups extends ListField
{
	protected $type = 'Groups';

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