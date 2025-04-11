<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Tools;

use CurlHandle;
use stdClass;
use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Helpers\Groups;
use THM\Groups\Tables\Users;
use THM\Groups\Tools\FIS\{Cards, Persons};

class Integration
{
    // Configuration URLs
    private const
        ASSOCIATION_CONFIGURATIONS = 1,
        ENTITY_CONFIGURATIONS = 2,
        SPECIFIC_ASSOCIATION = 3,
        SPECIFIC_ENTITY = 4;

    private const HEADERS = [
        'attributes' => 'Converis-attribute-definition: ALL',
        'links'      => 'Converis-linkentity-references: true'
    ];

    private const CONFIGURATIONS = [
        self::ASSOCIATION_CONFIGURATIONS => 'config/linkentities',
        self::ENTITY_CONFIGURATIONS      => 'config/entities',
        self::SPECIFIC_ASSOCIATION       => 'config/linkentities/',
        self::SPECIFIC_ENTITY            => 'config/entities/'
    ];

    public static function fillIDs(): void
    {
        $curl = curl_init();
        if (!self::setCredentials($curl)) {
            return;
        }

        if (!$url = self::getURL()) {
            return;
        }

        if (!$users = self::getUsers(false)) {
            return;
        }

        // TODO: configure entity specific retrieval templates
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Converis-attribute-definition: ALL']);

        $url = $url . Persons::QUERIES['ALL'];

        if (!$persons = self::getRecords($curl, $url)) {
            return;
        }

        self::processPersons($users, $persons);
    }

    /**
     * Provides standardized retrieval of the URL configured to communicate with the converis API.
     * @return string
     */
    private static function getURL(): string
    {
        if (!$url = (string) Input::getParams()->get('fis')) {
            Application::message('Converis credentials have not been configured.', Application::WARNING);
            return '';
        }

        return $url;
    }

    /**
     * Retrieves a single record from the converis API
     *
     * @param   CurlHandle  $curl  the curl handle used to communicate with the API
     * @param   string      $url   the url of the API and any relevant parameters
     *
     * @return stdClass|null
     */
    private static function getRecord(CurlHandle $curl, string $url): null|stdClass
    {
        curl_setopt($curl, CURLOPT_URL, $url);

        if (!$record = self::getResult($curl)) {
            return null;
        }

        return json_decode($record);
    }

    /**
     * Retrieves multiple records from the converis API.
     *
     * @param   CurlHandle  $curl   the curl handle used to communicate with the API
     * @param   string      $url    the url of the API and any relevant parameters unrelated to pagination
     * @param   bool        $break  whether the process should stop at a break point or continue
     *
     * @return array the JSON decoded records delivered by the API
     */
    private static function getRecords(CurlHandle $curl, string $url, bool $break = false): array
    {
        $count   = 50;
        $queries = 0;
        $records = [];
        $start   = 1;

        // Case handling for URLs with additional parameters through link entities
        if (str_contains($url, '?')) {
            if (!str_ends_with($url, '&')) {
                $url .= '&';
            }
        }
        else {
            $url .= '?';
        }

        do {
            curl_setopt($curl, CURLOPT_URL, $url . "count=$count&startRecord=$start");

            if ($queries and $queries % 10 === 0) {
                if ($break) {
                    return $records;
                }
                sleep(1);
            }

            if (!$set = self::getResult($curl)) {
                if ($records) {
                    $last = $start - 1;
                    Application::message("Throttling has limited the results to $last records.", Application::INFO);
                }
                return $records;
            }

            $queries++;

            // Record set prefaced by metadata
            $set = json_decode($set);

            if (empty($set->data)) {
                Application::message('Converis result set is empty.', Application::INFO);
                return $records;
            }

            $start += $count;
            $end   = $set->nrRecordsAll;

            $records = array_merge($records, $set->data);
        }
        while ($start <= $end);

        return $records;
    }

    private static function getResult(CurlHandle $curl): bool|string
    {
        // This makes the result the actual result and not a boolean commentary on the queries' success.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (!$set = curl_exec($curl)) {
            Application::message('Connection to Converis failed.', Application::ERROR);
            return '';
        }

        return $set;
    }

    public static function getTestResults(): void
    {
        $curl = curl_init();
        if (!self::setCredentials($curl)) {
            return;
        }

        if (!$url = self::getURL()) {
            return;
        }

        $IDMap      = self::getUsers(true);
        $attributes = Cards::getAttributes();
        $cardURL    = Persons::QUERIES[Persons::CARDS];

        $headers               = self::HEADERS;
        $headers['attributes'] = str_replace('ALL', $attributes, $headers['attributes']);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        foreach ($IDMap as $converisID) {
            if ($converisID !== 2483544) {
                continue;
            }
            $thisURL = $url . sprintf($cardURL, $converisID);
            $cards   = self::getRecords($curl, $thisURL);
            echo "<pre>" . print_r($cards, true) . "</pre>";
        }

        //$url = $url . self::CONFIGURATIONS[self::ASSOCIATION_CONFIGURATIONS];
        //$url = $url . $entity['all'];

        //$results = self::getRecords($curl, $url, true);

        /*foreach ($results as $result) {
        }*/
        //echo "<pre>" . print_r(json_encode($results), true) . "</pre>";
        die;
    }

    private static function getUsers(bool $resolved): array
    {
        $db        = Application::database();
        $condition = $db->quoteName('m.user_id') . ' = ' . $db->quoteName('u.id');
        $query     = $db->getQuery(true);

        $query->select('DISTINCT ' . $db->quoteName('u') . '.*')
            ->from($db->quoteName('#__users', 'u'))
            ->join('inner', $db->quoteName('#__user_usergroup_map', 'm'), $condition)
            ->whereNotIn($db->quoteName('m.group_id'), Groups::DEFAULT);

        $where = $db->quoteName('u.converisID');
        $where .= $resolved ? ' IS NOT NULL' : ' IS NULL';
        $query->where($where);

        $db->setQuery($query);

        return $resolved ? $db->loadAssocList('username', 'converisID') : $db->loadAssocList('username', 'id');
    }

    private static function processPersons(array $users, array $persons): void
    {
        foreach ($persons as $pIndex => $person) {
            // No data to process
            if (empty($person->attributes)) {
                unset($persons[$pIndex]);
                continue;
            }

            $personID = $person->id;

            foreach ($person->attributes as $attribute) {
                // Irrelevant attribute
                if (!$aName = $attribute->attributeName or $aName !== 'thmLogin') {
                    continue;
                }

                // Irrelevant person
                if (!$userName = $attribute->value or !array_key_exists($userName, $users)) {
                    unset($persons[$pIndex]);
                    continue 2;
                }

                $userID = $users[$userName];
                $table  = new Users();
                $table->load($userID);
                $table->converisID = $personID;
                if (!$table->store()) {
                    echo "<pre>" . print_r($table->getErrors(), true) . "</pre>";
                    die;
                }
            }
        }
    }

    private static function setCredentials(CurlHandle $curl): bool
    {
        $params = Input::getParams();

        if (!$key = $params->get('fisKey') or !$token = $params->get('fisToken')) {
            Application::message('Converis credentials have not been configured.', Application::WARNING);
            return false;
        }

        curl_setopt($curl, CURLOPT_PASSWORD, $token);
        curl_setopt($curl, CURLOPT_USERNAME, $key);

        return true;
    }
}