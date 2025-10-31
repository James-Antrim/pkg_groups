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

use Joomla\{Filter\OutputFilter, String\StringHelper};
use THM\Groups\Adapters\{Application, Input};
use THM\Groups\Tables\Content as Table;

/** @inheritDoc */
class Content extends FormController
{
    protected string $list = 'Contents';

    /** @inheritDoc */
    protected function prepareData(): array
    {
        $configuration = Application::configuration();
        $data          = Input::post();
        $task          = Input::task();

        // Alter the title for save as copy
        if ($task === 'save2copy') {
            $original = new Table();
            $original->load(Input::id());

            if ($data['title'] === $original->title) {
                [$title, $alias] = $this->newTitles($data['catid'], $data['alias'], $data['title']);
                $data['title'] = $title;
                $data['alias'] = $alias;
            }
            elseif ($data['alias'] === $original->alias) {
                $data['alias'] = '';
            }
        }
        elseif (empty($data['id']) and empty($data['alias'])) {

            $data['alias'] = $configuration->get('unicodeslugs') ?
                OutputFilter::stringUrlUnicodeSlug($data['title']) : OutputFilter::stringUrlSafe($data['title']);

            $table = new Table();

            if ($table->load(['alias' => $data['alias'], 'catid' => $data['catid']])) {
                Application::message('ALIAS_EXISTS', Application::WARNING);
            }

            $titles = $this->newTitles($data['catid'], $data['alias'], $data['title']);

            $data['alias'] = end($titles);
        }

        return $data;
    }

