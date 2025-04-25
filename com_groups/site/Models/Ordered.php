<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

/**
 * Allows standard override of generalized populate state function for list classes whose output is always ordered.
 */
trait Ordered
{
    /** @inheritDoc */
    protected function populateState($ordering = null, $direction = null): void
    {
        parent::populateState($ordering, $direction);

        $this->state->set('list.fullordering', 'ordering ASC');
        $this->state->set('list.ordering', 'ordering');
        $this->state->set('list.direction', 'ASC');
    }
}