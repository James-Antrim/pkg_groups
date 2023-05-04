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
use THM\Groups\Adapters\HTML;
use THM\Groups\Views\HTML\ListView;

class ListItem
{
    private const ADMIN = true;

    public const DIRECT = 1, NONE = 0, TAB = 2;
    public const LINK_TYPES = [self::DIRECT, self::NONE, self::TAB];

    /**
     * Renders a check all box style list header.
     *
     * @param int $rowNo the row iteration count
     * @param object $item the item being rendered
     */
    private static function check(int $rowNo, object $item): void
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
     * @param object $item the item being rendered
     * @param bool $enabled whether sorting has been enabled
     */
    private static function ordering(object $item, bool $enabled): void
    {
        $attributes = ['class' => 'sortable-handler'];

        if (!$item->access) {
            $attributes['class'] .= ' inactive';
        } elseif (!$enabled) {
            $attributes['class'] .= ' inactive';
            $attributes['title'] = Text::_('JORDERINGDISABLED');
        }

        $properties = HTML::toProperties($attributes);
        ?>
        <td class="text-center d-none d-md-table-cell">
            <span <?php echo $properties ?>>
                <span class="icon-ellipsis-v"></span>
            </span>
            <?php if ($item->access and $enabled) : ?>
                <!--suppress HtmlFormInputWithoutLabel -->
                <input type="text" class="width-20 text-area-order hidden" name="order[]" size="5"
                       value="<?php echo $item->ordering; ?>">
            <?php endif; ?>
        </td>
        <?php
    }

    /**
     * Renders a list item.
     *
     * @param ListView $view the view being rendered
     * @param int $rowNo the row number being rendered
     * @param object $item the item being rendered
     */
    public static function render(ListView $view, int $rowNo, object $item): void
    {
        $context     = $view->backend;
        $state       = $view->get('state');
        $direction   = $view->escape($state->get('list.direction'));
        $orderBy     = $view->escape($state->get('list.ordering'));
        $dragEnabled = ($orderBy == 'ordering' and strtolower($direction) == 'asc');
        ?>
        <tr>
            <?php

            foreach ($view->headers as $column => $header) {
                $linkType = (!empty($header['link']) and in_array($header['link'], self::LINK_TYPES)) ?
                    $header['link'] : self::NONE;

                $header['properties'] = $header['properties'] ?? [];
                switch ($header['type']) {
                    case 'check':
                        self::check($rowNo, $item);
                        break;
                    case 'ordering':
                        self::ordering($item, $dragEnabled);
                        break;
                    case 'sort':
                    case 'text':
                    case 'value':
                    default:
                        self::text($item, $column, $context, $linkType);
                        break;
                }
            }
            ?>
        </tr>
        <?php
    }

    /**
     * Renders a check all box style list header.
     *
     * @param object $item the current row item
     * @param string $column the current column
     * @param bool $context the display context (false: public, true: admin)
     * @param int $linkType the link type to use for the displayed column value
     */
    private static function text(object $item, string $column, bool $context, int $linkType): void
    {
        $value = $item->$column ?? '';

        if (is_array($value)) {
            $properties = HTML::toProperties($value['properties']);
            $value      = $value['value'];
        } else {
            $properties = '';
        }

        if ($main = $column === 'name') {
            $opener = "<th $properties scope=\"row\">";
            $closer = "</th>";
        } else {
            $opener = "<td $properties>";
            $closer = "</td>";
        }

        $linkOpen  = '';
        $linkClose = '';

        if ($linkType) {
            $editLink = $item->editLink ?? '';
            $viewLink = $item->viewLink ?? '';
            if ($url = $context === self::ADMIN ? $editLink : $viewLink) {
                $lProperties = ['href' => $url];

                if ($linkType === self::TAB) {
                    $lProperties['target'] = '_blank';
                }

                $linkOpen  = '<a ' . HTML::toProperties($lProperties) . '>';
                $linkClose = '</a>';
            }
        }

        echo $opener;

        if ($main and !empty($item->prefix)) {
            echo $item->prefix;
        }

        echo $linkOpen . $value . $linkClose;

        if ($main and !empty($item->icon)) {
            echo $item->icon;
        }

        if ($main and !empty($item->supplement)) {
            echo "<br><span class=\"small\">$item->supplement</span>";
        }

        if ($main and isset($item->requireReset) and $item->requireReset === 1) {
            echo '<span class="badge bg-warning text-dark">' . Text::_('GROUPS_RESET_REQUIRED') . '</span>';
        }

        echo $closer;
    }
}