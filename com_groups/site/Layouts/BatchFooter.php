<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Layouts;

use Joomla\CMS\Language\Text;

class BatchFooter
{
	public static function render(string $controller, string $group)
	{
		?>
		<button type="button" class="btn btn-secondary" onclick="document.getElementById('batch-group-id').value=''" data-bs-dismiss="modal">
			<?php echo Text::_('GROUPS_CLOSE'); ?>
		</button>
		<button type="submit" class="btn btn-success" onclick="Joomla.submitbutton('user.batch');return false;">
			<?php echo Text::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
		<?php
	}
}