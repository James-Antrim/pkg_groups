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

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\MVC\View\FormView as Base;
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Input;
use THM\Groups\Helpers\Can;
use THM\Groups\Views\Named;

/**
 * View class for handling lists of items.
 * - Overrides/-writes to avoid deprecated code in the platform or promote ease of use
 * - Supplemental functions to extract common code from list models
 */
abstract class FormView extends Base
{
	use Configured, Named;

	public bool $backend;
	public array $batch;
	public bool $mobile;
	protected $_layout = 'form';

	/**
	 * Constructor
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct(array $config)
	{
		// If this is not explicitly set going in Joomla will default to default without looking at the object property value.
		$config['layout'] = $this->_layout;

		parent::__construct($config);

		$this->canDo = ContentHelper::getActions('com_users');
		$this->configure();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		Input::set('hidemainmenu', true);

		if ($this->item->id)
		{

		}
		$titleKey = 'GROUPS_' . strtoupper($this->_name);

		ToolbarHelper::title(Text::_($titleKey), '');

		if (Can::administrate())
		{
			ToolbarHelper::preferences('com_groups');
			//ToolbarHelper::divider();
		}

		//ToolbarHelper::help('Users:_Groups');
	}
}