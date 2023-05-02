<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @author      James Antrim, <james.antrim@nm.thm.de>
 * @copyright   2023 TH Mittelhessen
 * @license     GNU GPL v.3
 * @link        www.thm.de
 */

namespace THM\Groups\Adapters;

use Joomla\CMS\Language\Text as Base;

class Text extends Base
{
    /**
     * Filters texts to their alphabetic or alphanumeric components
     *
     * @param string $text the text to be filtered
     * @param string $type the way in which the text should be filtered
     *
     * @return string the filtered text
     */
    public static function filter(string $text, string $type = 'alpha'): string
    {
        $pattern = match ($type) {
            'alphanum' => '/[^\p{L}\p{N}]/',
            default => '/[^\p{L}]/',
        };

        $text = preg_replace($pattern, ' ', $text);

        // The letter filter seems to include periods
        $text = str_replace('.', '', $text);

        return self::trim($text);
    }

    /**
     * Replaces special characters in a given string with their transliterations.
     *
     * @param string $text the text to be processed
     *
     * @return string an ASCII compatible transliteration of the given string
     */
    public static function transliterate(string $text): string
    {
        // This will always be for alias related purposes => always lower case
        $text = mb_strtolower($text);

        $aSearch = ['à', 'á', 'â', 'ă', 'ã', 'å', 'ā', 'ą'];
        $text    = str_replace($aSearch, 'a', $text);

        $aeSearch = ['ä', 'æ'];
        $text     = str_replace($aeSearch, 'ae', $text);

        $cSearch = ['ć', 'č', 'ç'];
        $text    = str_replace($cSearch, 'c', $text);

        $dSearch = ['ď', 'ð'];
        $text    = str_replace($dSearch, 'd', $text);

        $eSearch = ['è', 'é', 'ê', 'ě', 'ë', 'ē', 'ę'];
        $text    = str_replace($eSearch, 'e', $text);

        $gSearch = ['ģ', 'ğ'];
        $text    = str_replace($gSearch, 'g', $text);

        $iSearch = ['ı', 'ì', 'í', 'î', 'ï', 'ī'];
        $text    = str_replace($iSearch, 'i', $text);

        $lSearch = ['ļ', 'ł'];
        $text    = str_replace($lSearch, 'l', $text);

        $text = str_replace('ķ', 'k', $text);

        $nSearch = ['ń', 'ň', 'ñ', 'ņ'];
        $text    = str_replace($nSearch, 'n', $text);

        $oSearch = ['ò', 'ó', 'ô', 'õ', 'ő', 'ø'];
        $text    = str_replace($oSearch, 'o', $text);

        $text = str_replace('ö', 'oe', $text);

        $text = str_replace('ř', 'r', $text);

        $sSearch = ['ś', 'š', 'ş', 'ș'];
        $text    = str_replace($sSearch, 's', $text);

        $text = str_replace('ß', 'ss', $text);

        $tSearch = ['ť', 'ț'];
        $text    = str_replace($tSearch, 't', $text);

        $text = str_replace('þ', 'th', $text);

        $uSearch = ['ù', 'ú', 'û', 'ű', 'ů', 'ū'];
        $text    = str_replace($uSearch, 'u', $text);

        $text = str_replace('ü', 'ue', $text);

        $ySearch = ['ý', 'ÿ'];
        $text    = str_replace($ySearch, 'y', $text);

        $zSearch = ['ź', 'ž', 'ż'];
        return str_replace($zSearch, 'z', $text);
    }

    /**
     * Removes excess space characters from a given string.
     *
     * @param string $text the text to be trimmed
     *
     * @return string the trimmed text
     */
    public static function trim(string $text): string
    {
        return trim(preg_replace('/ +/u', ' ', $text));
    }
}