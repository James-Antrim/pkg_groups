<?php
/**
 * @package     THM\Groups\Layouts
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace THM\Groups\Layouts;

use Joomla\CMS\Language\Text;
use THM\Groups\Adapters\HTML;
use THM\Groups\Views\HTML\ListView;

class ListItem
{
	/**
	 * Renders a check all box style list header.
	 *
	 * @param   array  $properties
	 */
	private static function check(int $row, object $item)
	{
		?>
        <td class="text-center">
			<?php echo HTML::_('grid.id', $row, $item->id, false, 'cid', 'cb', $item->name); ?>
        </td>
		<?php
	}

	/**
	 * Renders a sort activation list header.
	 *
	 * @param   string  $column
	 * @param   string  $order
	 * @param   string  $direction
	 */
	private static function order(string $column, string $order, string $direction)
	{
		$saveOrder = ($order == 'order' and strtolower($direction) == 'asc');
		$iconClass = $saveOrder ? '' : ' inactive" title="' . Text::_('JORDERINGDISABLED');
		?>
        <td class="text-center d-none d-md-table-cell">
			<?php
			?>
            <span class="sortable-handler<?php echo $iconClass ?>">
                                            <span class="icon-ellipsis-v" aria-hidden="true"></span>
                                        </span>
			<?php if ($saveOrder) : ?>
                <input type="text" class="hidden" name="order[]" size="5"
                       value="<?php echo $orderkey + 1; ?>">
			<?php endif; ?>
        </td>
		<?php
	}

	/**
	 * Renders list headers.
	 *
	 * @param   ListView  $view  the view being displayed
	 */
	public static function render(object $view)
	{
		$direction = $view->escape($view->state->get('list.direction'));
		$order     = $view->escape($view->state->get('list.ordering'));

		?>
        <thead>
        <tr>
			<?php

			foreach ($view->headers as $header)
			{
				$header['properties'] = $header['properties'] ?? [];
				switch ($header['type'])
				{
					case 'check':
						self::check($header['properties']);
						break;
					case 'order':
						self::order($header['properties'], $header['column'], $order, $direction);
						break;
					case 'sort':
						self::sort($header['properties'], $header['title'], $header['column'], $order, $direction);
						break;
					case 'text':
					default:
						self::text($header['properties'], $header['title']);
						break;
				}
			}
			?>
        </tr>
        </thead>
		<?php
	}

	/**
	 * Renders a check all box style list header.
	 *
	 * @param   array   $properties  the properties for the containing tag
	 * @param   string  $title       the title text to display
	 * @param   string  $column      the table column represented by the data displayed in this column
	 * @param   string  $order       the column the results are currently ordered by
	 * @param   string  $direction   the current sort direction
	 */
	private static function sort(array $properties, string $title, string $column, string $order, string $direction)
	{
		$properties = HTML::toProperties($properties);
		?>
        <th <?php echo $properties; ?>>
			<?php echo HTML::_('searchtools.sort', $title, $column, $direction, $order); ?>
        </th>
		<?php
	}

	/**
	 * Renders a check all box style list header.
	 *
	 * @param   array   $properties  the properties for the containing tag
	 * @param   string  $title       the title text to display. optional for default processing
	 */
	private static function text(array $properties, string $title = '')
	{
		$properties = HTML::toProperties($properties);
		?>
        <th <?php echo $properties; ?>>
			<?php echo Text::_($title); ?>
        </th>
		<?php
	}
}