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

use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Database\QueryInterface;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;
use THM\Groups\Tables\Templates as Table;
use THM\Groups\Tools\Migration;

/**
 * Model class for aggregating available roles data.
 */
class Templates extends ListModel
{
    protected string $defaultOrdering = 'name';

    /**
     * @inheritDoc
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null)
    {
        Migration::migrate();

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [];
        }

        parent::__construct($config, $factory);
    }

    /**
     * Sets the template with the given id as the default template for module contexts.
     * @return bool true if the default was reset successfully, otherwise false
     */
    public function resetDefault(): bool
    {
        if (!Can::administrate()) {
            Application::error(403);
        }

        $db = $this->getDatabase();

        $default   = $db->quoteName('default');
        $templates = $db->quoteName('#__groups_templates');

        $query = $db->getQuery(true);
        $query->update($templates)->set("$default = 0");
        $db->setQuery($query);

        return $db->execute();
    }

    /**
     * @inheritDoc
     */
    public function delete(): void
    {
        Application::message(\THM\Groups\Adapters\Text::_('GROUPS_503'));
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        $items = parent::getItems();

        foreach ($items as $item) {
            // Management access is a prerequisite of accessing this view at all.
            $item->access   = true;
            $item->editLink = Route::_('index.php?option=com_groups&view=TemplateAttributes&id=' . $item->id);
        }

        return $items;
    }

    /**
     * Build an SQL query to load the list data.
     * @return  QueryInterface
     */
    protected function getListQuery(): QueryInterface
    {
        // Create a new query object.
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);
        $tag   = Application::getTag();

        // Select the required fields from the table.
        $query->select(['*', $db->quoteName("name_$tag", 'name')])->from($db->quoteName('#__groups_templates'));

        $this->orderBy($query);

        return $query;
    }
}