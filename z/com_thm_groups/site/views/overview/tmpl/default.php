<?php
/**
 * @package     Groups
 * @extension   com_groups
 * @copyright   2018 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.thm.de
 */


$currentColumns     = 1;
$currentRowCount    = 1;
$lpCount            = 0;
$tolerance          = $this->columnCount;
$totalLettersOutput = 0;
$totalRowsOutput    = 0;

echo $this->getHeaderImage();
?>
<div class="thm_groups-overview">
    <?php if ($this->params->get('show_page_heading') or empty($this->params->get('groupID'))) : ?>
        <div class="page-header">
            <h2 itemprop="headline">
                <?php echo $this->escape($this->title); ?>
            </h2>
        </div>
    <?php endif; ?>
    <div class="overview-container">
        <div class="profiles-container">
            <?php
            foreach ($this->profiles as $letter => $letterProfiles) {
                foreach ($letterProfiles as $profile) {
                    // Define structure conditionals
                    $closeColumn = false;
                    $continue    = true;
                    $newColumn   = $currentRowCount === 1;
                    $newLetter   = $lpCount === 0;
                    $openLetter  = ($newColumn or $newLetter);

                    // Incremental conditionals
                    $lpCount++;
                    $currentRowCount++;
                    $totalRowsOutput++;

                    // After actions conditionals
                    if ($maxRowsReached = $this->maxColumnSize === $currentRowCount) {
                        $continue = false;
                    }

                    $rowsAvailable = $this->maxColumnSize - $currentRowCount;

                    // Look ahead
                    if ($letterDone = $lpCount == count($letterProfiles)) {
                        $lpCount = 0;
                        $totalLettersOutput++;
                        $outStandingLetters = array_slice($this->profiles, $totalLettersOutput, 1);

                        if ($next = array_shift($outStandingLetters)) {
                            $nextCount    = count($next);
                            $fitsNeatly   = ($nextCount and $nextCount <= $rowsAvailable);
                            $breaksNeatly = ($nextCount and $nextCount >= $tolerance * 2);

                            $neat     = ($fitsNeatly or $breaksNeatly);
                            $neat     = ($neat and $rowsAvailable >= $tolerance and !$maxRowsReached);
                            $forced   = $currentColumns === $this->columnCount;
                            $continue = ($neat or $forced);
                        }
                        else {
                            $continue = false;
                        }
                    }

                    $closeProfiles = ($letterDone or $maxRowsReached);
                    $done          = $totalLettersOutput == count($this->profiles);

                    if ($closeColumn = ($done or !$continue)) {
                        $currentRowCount = 1;
                    }

                    if ($newColumn) {
                        echo '<div class="ym-g33 ym-gl">';
                    }

                    if ($openLetter) {
                        echo "<div class=\"letter\">$letter</div><div class=\"profiles\"><ul>";
                    }

                    echo '<li>' . $this->getProfileLink($profile->id) . '</li>';

                    if ($closeProfiles) {
                        echo "</ul></div>";
                    }

                    if ($closeColumn) {
                        echo "</div>";
                    }
                }
            }
            ?>
        </div>
    </div>
</div>