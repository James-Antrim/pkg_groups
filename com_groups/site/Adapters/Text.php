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

/**
 * Class handles localization resolution.
 */
class Text extends Base
{

    /**
     * Translates a string into the current language.
     *
     * @param   string  $string                The string to translate.
     * @param   mixed   $jsSafe                Boolean: Make the result javascript safe.
     * @param   bool    $interpretBackSlashes  To interpret backslashes (\\=\, \n=carriage return, \t=tabulation)
     * @param   bool    $script                To indicate that the string will be push in the javascript language store
     *
     * @return  string  The translated string or the key if $script is true
     * @noinspection PhpMethodNamingConventionInspection
     */
    public static function _($string, $jsSafe = false, $interpretBackSlashes = true, $script = false): string
    {
        $string = self::prefaceKey($string);

        if ($script) {
            return self::useLocalization($string);
        }

        return Application::getLanguage()->_($string, $jsSafe, $interpretBackSlashes);
    }

    /**
     * Translate a string into the current language and stores it in the JavaScript language store.
     *
     * @param   string  $key  the localization key
     *
     * @return  array the current localizations queued for use in script
     */
    public static function addLocalization(string $key): array
    {
        $key = self::prefaceKey($key);
        $key = strtoupper($key);

        // Normalize the key and translate the string.
        static::$strings[$key] = Application::getLanguage()->_($key);

        // Load core.js dependency
        HTML::_('behavior.core');

        // Update Joomla.Text script options
        Document::addScriptOptions('joomla.jtext', static::$strings, false);

        return static::getScriptStrings();
    }

    /**
     * Filters texts to their alphabetic or alphanumeric components
     *
     * @param   string  $text  the text to be filtered
     * @param   string  $type  the way in which the text should be filtered
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
     * Supplements a non-prefaced key as necessary.
     *
     * @param   string  $key
     *
     * @return string the resolved localization
     */
    private static function prefaceKey(string $key): string
    {
        preg_match('/^([A-Z_]+|\d{3})$/', $key, $matches);
        $isKey = !empty($matches);

        // The key is in fact a localization key and the component preface is missing.
        if ($isKey and !str_starts_with($key, 'GROUPS_')) {
            $key = "GROUPS_$key";
        }

        return $key;
    }

    /**
     * @inheritdoc
     * Two Joomla\CMS\Language\Text exist the real one (Text.php) uses $string the dummy (finalisation.php) uses $text.
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public static function sprintf($string): string
    {
        $lang    = Application::getLanguage();
        $args    = func_get_args();
        $args[0] = $lang->_(self::prefaceKey($string));

        // Replace custom placeholders
        $args[0] = preg_replace('/\[\[%([0-9]+):[^\]]*\]\]/', '%\1$s', $args[0]);

        return call_user_func_array('sprintf', $args);
    }

    /**
     * Replaces special characters in a given string with their transliterations.
     *
     * @param   string  $text  the text to be processed
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
     * @param   string  $text  the text to be trimmed
     *
     * @return string the trimmed text
     */
    public static function trim(string $text): string
    {
        return trim(preg_replace('/ +/u', ' ', $text));
    }

    /**
     * Adds the localization as necessary and resolves the value for immediate use.
     *
     * @param   string  $key  the localization key to resolve
     *
     * @return string the resolved constant
     */
    public static function useLocalization(string $key): string
    {
        $key = self::prefaceKey($key);
        self::addLocalization($key);

        return self::_($key);
    }
}