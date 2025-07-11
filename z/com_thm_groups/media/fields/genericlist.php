<?php
/**
 * @package     THM_Groups
 * @extension   com_thm_groups
 * @author      James Antrim, <james.antrim@mni.thm.de>
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */

use Joomla\CMS\Form\Field\ListField;
use Joomla\Database\DatabaseQuery;
use THM\Groups\Adapters\{Application, Database as DB, HTML, Input};

/**
 * Class loads a list of fields for selection
 */
class JFormFieldGenericList extends ListField
{
    /**
     * Type
     *
     * @var    String
     */
    public $type = 'genericlist';

    /**
     * Resolves the textColumns for concatenated values
     *
     * @param   DatabaseQuery  $query  the query object
     *
     * @return  void
     */
    private function from(DatabaseQuery $query): void
    {
        $tables = $this->getAttribute('table');
        $tables = explode(',', $tables);

        // Plain text in form manifest => name quoting isn't cost-efficient
        $query->from("#__$tables[0]");
        $count = count($tables);

        if ($count === 1) {
            return;
        }

        for ($index = 1; $index < $count; $index++) {
            $query->innerjoin("#__$tables[$index]");
        }
    }

    /**
     * Method to get the options based upon information held in the database
     *
     * @return  array  The field option objects.
     */
    protected function getOptions(): array
    {
        $query = DB::query();

        $default = parent::getOptions();
        $glue    = $this->getAttribute('glue', '');
        $text    = $this->resolveText($query);
        $value   = $this->getAttribute('valueColumn');

        $query->select(['DISTINCT ' . DB::qn($value, 'value'), $text . ' AS ' . DB::qn('text')])->order(DB::qn('text'));
        $this->from($query);
        $this->setWhere($query);
        DB::set($query);

        $options = [];
        foreach (DB::arrays() as $resource) {
            // Removes glue from the end of entries
            if ($glue and strpos($resource['text'], $glue) === strlen($resource['text']) - strlen($glue)) {
                $resource['text'] = str_replace($glue, '', $resource['text']);
            }

            $options[$resource['text']] = JHtml::_('select.option', $resource['value'], $resource['text']);
        }

        $this->setValueParameters($options);

        return array_merge($default, $options);
    }

    /**
     * Resolves the textColumns for concatenated values
     *
     * @param   DatabaseQuery  $query  the query object
     *
     * @return  string
     */
    private function resolveText(DatabaseQuery $query): string
    {
        $glue      = $this->getAttribute('glue');
        $localized = $this->getAttribute('localized');
        $texts     = $this->getAttribute('textColumn');
        $tag       = Application::tag();
        $texts     = explode(',', $texts);

        foreach ($texts as $key => $value) {
            $texts[$key] = $localized ? DB::qn($value . '_' . $tag) : DB::qn($value);
        }

        if (count($texts) === 1 or empty($glue)) {
            return $texts[0];
        }

        return '( ' . $query->concatenate($texts, $glue) . ' )';
    }

    /**
     * Applies restrictions
     *
     * @param   DatabaseQuery  $query  the query object
     *
     * @return  void modifies the query object
     */
    private function setWhere(DatabaseQuery $query): void
    {
        $whereParameters = $this->getAttribute('restriction');
        if (empty($whereParameters)) {
            return;
        }

        $restrictions = explode(';', $whereParameters);
        if (empty($restrictions)) {
            return;
        }

        foreach ($restrictions as $restriction) {
            $query->where($restriction);
        }
    }

    /**
     * Sets value oriented parameters from component settings
     *
     * @param   array &$options  the input options
     *
     * @return  void  sets option values
     */
    private function setValueParameters(array &$options): void
    {
        if (!$fParameters = $this->getAttribute('valueParameter')) {
            return;
        }

        $cParameters = Input::parameters();
        foreach (explode(',', $fParameters) as $fParameter) {
            if (!$cParameter = $cParameters->get($fParameter)) {
                continue;
            }
            $options[$cParameter] = HTML::option($cParameter, $cParameter);
        }

        ksort($options);
    }
}
