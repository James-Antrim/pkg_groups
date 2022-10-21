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

use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\Database\DatabaseAwareTrait;

/**
 * Default factory for creating Form objects
 *
 * @since  4.0.0
 */
class FormFactory implements FormFactoryInterface
{
	use DatabaseAwareTrait;

	/**
	 * Method to get an instance of a form.
	 *
	 * @param   string  $name     The name of the form.
	 * @param   array   $options  An array of form options.
	 *
	 * @return  Form
	 */
	public function createForm(string $name, array $options = []): Form
	{
		$form = new Form($name, $options);

		$form->setDatabase($this->getDatabase());

		return $form;
	}
}
