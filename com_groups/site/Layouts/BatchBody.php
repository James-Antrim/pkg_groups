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

use THM\Groups\Views\HTML\ListView;

class BatchBody
{
	public static function render(ListView $view, string $batch)
	{
		if (empty($view->filterForm))
		{
			return;
		}

		if (!$batch = $view->filterForm->getGroup($batch))
		{
			return;
		}

		?>
		<div class="p-3">
			<form>
				<?php foreach ($batch as $field) :?>
				<div class="form-group">
					<?php echo $field->__get('label'); ?>
					<?php echo $field->__get('input'); ?>
				</div>
				<?php endforeach; ?>
			</form>
		</div>
		<?php
	}
}