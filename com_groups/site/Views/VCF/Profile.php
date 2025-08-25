<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

namespace THM\Groups\Views\VCF;

use Joomla\CMS\MVC\View\AbstractView;
use THM\Groups\Helpers\{Profiles as Helper, Users};
use THM\Groups\Adapters\Application;
use THM\Groups\Adapters\Document;
use THM\Groups\Adapters\Input;

/**
 * VCF Profile View
 */
class Profile extends AbstractView
{
    /** @inheritDoc */
    public function display($tpl = null): void
    {
        if (!$profileID = Input::integer('profileID') and !Users::published($profileID)) {
            Application::error(404);
        }

        $addressIdentifiers   = ['ADDRESS', 'ADRESSE', 'ANSCHRIFT'];
        $address              = '';
        $cellIdentifiers      = ['CELL PHONE', 'HANDY', 'MOBILE'];
        $cell                 = '';
        $fax                  = '';
        $fixedAttributes      = [EMAIL_ATTRIBUTE, FORENAME, POSTTITLE, SURNAME, TITLE];
        $homepage             = '';
        $hpIdentifiers        = ['HOMEPAGE', 'WEBPAGE', 'WEBSEITE', 'WEB'];
        $image                = '';
        $officeIdentifiers    = ['BÜRO', 'OFFICE', 'RAUM'];
        $office               = '';
        $profile              = Helper::raw($profileID);
        $telephoneIdentifiers = ['TELEFON', 'TELEPHONE'];
        $telephone            = '';
        foreach ($profile as $attributeID => $attribute) {

            $fieldID = $attribute['fieldID'];

            // Fixed attributes can be accessed directly and file attributes are irrelevant
            if (in_array($attributeID, $fixedAttributes) or $fieldID == FILE) {
                continue;
            }

            $ucLabel = strtoupper($attribute['label']);

            switch ($fieldID) {
                case EDITOR:
                    if (in_array($ucLabel, $addressIdentifiers) and empty($address)) {
                        $address = $this->cleanAddress($attribute['value']);
                    }
                    break;
                case TELEPHONE:
                    if (in_array($ucLabel, $cellIdentifiers) and empty($cell)) {
                        $cell = $attribute['value'];
                    }

                    if ($ucLabel === 'FAX' and empty($fax)) {
                        $fax = $attribute['value'];
                    }

                    if (in_array($ucLabel, $telephoneIdentifiers) and empty($telephone)) {
                        $telephone = $attribute['value'];
                    }
                    break;
                case TEXT:
                    if (in_array($ucLabel, $officeIdentifiers) and empty($office)) {
                        $office = $attribute['value'];
                        continue 2;
                    }
                    break;
                case URL:
                    if (in_array($ucLabel, $hpIdentifiers) and empty($homepage)) {
                        $homepage = $attribute['value'];
                        continue 2;
                    }
                    break;
            }
        }

        $addressText = (!empty($address) and !empty($office)) ? "$office\\n$address" : "$office$address";
        $cardName    = Helper::name($profileID);
        $email       = (!empty($profile[EMAIL_ATTRIBUTE]) and !empty($profile[EMAIL_ATTRIBUTE]['value'])) ?
            $profile[EMAIL_ATTRIBUTE]['value'] : '';
        $forenames   = (!empty($profile[FORENAME]) and !empty($profile[EMAIL_ATTRIBUTE]['value'])) ?
            explode(' ', $profile[FORENAME]['value']) : [];
        $forename    = count($forenames) ? array_shift($forenames) : '';
        $middleNames = implode(' ', $forenames);
        $title       = empty($profile[TITLE]['value']) ? '' : $profile[TITLE]['value'];
        $title       .= empty($profile[POSTTITLE]['value']) ? '' : $profile[POSTTITLE]['value'];

        Document::mime('text/directory');

        $headerValue = 'attachment; filename="' . $cardName . '.vcf"';
        Application::header('Content-disposition', $headerValue, true);

        $vcard   = [];
        $vcard[] .= 'BEGIN:VCARD';
        $vcard[] .= 'VERSION:3.0';
        $vcard[] = "N:{$profile[SURNAME]['value']};$forename;$middleNames";
        $vcard[] = "FN:$cardName";
        $vcard[] = "TITLE:$title";
        $vcard[] = "PHOTO;$image";
        $vcard[] = "TEL;TYPE=WORK,VOICE:$telephone";
        $vcard[] = "TEL;TYPE=WORK,FAX:$fax";
        $vcard[] = "TEL;TYPE=WORK,MOBILE:$cell";
        $vcard[] = "ADR;TYPE=WORK:;;$addressText;;;;";
        $vcard[] = "LABEL;TYPE=WORK:$addressText";
        $vcard[] = "EMAIL;TYPE=PREF,INTERNET:$email";
        $vcard[] = "URL:$homepage";
        $vcard[] = 'REV:' . date('c') . 'Z';
        $vcard[] = 'END:VCARD';

        echo implode("\n", $vcard);
    }

    /**
     * Cleans the address of html and superfluous white space.
     *
     * @param   string  $address  the address to be cleaned
     *
     * @return string the cleaned address
     */
    private function cleanAddress(string $address): string
    {
        $address      = str_replace(['<br>', '<br/>', '<br />', '</p><p>'], "XXX", $address);
        $address      = strip_tags($address);
        $address      = preg_replace("/\n/", "XXX", $address);
        $address      = preg_replace("/ +/", " ", $address);
        $addressParts = explode("XXX", $address);
        foreach ($addressParts as &$addressPart) {
            $addressPart = trim($addressPart);
        }

        return implode("\\n", array_filter($addressParts));
    }
}
