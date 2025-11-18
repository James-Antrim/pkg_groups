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

use THM\Groups\Adapters\{Input, User as UserAdapter};
use THM\Groups\Helpers\{Categories, Users as UHelper};

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

        if ($rCategoryID = Input::integer('catid') and $rCategoryID !== $item->catid) {
            $item->catid = $rCategoryID;

            $rLevels = UserAdapter::levels(Categories::userID($rCategoryID));
            if (!in_array($item->access, $rLevels)) {
                $item->access = UHelper::PUBLIC_ACCESS;
            }
        }

        return $item;
    }

    //todo: step 1 - add associations in the style of content ~as is.
    //todo: step 2 - replace the use of articles modal with a list of directly relevant articles (catid & language)
}
