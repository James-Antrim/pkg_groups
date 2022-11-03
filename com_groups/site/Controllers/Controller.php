<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Controller class for attribute types.
 */
class Controller extends BaseController
{
	/**
	 * Calls the appropriate model delete function and redirects to the appropriate list. Authorization occurs in the
	 * called model.
	 */
	public function delete()
	{
		// Check for request forgeries
		$this->checkToken();

		$fqName = 'THM\\Groups\\Models\\' . $this->name;

		$model = new $fqName();
		$model->delete();

		$this->setRedirect("index.php?option=com_groups&controller=$this->name");
	}
}