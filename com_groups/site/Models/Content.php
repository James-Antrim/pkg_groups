<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Models;

/** @inheritDoc */
class Content extends EditModel
{
    protected string $tableClass = 'Content';

    /** @inheritDoc */
    public function getItem(): object
    {
        if ($this->item) {
            return $this->item;
        }

        $item = parent::getItem();

        $item->introtext   = trim((string) $item->introtext);
        $item->fulltext    = trim((string) $item->fulltext);
        $item->articletext = $item->fulltext ?
            $item->introtext . '<hr id="system-readmore">' . $item->fulltext : $item->introtext;

        return $item;
    }

    //todo: step 1 - add associations in the style of content ~as is.
    //todo: step 2 - replace the use of articles modal with a list of directly relevant articles (catid & language)
}
