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
	private const ADMIN = true;

	/**
	 * Renders a check all box style list header.
	 *
	 * @param   int     $rowNo  the row iteration count
	 * @param   object  $item   the item being rendered
	 */
	private static function check(int $rowNo, object $item)
	{
		?>
        <td class="text-center">
			<?php echo HTML::_('grid.id', $rowNo, $item->id, false, 'cid', 'cb', $item->name); ?>
        </td>
		<?php
	}

	/**
	 * Renders a sorting tool.
	 *
	 * @param   object  $item     the item being rendered
	 * @param   bool    $enabled  whether sorting has been enabled
	 */
	private static function order(object $item, bool $enabled)
	{
		$attributes = ['class' => 'sortable-handler'];

		if (!$item->access)
		{
			$attributes['class'] .= ' inactive';
		}
        elseif (!$enabled)
		{
			$attributes['class'] .= ' inactive';
			$attributes['title'] = Text::_('JORDERINGDISABLED');
		}

		$properties = HTML::toProperties($attributes);
		?>
        <td class="text-center d-none d-md-table-cell">
			<?php
			?>
            <span <?php echo $properties ?>>
                <span class="icon-ellipsis-v"></span>
            </span>
			<?php if ($item->access and $enabled) : ?>
                <input type="text" class="hidden" name="order[]" size="5" value="<?php echo $item->order; ?>">
			<?php endif; ?>
        </td>
		<?php
	}

	/**
	 * Renders a list item.
	 *
	 * @param   ListView  $view   the view being rendered
	 * @param   int       $rowNo  the row number being rendered
	 * @param   object    $item   the item being rendered
	 */
	public static function render(ListView $view, int $rowNo, object $item)
	{
		$context         = $view->backend;
		$direction       = $view->escape($view->state->get('list.direction', 'ASC'));
		$column          = $view->escape($view->state->get('list.ordering'));
		$orderingEnabled = ($column === 'order' and strtolower($direction) == 'asc');

		?>
        <thead>
        <tr>
			<?php

			foreach ($view->headers as $column => $header)
			{
				$header['properties'] = $header['properties'] ?? [];
				switch ($header['type'])
				{
					case 'check':
						self::check($rowNo, $item);
						break;
					case 'order':
						self::order($item, $orderingEnabled);
						break;
					case 'sort':
					case 'text':
						self::text($item, $column, $context);
						break;
					case 'value':
					default:
						self::text($item, $column, $context, false);
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
	 * @param   object  $item     the current row item
	 * @param   string  $column   the current column
	 * @param   bool    $context  the display context (false: public, true: admin)
	 * @param   bool    $link     whether to display the column information as a link
	 * @param   bool    $newTab   whether to open the link in a new tab
	 */
	private static function text(object $item, string $column, bool $context, bool $link = true, bool $newTab = false)
	{
		$value = $item->$column;

		if (is_array($value))
		{
			$properties = HTML::toProperties($value['properties']);
			$value      = $value['value'];
		}
		else
		{
			$properties = '';
		}

		$linkOpen  = '';
		$linkClose = '';

		if ($link)
		{
			$editLink = $item->editLink ?? '';
			$viewLink = $item->viewLink ?? '';
			if ($url = $context === self::ADMIN ? $editLink : $viewLink)
			{
				$properties = ['href' => $url];

				if ($newTab)
				{
					$properties['target'] = '_blank';
				}

				$properties = HTML::toProperties($properties);
				$linkOpen   = '<a ' . $properties . '>';
				$linkClose  = '</a>';
			}
		}

		?>
        <td <?php echo $properties; ?>>
			<?php echo $linkOpen; ?>
			<?php echo $value; ?>
			<?php echo $linkClose; ?>
        </td>
		<?php
	}
}