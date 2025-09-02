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
use THM\Groups\Adapters\{Application, Database as DB, Input};
use THM\Groups\Helpers\{Attributes as AH, Profiles as PH, Types, Users as UH};
use THM\Groups\Tables\{ProfileAttributes as PAT, Users as Table};

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

    /**
     * Creates a profile based on rudimentary user data.
     *
     * @param   int  $userID
     *
     * @return void
     */
    public static function create(int $userID): void
    {
        $user = new Table();

        // Critical inconsistency
        if (!$user->load($userID)) {
            Application::message('GROUPS_412', Application::ERROR);
            return;
        }

        // Profile exists
        if ($user->surnames !== null or str_contains($user->name, "'")) {
            return;
        }

        [$forenames, $surnames] = UH::parseNames($user->name);

        $names = $forenames ? $surnames : "$forenames $surnames";
        $alias = UH::createAlias($userID, $names);

        $params    = Input::parameters();
        $content   = $params->get('profile-content');
        $content   = in_array($content, [PH::DISABLED, PH::ENABLED]) ? $content : PH::DISABLED;
        $editing   = $params->get('profile-management');
        $editing   = in_array($editing, [PH::DISABLED, PH::ENABLED]) ? $editing : PH::DECENTRALIZED;
        $published = $params->get('automatic-publishing');
        $published = in_array($published, [PH::DISABLED, PH::ENABLED]) ? $published : PH::ENABLED;

        $query = DB::query();
        $query->update(DB::qn('#__users'))
            ->set([
                DB::qc('alias', $alias),
                DB::qc('content', $content),
                DB::qc('editing', $editing),
                DB::qc('forenames', $forenames),
                DB::qc('published', $published),
                DB::qc('surnames', $surnames),
            ])
            ->where(DB::qc('id', $userID));
        DB::set($query);

        if (!DB::execute()) {
            Application::message('500', Application::ERROR);
            return;
        }

        // Find the first attribute of an email type and set the joomla user email there as a default value
        $query = DB::query();
        $query->select('MIN(' . DB::qn('id') . ')')->from(DB::qn('#__groups_attributes'))->where('typeID', Types::EMAIL);
        DB::set($query);

        if ($emailID = DB::integer()) {
            $pat = new PAT();
            if (!$pat->load(['attributeID' => $emailID, 'userID' => $userID])) {
                $pat->save([
                    'attributeID' => $emailID,
                    'published'   => AH::HIDDEN,
                    'userID'      => $userID,
                    'value'       => $user->email
                ]);
            }
        }
    }

    /** @inheritDoc */
    protected function prepareData(): array
    {
        /** @todo implement this */
        return [];
    }
}