    /**
     * Increments the title and alias as necessary within a category context
     *
     * @param   int     $categoryID  the id of the category providing context for incrementation
     * @param   string  $alias
     * @param   string  $title
     *
     * @return array
     */
    private function newTitles(int $categoryID, string $alias, string $title): array
    {
        $table = new Table();
        while ($table->load(['alias' => $alias, 'catid' => $categoryID])) {
            if ($title === $table->title) {
                $title = StringHelper::increment($title);
            }

            $alias = StringHelper::increment($alias, 'dash');
        }

        return [$title, $alias];
    }
}
/*
<dt>articletext</dt>
<dd><p><strong class="welt:font-extrabold">Liebe Absolventinnen und Absolventen,</strong></p>
<p>herzlichen Glückwunsch zu Ihrem erfolgreichen Studienabschluss! <br>Der Fachbereich LSE möchte Ihnen persönlich zu diesem bedeutenden Meilenstein gratulieren und lädt Sie herzlich zur feierlichen Abschlussveranstaltung ein.</p>
<p><strong class="welt:font-extrabold">Wann?</strong> Freitag, 17. Oktober 2025, 14:00 bis 18:30 Uhr , D-Campus, THM, Gießen</p>
<p>Es ist geplant, die persönliche Ansprache an die Absolventinnen und Absolventen semesterweise durchzuführen:</p>
<ul>
<li>Wintersemester 2023/24</li>
<li>Sommersemester 2024</li>
<li>Wintersemester 2024/25</li>
<li>Sommersemester 2025</li>
</ul>
<p>Diese besondere Feier bietet Ihnen die Gelegenheit, gemeinsam mit ehemaligen Kommilitoninnen und Kommilitonen, Professorinnen und Professoren und Mitarbeitenden Erinnerungen auszutauschen und Kontakte zu pflegen.</p>
<p>Wir freuen uns darauf, Sie – gerne in Begleitung von bis zu drei Personen – begrüßen zu dürfen.<br>Bitte melden Sie sich - verbindlich - bis spätestens 19. September 2025 an (<a href="#formular">Online Formular</a>). Später eingehende Anmeldungen können leider nicht mehr berücksichtigt werden.&nbsp;<span>Bitte beachten Sie, dass die Teilnehmeranzahl begrenzt ist.</span></p>
<p>Für Ihr leibliches Wohl, Imbiss und alkoholfreie Getränke, ist gesorgt, alkoholische Getränke sind selbst zu zahlen.</p>
<p>Ihre Fachbereichsleitung LSE</p>
<p>&nbsp;--------------------------------------------------------------------------------------------------------------</p>
<h4>Programm</h4>
<p><strong>Einlass und Sektempfang ab 14:00 Uhr<br></strong><strong>Beginn Feierlichkeiten 15:00 Uhr</strong></p>
<ul>
<li>Sektempfang</li>
<li>Begrüßung durch das Präsidium und den Dekan</li>
<li>Ansprache&nbsp;an die Absolventinnen und Absolventen</li>
<li>Verleihung IMPS-Preis</li>
<li>Geselliges Beisammensein</li>
</ul>
<h4>Kosten</h4>
<p>Für Absolventinnen und Absolventen ist die Teilnahme kostenfrei. Weitere Begleitpersonen sind herzlich willkommen. Wir bitten um Verständnis dafür, dass wir für jede Begleitperson einen Beitrag in Höhe von 15 € erheben werden, der vor Ort beim Einlass zu zahlen ist.</p>
<p>&nbsp;----------------------------------------------------------------------------------------------------------------</p>
<p id="formular">{vfformview}{"formid":"1"}{/vfformview}</p></dd>
<dt>transition</dt>
<dd></dd>
<dt>state</dt>
<dd>1</dd>
<dt>catid</dt>
<dd>8</dd>
<dt>featured</dt>
<dd>0</dd>
<dt>access</dt>
<dd>1</dd>
<dt>language</dt>
<dd>*</dd>
<dt>note</dt>
<dd></dd>
<dt>version_note</dt>
<dd></dd>
<dt>images</dt>
<dd>Array
(
    [image_intro] =>
    [image_intro_alt] =>
    [float_intro] =>
    [image_intro_caption] =>
    [image_fulltext] =>
    [image_fulltext_alt] =>
    [float_fulltext] =>
    [image_fulltext_caption] =>
)
</dd>
<dt>attribs</dt>
<dd>Array
(
    [article_layout] =>
    [show_title] =>
    [link_titles] =>
    [show_tags] =>
    [show_intro] =>
    [info_block_position] =>
    [info_block_show_title] =>
    [show_category] =>
    [link_category] =>
    [show_parent_category] =>
    [link_parent_category] =>
    [show_associations] =>
    [flags] =>
    [show_author] =>
    [link_author] =>
    [show_create_date] =>
    [show_modify_date] =>
    [show_publish_date] =>
    [show_item_navigation] =>
    [show_vote] =>
    [show_hits] =>
    [show_noauth] =>
    [urls_position] =>
    [alternative_readmore] =>
    [article_page_title] =>
    [show_publishing_options] =>
    [show_article_options] =>
    [show_urls_images_backend] =>
    [show_urls_images_frontend] =>
)
</dd>
<dt>schema</dt>
<dd>Array
(
    [extendJed] =>
)
</dd>
<dt>publish_up</dt>
<dd>05.08.2025 12:20:03</dd>
<dt>publish_down</dt>
<dd></dd>
<dt>featured_up</dt>
<dd></dd>
<dt>featured_down</dt>
<dd></dd>
<dt>created</dt>
<dd>28.07.2025 13:46:41</dd>
<dt>created_by</dt>
<dd>65</dd>
<dt>created_by_alias</dt>
<dd></dd>
<dt>modified</dt>
<dd>21.08.2025 10:14:49</dd>
<dt>version</dt>
<dd>70</dd>
<dt>hits</dt>
<dd>785</dd>
<dt>id</dt>
<dd>750</dd>
<dt>metadesc</dt>
<dd></dd>
<dt>metakey</dt>
<dd></dd>
<dt>metadata</dt>
<dd>Array
(
    [robots] =>
    [author] =>
    [rights] =>
)
</dd>
<dt>associations</dt>
<dd>Array
(
    [en-GB] =>
    [de-DE] =>
)
</dd>
<dt>rules</dt>
<dd>Array
(
    [core.delete] => Array
        (
            [1] =>
            [9] =>
            [6] =>
            [7] =>
            [2] =>
            [3] =>
            [4] =>
            [5] =>
            [36] =>
            [11] =>
            [12] =>
            [29] =>
            [25] =>
            [17] =>
            [18] =>
            [39] =>
            [40] =>
            [19] =>
            [13] =>
            [43] =>
            [42] =>
            [41] =>
            [14] =>
            [16] =>
            [28] =>
            [23] =>
            [24] =>
            [20] =>
            [22] =>
            [27] =>
            [26] =>
            [31] =>
            [30] =>
            [15] =>
            [8] =>
        )

    [core.edit] => Array
        (
            [1] =>
            [9] =>
            [6] =>
            [7] =>
            [2] =>
            [3] =>
            [4] =>
            [5] =>
            [36] =>
            [11] =>
            [12] =>
            [29] =>
            [25] =>
            [17] =>
            [18] =>
            [39] =>
            [40] =>
            [19] =>
            [13] =>
            [43] =>
            [42] =>
            [41] =>
            [14] =>
            [16] =>
            [28] =>
            [23] =>
            [24] =>
            [20] =>
            [22] =>
            [27] =>
            [26] =>
            [31] =>
            [30] =>
            [15] =>
            [8] =>
        )

    [core.edit.state] => Array
        (
            [1] =>
            [9] =>
            [6] =>
            [7] =>
            [2] =>
            [3] =>
            [4] =>
            [5] =>
            [36] =>
            [11] =>
            [12] =>
            [29] =>
            [25] =>
            [17] =>
            [18] =>
            [39] =>
            [40] =>
            [19] =>
            [13] =>
            [43] =>
            [42] =>
            [41] =>
            [14] =>
            [16] =>
            [28] =>
            [23] =>
            [24] =>
            [20] =>
            [22] =>
            [27] =>
            [26] =>
            [31] =>
            [30] =>
            [15] =>
            [8] =>
        )

)
</dd></dl>
*/