<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2022 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Controllers;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Input\Input as CoreInput;
use THM\Groups\Adapters\Application;

class Profile extends FormController
{
    protected string $list = 'Profiles';

    /** @inheritDoc */
    public function __construct(
        $config = [],
        ?MVCFactoryInterface $factory = null,
        ?CMSApplication $app = null,
        ?CoreInput $input = null
    )
    {
        if (Application::backend()) {
            $this->list = 'Users';
        }

        parent::__construct($config, $factory, $app, $input);
    }

    /** @inheritDoc */
    protected function prepareData(): array
    {
        /** @todo implement this */
        return [];
    }
}