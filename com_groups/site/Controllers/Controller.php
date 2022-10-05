<?php
/**
 * @package     THM\Groups\Controllers
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace THM\Groups\Controllers;

use Joomla\CMS\MVC\Controller\BaseController;
use THM\Groups\Adapters\Component;

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

	/**
	 * @inheritDoc
	 */
	public function display($cachable = false, $urlparams = []): BaseController
	{
		return parent::display($cachable, $urlparams);
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @throws Exception
	 */
	public function saveOrderAjax()
	{
		// Check for request forgeries
		$this->checkToken();

		$fqName = 'THM\\Groups\\Models\\' . $this->name;
		$model  = new $fqName();

		if ($model->saveorder())
		{
			echo "1";
		}

		Component::getApplication()->close();
	}
}