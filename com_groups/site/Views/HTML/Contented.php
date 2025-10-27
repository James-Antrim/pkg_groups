<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2025 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Views\HTML;

use THM\Groups\Adapters\Text;
use THM\Groups\Helpers\Pages as Helper;

/**
 * Handles code common for HTML output of resource attributes.
 */
trait Contented
{
    /**
     * Adds filtered columns common to content lists.
     *
     * @return void
     */
    public function filteredColumns(): void
    {
        if (!$state = $this->state->get('filter.state') or !in_array((int) $state, array_keys(Helper::STATES))) {
            $this->headers['state'] = [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('STATUS'),
                'type'       => 'value'
            ];
        }

        if (!$featured = $this->state->get('filter.featured')
            or !in_array((int) $featured, array_keys(Helper::FEATURED_STATES))
        ) {
            $this->headers['featured'] = [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('FEATURED'),
                'type'       => 'value'
            ];
        }

        if (!$levelID = $this->state->get('filter.levelID') or !is_numeric($levelID)) {
            $this->headers['level'] = [
                'properties' => ['class' => 'w-10 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('LEVEL'),
                'type'       => 'value'
            ];
        }

        if ($this->showLanguages and !$this->state->get('filter.language')) {
            $this->headers['language'] = [
                'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
                'title'      => Text::_('LANGUAGE'),
                'type'       => 'value'
            ];
        }

        $this->headers['id'] = [
            'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('ID'),
            'type'       => 'value'
        ];

        $this->headers['hits'] = [
            'properties' => ['class' => 'w-5 d-none d-md-table-cell', 'scope' => 'col'],
            'title'      => Text::_('HITS'),
            'type'       => 'value'
        ];

    }
}