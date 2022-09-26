<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use Exception;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Uri\Uri;
use THM\Groups\Helpers\Component;
use THM\Groups\Views\Named;

abstract class BaseView extends HtmlView
{
	use Named;

	public bool $backend;
	public bool $mobile;

	/**
	 * @inheritDoc
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);

		$this->_basePath = JPATH_COMPONENT_SITE;

		// Set the default template search path
		$this->_setPath('template', $this->_basePath . '/Templates');
		$this->_setPath('helper', $this->_basePath . '/Helpers');
		$this->_setPath('layout', $this->_basePath . '/Layouts');

		$this->backend = Component::backend();
		$this->mobile  = Component::mobile();
	}

	/**
	 * Execute and display a template script. Does not dump the responsibility for exception handling onto inheriting classes.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		try
		{
			parent::display($tpl);
		}
		catch (Exception $exception)
		{
			/**
			 * So many possible points of failure...
			 */
			Component::message($exception->getMessage(), 'error');
			Component::redirect(Uri::getInstance()::base(), $exception->getCode());
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getLayout(): string
	{
		return $this->_layout !== 'default' ? $this->_layout: strtolower($this->_name);
	}
}