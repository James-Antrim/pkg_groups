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

class Controller extends BaseController
{
	/**
	 * @inheritDoc
	 */
	public function display($cachable = false, $urlparams = array()): BaseController
	{
		return parent::display($cachable, $urlparams);
	}
}