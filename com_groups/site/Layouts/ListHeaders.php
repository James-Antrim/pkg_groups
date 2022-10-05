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

class ListHeaders
{
	/**
	 * Renders a check all box style list header.
	 */
	private static function check()
	{
		?>
        <th class="w-1 text-center">
			<?php echo HTML::_('grid.checkall'); ?>
        </th>
		<?php
	}

	/**
	 * Renders a sort activation list header. The values in the column denoted by this header are always the column 'ordering' of
	 * their respective tables.
	 *
	 * @param   string  $orderBy
	 * @param   string  $direction
	 */
	private static function ordering(string $orderBy, string $direction)
	{
		?>
        <th class="w-1 text-center d-none d-md-table-cell" scope="col">
			<?php echo HTML::_('searchtools.sort', '', 'ordering', $direction, $orderBy, null, 'asc', 'JGRID_HEADING_ORDERING',
				'icon-sort'); ?>
        </th>
		<?php
	}

	/**
	 * Renders list headers.
	 *
	 * @param   ListView  $view  the view being displayed
	 */
	public static function render(ListView $view)
	{
		$direction = $view->escape($view->state->get('list.direction', 'ASC'));
		$column    = $view->escape($view->state->get('list.ordering'));

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
						self::check();
						break;
					case 'ordering':
						self::ordering($column, $direction);
						break;
					case 'sort':
						self::sort($header['properties'], $header['title'], $header['column'], $column, $direction);
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
	 * @param   string  $orderBy     the column the results are currently ordered by
	 * @param   string  $direction   the current sort direction
	 */
	private static function sort(array $properties, string $title, string $column, string $orderBy, string $direction)
	{
		$properties = HTML::toProperties($properties);
		?>
        <th <?php echo $properties; ?>>
			<?php echo HTML::_('searchtools.sort', $title, $column, $direction, $orderBy); ?>
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