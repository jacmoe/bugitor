<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2011 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php $versions = $this->getVersions(); ?>
<?php $identifier = $this->getIdentifier(); ?>
<?php $show_detail = $this->getDetailview(); ?>
<?php foreach($versions as $version) : ?>
    <?php $show_version = $completed = false;
    if (strtotime($version->effective_date) >= strtotime(date("Y-m-d"))) {
        // if effective date is in the future..
        if($version->issueCountOpen < 1) {
            $completed = false;
            $show_version = true;
        } else {
            $completed = false;
            $show_version = true;
        }
    } elseif (strtotime($version->effective_date) < strtotime(date("Y-m-d"))) {
        // if effective date is in the past..
        if($version->issueCountOpen < 1) {
            $show_version = false;
            $completed = true;
        } else {
            $show_version = true;
            $completed = false;
        }
    }
    ?>
    <?php //TODO: Yii::app()->user->setState('show_all_versions', false); ?>
    <?php if($this->getShowAllOverride()) $show_version = true; ?>
    <?php if($show_version): ?>
        <h3>
            <?php echo CHtml::link($version->name,
                array('version/view',
                    'id' => $version->id,
                    'identifier' => $identifier,
                )
            ); ?>
        </h3>
        <b>Deadline</b> (<?php echo $version->effective_date; ?>) - <span class="quiet"><?php echo ($completed) ? 'Completed' : Time::dueDateInWords($version->effective_date) ?></span><br/>
        <?php if( $version->issueCount > 0 ) : ?>
            <?php $num_actual_issues = $version->issueCount - $version->issueCountRejected; ?>
            <?php $open_percent = (($version->issueCountOpen / $num_actual_issues)*100); ?>
            <?php $closed_percent = (($version->issueCountResolved / $num_actual_issues)*100); ?>
            <?php $done_ratio = ((($version->issueCountDone / 100) * $version->issueCountOpen) / $num_actual_issues) * 100; ?>
            <?php $open_ratio = $open_percent - $done_ratio; ?>
            <?php if($show_detail) : ?>
                <?php echo Bugitor::big_progress_bar(array($closed_percent, $done_ratio, $open_ratio), array('width' => '500px', 'legend' => number_format($closed_percent + $done_ratio) . '%')); ?><br/>
                <p class="big_progress-info quiet">
                <?php if($version->issueCountResolved > 0) : ?>
                    <?php echo CHtml::link($version->issueCountResolved. ' ' . Yii::t('Bugitor','closed'),
                        array('issue/index', 'identifier' => $identifier,
                            'Issue[status]' => 'swIssue/resolved',
                            'Issue[version_id]' => $version->name,
                            'issueFilter' => 2,
                        )); ?> (<?php echo number_format($closed_percent) ?>%)
                <?php else : ?>
                    0 closed
                <?php endif; ?>
                <?php if($version->issueCountOpen > 0) : ?>
                    <?php echo CHtml::link($version->issueCountOpen. ' ' . Yii::t('Bugitor','open'),
                        array('issue/index', 'identifier' => $identifier,
                            'Issue[version_id]' => $version->name,
                    )); ?> (<?php echo number_format($open_percent) ?>%)
                <?php else : ?>
                     0 open
                <?php endif; ?>
                <?php if($version->issueCountRejected > 0) : ?>
                    <?php echo CHtml::link($version->issueCountRejected. ' ' . Yii::t('Bugitor','rejected'),
                        array('issue/index', 'identifier' => $identifier,
                            'Issue[status]' => 'swIssue/rejected',
                            'Issue[version_id]' => $version->name,
                            'issueFilter' => 2,
                    )); ?>
                <?php else : ?>
                     0 rejected
                <?php endif; ?>
                </p>
            <?php else : ?>
                <?php echo Bugitor::small_progress_bar(array($closed_percent, $done_ratio, $open_ratio), array('width' => '250px', 'legend' => number_format($closed_percent + $done_ratio) . '%')); ?><br/>
            <?php endif; ?>
            <?php if($show_detail) : ?>
                <?php echo Yii::app()->textile->textilize($version->description); ?>
                    <?php if($this->controller->id === 'project') : ?>
                        <fieldset id="version_related_<?php echo $version->id ?>_fieldset" class="collapsible collapsed related-issues">
                        <legend onclick="$('#description_row').toggle();$('#version_related_issues_<?php echo $version->id ?>').toggle();$('#version_related_<?php echo $version->id ?>_fieldset').toggleClass('collapsed')">
                        <?php echo 'Related issues'; ?><small> (Click to toggle)</small></legend>
                        <div class="issues" id="version_related_issues_<?php echo $version->id ?>" style="display: none;">
                    <?php else : ?>
                        <fieldset class="related-issues">
                        <legend><?php echo 'Related issues'; ?></legend>
                        <div class="issues">
                    <?php endif; ?>
                    <ul>
                        <?php foreach($version->issues as $issue) : ?>
                            <li>
                                <?php echo Bugitor::short_link_to_issue($issue) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    </div>
                    </fieldset>
            <?php endif; ?>
        <?php else : ?>
            <p class="nodata"><?php echo 'No issues for this version'; ?></p>
        <?php endif; // is issues ?>
    <?php endif; // if show version ?>
<?php endforeach; ?>
