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
<?php $ver_count = 0; ?>
<?php foreach($versions as $version) : ?>
    <?php $show_version = $completed = false;
    if (strtotime($version->effective_date) >= strtotime(date("Y-m-d"))) {
        // if effective date is in the future..
        $completed = false;
        $show_version = true;
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
    <?php if(isset($_GET['showcompleted'])) $show_version = true; ?>
    <?php if((isset($_GET['id'])) && ($this->controller->id === 'version')) $show_version = true; ?>
    <?php if($show_version): ?>
    <?php if(!$show_detail) {
            $ver_count++;
            if($ver_count > 2) break;
    } ?>
        <h3>
            <?php echo CHtml::link($version->name. ' - ' . $version->title,
                array('version/view',
                    'id' => $version->id,
                    'identifier' => $identifier,
                )
            ); ?>
        </h3>
        <b>Deadline</b> (<?php echo Yii::app()->dateFormatter->formatDateTime($version->effective_date, 'medium', null); ?>) - <span class="quiet"><?php echo ($completed) ? 'Completed' : Time::dueDateInWords($version->effective_date) ?></span>
        <br/>
        <?php if( $version->issueCount > 0 ) : ?>
            <?php $num_actual_issues = $version->issueCount - $version->issueCountRejected; ?>
            <?php $open_percent = (($num_actual_issues != 0) ? (($version->issueCountOpen / $num_actual_issues)*100) : 0); ?>
            <?php $closed_percent = (($num_actual_issues != 0) ? (($version->issueCountResolved / $num_actual_issues)*100) : 0); ?>
            <?php $done_ratio = (($num_actual_issues != 0) ? ((($version->issueCountDone / 100) * $version->issueCountOpen) / $num_actual_issues) * 100 : 0); ?>
            <?php $open_ratio = $open_percent - $done_ratio; ?>
            <?php if($show_detail) : ?>
                <?php echo Bugitor::big_progress_bar(array($closed_percent, $done_ratio, $open_ratio), array('width' => '500px', 'legend' => number_format($closed_percent + $done_ratio) . '%')); ?><br/>
                <div class="big_progress-info quiet">
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
                </div>
                <?php echo Yii::app()->textile->textilize($version->description); ?>
            <?php else : ?>
                <?php echo Bugitor::small_progress_bar(array($closed_percent, $done_ratio, $open_ratio), array('width' => '250px', 'legend' => number_format($closed_percent + $done_ratio) . '%')); ?><br/><br/>
            <?php endif; ?>
                    <?php if($this->controller->id === 'version') : ?>
                        <?php if(Yii::app()->user->checkAccess('Version.Update')) : ?>
                            <?php echo CHtml::link('Edit Version',array('version/update','id'=>$version->id, 'identifier' => $_GET['identifier'])); ?><br/><br/>
                        <?php endif; ?>
                        <fieldset class="related-issues">
                        <legend><?php echo 'Related issues'; ?></legend>
                        <div class="issues">
                    <ul>
                        <?php foreach($version->issues as $issue) : ?>
                            <li>
                                <?php echo Bugitor::progress_bar($issue->done_ratio, array("width"=>"60px;"));?> <?php echo Bugitor::short_link_to_issue($issue) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    </div>
                    </fieldset>
                <?php endif; ?>
        <?php else : ?>
            <?php if($show_detail) : ?>
                <?php echo Yii::app()->textile->textilize($version->description); ?>
                <?php if(($this->controller->id === 'version')&&(Yii::app()->user->checkAccess('Version.Update'))) : ?>
                    <?php echo CHtml::link('Edit Version',array('version/update','id'=>$version->id, 'identifier' => $_GET['identifier'])); ?><br/><br/>
                <?php endif; ?>
            <?php else : ?>
                <p></p>
            <?php endif; ?>
            <?php if($show_detail) : ?>
                <p class="nodata" style="width: 500px;"><?php echo 'No issues for this version'; ?></p>
            <?php else : ?>
                <p class="nodata" style="width: 250px;"><?php echo 'No issues for this version'; ?></p>
            <?php endif; ?>
        <?php endif; // is issues ?>
    <?php endif; // if show version ?>
<?php endforeach; ?>
