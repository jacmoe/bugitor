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
 * Copyright (C) 2009 - 2013 Bugitor Team
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
<?php $milestones = $this->getMilestones(); ?>
<?php $identifier = $this->getIdentifier(); ?>
<?php $show_detail = $this->getDetailview(); ?>
<?php $ver_count = 0; ?>
<?php foreach($milestones as $milestone) : ?>
    <?php $show_milestone = $completed = false;
    if (strtotime($milestone->effective_date) >= strtotime(date("Y-m-d"))) {
        // if effective date is in the future..
        $completed = false;
        $show_milestone = true;
    } elseif (strtotime($milestone->effective_date) < strtotime(date("Y-m-d"))) {
        // if effective date is in the past..
        if($milestone->issueCountOpen < 1) {
            $show_milestone = false;
            $completed = true;
        } else {
            $show_milestone = true;
            $completed = false;
        }
    }
    ?>
    <?php if(isset($_GET['showcompleted'])) $show_milestone = true; ?>
    <?php if((isset($_GET['id'])) && ($this->controller->id === 'milestone')) $show_milestone = true; ?>
    <?php if($show_milestone): ?>
    <?php if(!$show_detail) {
            $ver_count++;
            if($ver_count > 2) break;
    } ?>
        <h3 class="roadmap-icon">
            <?php echo CHtml::link($milestone->name. ' - ' . $milestone->title,
                array('milestone/view',
                    'id' => $milestone->id,
                    'identifier' => $identifier,
                )
            ); ?>
        </h3>
        <b>Deadline</b> (<?php echo Yii::app()->dateFormatter->formatDateTime($milestone->effective_date, 'medium', null); ?>) - <span class="quiet"><?php echo ($completed) ? 'Completed' : Time::dueDateInWords($milestone->effective_date) ?></span>
        <br/>
        <?php if( $milestone->issueCount > 0 ) : ?>
            <?php $num_actual_issues = $milestone->issueCount - $milestone->issueCountRejected; ?>
            <?php $open_percent = (($num_actual_issues != 0) ? (($milestone->issueCountOpen / $num_actual_issues)*100) : 0); ?>
            <?php $closed_percent = (($num_actual_issues != 0) ? (($milestone->issueCountResolved / $num_actual_issues)*100) : 0); ?>
            <?php $done_ratio = (($num_actual_issues != 0) ? ((($milestone->issueCountDone / 100) * $milestone->issueCountOpen) / $num_actual_issues) * 100 : 0); ?>
            <?php $open_ratio = $open_percent - $done_ratio; ?>
            <?php if($show_detail) : ?>
                <?php echo Bugitor::big_progress_bar(array($closed_percent, $done_ratio, $open_ratio), array('width' => '500px', 'legend' => number_format($closed_percent + $done_ratio) . '%')); ?><br/>
                <div class="big_progress-info quiet">
                <?php if($milestone->issueCountResolved > 0) : ?>
                    <?php echo CHtml::link($milestone->issueCountResolved. ' ' . Yii::t('Bugitor','closed'),
                        array('issue/index', 'identifier' => $identifier,
                            'Issue[status]' => 'swIssue/resolved',
                            'Issue[milestone_id]' => $milestone->name,
                            'issueFilter' => 2,
                        )); ?> (<?php echo number_format($closed_percent) ?>%)
                <?php else : ?>
                    0 closed
                <?php endif; ?>
                <?php if($milestone->issueCountOpen > 0) : ?>
                    <?php echo CHtml::link($milestone->issueCountOpen. ' ' . Yii::t('Bugitor','open'),
                        array('issue/index', 'identifier' => $identifier,
                            'Issue[milestone_id]' => $milestone->name,
                    )); ?> (<?php echo number_format($open_percent) ?>%)
                <?php else : ?>
                     0 open
                <?php endif; ?>
                <?php if($milestone->issueCountRejected > 0) : ?>
                    <?php echo CHtml::link($milestone->issueCountRejected. ' ' . Yii::t('Bugitor','rejected'),
                        array('issue/index', 'identifier' => $identifier,
                            'Issue[status]' => 'swIssue/rejected',
                            'Issue[milestone_id]' => $milestone->name,
                            'issueFilter' => 2,
                    )); ?>
                <?php else : ?>
                     0 rejected
                <?php endif; ?>
                </div>
                <?php echo Yii::app()->textile->textilize($milestone->description); ?>
            <?php else : ?>
                <?php echo Bugitor::small_progress_bar(array($closed_percent, $done_ratio, $open_ratio), array('width' => '250px', 'legend' => number_format($closed_percent + $done_ratio) . '%')); ?><br/><br/>
            <?php endif; ?>
                    <?php if($this->controller->id === 'milestone') : ?>
                        <?php if(Yii::app()->user->checkAccess('Milestone.Update')) : ?>
                            <?php echo CHtml::link('Edit Milestone',array('milestone/update','id'=>$milestone->id, 'identifier' => $_GET['identifier'])); ?><br/><br/>
                        <?php endif; ?>
                        <fieldset class="related-issues">
                        <legend><?php echo 'Related issues'; ?></legend>
                        <div class="issues">
                    <ul>
                        <?php foreach($milestone->issues as $issue) : ?>
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
                <?php echo Yii::app()->textile->textilize($milestone->description); ?>
                <?php if(($this->controller->id === 'milestone')&&(Yii::app()->user->checkAccess('Milestone.Update'))) : ?>
                    <?php echo CHtml::link('Edit Milestone',array('milestone/update','id'=>$milestone->id, 'identifier' => $_GET['identifier'])); ?><br/><br/>
                <?php endif; ?>
            <?php else : ?>
                <p></p>
            <?php endif; ?>
            <?php if($show_detail) : ?>
                <p class="nodata" style="width: 500px;"><?php echo 'No issues for this milestone'; ?></p>
            <?php else : ?>
                <p class="nodata" style="width: 250px;"><?php echo 'No issues for this milestone'; ?></p>
            <?php endif; ?>
        <?php endif; // is issues ?>
    <?php endif; // if show milestone ?>
<?php endforeach; ?>
