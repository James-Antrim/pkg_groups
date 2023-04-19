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

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use THM\Groups\Adapters\Application;
use THM\Groups\Helpers\Can;

/**
 * Controller class for attribute types.
 */
class Controller extends BaseController
{
    /**
     * Calls the appropriate model delete function and redirects to the appropriate list. Authorization occurs in the
     * called model.
     */
    public function delete()
    {
        // Check for request forgeries
        $this->checkToken();

        $fqName = 'THM\\Groups\\Models\\' . $this->name;

        $model = new $fqName();
        $model->delete();

        $this->setRedirect("index.php?option=com_groups&controller=$this->name");
    }

    /**
     * @inheritDoc
     */
    public function display($cachable = false, $urlparams = []): BaseController|Controller
    {
        if (!$view = $this->input->get('view'))
        {
            Application::error(501);
        }

        $format = strtoupper($this->input->get('format', 'HTML'));
        if (!class_exists("\\THM\\Groups\\Views\\$format\\$view"))
        {
            Application::error(503);
        }

        if (!Can::view($view))
        {
            Application::error(403);
        }

        return parent::display($cachable, $urlparams);
    }

    /**
     * Checks against unauthenticated access and returns the id of the current user.
     *
     * @return int
     */
    protected function getUserID(): int
    {
        if (!$userID = Application::getUser()->id)
        {
            Application::error(401);
        }

        return $userID;
    }

    /**
     * Updates a boolean column for multiple entries in a
     * @param string $name
     * @param string $column
     * @param array $selectedIDs
     * @param bool $value
     * @return int
     */
    protected function updateBool(string $name, string $column, array $selectedIDs, bool $value): int
    {
        $fqName = "THM\Groups\Tables\\$name";
        $table  = new $fqName();

        if (!property_exists($table, $column))
        {
            Application::message(Text::_('GROUPS_TABLE_COLUMN_NONEXISTENT'), Application::ERROR);

            return 0;
        }

        $total = 0;
        $value = (int)$value;

        foreach ($selectedIDs as $selectedID)
        {
            $table = new $fqName();

            if ($table->load($selectedID) and $table->$column !== $value)
            {
                $table->$column = $value;

                if ($table->store())
                {
                    $total++;
                }
            }
        }

        return $total;
    }
}