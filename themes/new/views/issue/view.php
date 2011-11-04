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
<?php if(null !== $model) : ?>
<?php
$this->pageTitle = $model->project->name . ' - ' . $model->tracker->name . ' #' . $model->id . ': ' . $model->subject . ' - ' . Yii::app()->name ;
?>
<div id="issue-view">
<div class="contextual" id="contextual">
<?php if(Yii::app()->user->checkAccess('Issue.Update')) : ?>
    <?php echo CHtml::link('Update / Reply', array('update', 'id' => $model->id, 'identifier' => $model->project->identifier), array('class' => 'icons-edit')) ?>
<?php elseif(Yii::app()->user->checkAccess('Issue.Create')) : ?>
    <?php echo CHtml::link('Reply', array('comment', 'id' => $model->id, 'identifier' => $model->project->identifier), array('class' => 'icons-edit')) ?>
<?php endif; ?>
&nbsp;&nbsp;
<?php if(Yii::app()->user->checkAccess('Issue.Watch'))
    echo CHtml::ajaxLink(($model->watchedBy(Yii::app()->user->id)) ? "Unwatch" : "Watch",
        $this->createUrl('watch'),
        array('update' => '#watchers',
            'type' => "post",
            'data' => array('id' => $model->id),
            'complete' => 'function(data,status){
                if(status == "success") {
                var titleTxt = $("#watchButton").attr("text");
                if(titleTxt == "Unwatch") {
                    $("#watchButton").text("Watch");
                    $("#watchButton").removeClass("icons-fav");
                    $("#watchButton").addClass("icons-fav-off");
                } else {
                    $("#watchButton").text("Unwatch");
                    $("#watchButton").removeClass("icons-fav-off");
                    $("#watchButton").addClass("icons-fav");
                }}
            }'), array('class' => ($model->watchedBy(Yii::app()->user->id)) ? 'icons-fav' : 'icons-fav-off', 'id' => 'watchButton')); ?>
&nbsp;&nbsp;
<?php if(Yii::app()->user->checkAccess('Issue.Move')) echo '  ' . CHtml::link('Move', '#',array('submit' => array('move', 'id' => $model->id, 'identifier' => $model->project->identifier), 'class' => 'icons-move')) ?>
<?php if(Yii::app()->user->checkAccess('Issue.Delete')) echo '  ' . CHtml::link('Delete', '#delete', array('submit' => array('delete','id' => $model->id, 'identifier' => $model->project->identifier), 'confirm' => 'Are you sure you want to delete this issue?', 'class' => 'icons-delete', 'id' => 'delete-button')); ?>
</div>
<h2><?php echo Bugitor::namedImage($model->tracker->name, true) . ' ' . $model->tracker->name . ' #' . $model->id; ?> (<?php echo $model->getStatusLabel($model->status); ?>)</h2>
<div class="issue">
<div class="row">
<?php echo Bugitor::gravatar($model->user); ?>
<h3><?php echo $model->subject; ?></h3>
Added by <?php echo Bugitor::link_to_user($model->user); ?> <?php echo Bugitor::timeAgoInWords($model->created); ?>.
<?php if(isset($model->updatedBy)) echo '  Updated by '.Bugitor::link_to_user($model->updatedBy) .' '. Bugitor::timeAgoInWords($model->modified); ?>
<hr/>
</div>
<div class="row">
<div id="splitcontentleft">
<table width="100%">
    <tbody><tr>
        <td style="width: 15%;" class="status"><b>Status:</b></td>
        <td style="width: 35%;" class="status">
            <?php if(isset($model->status)) : ?>
                <?php echo $model->getStatusLabel($model->status); ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td class="priority"><b>Priority:</b></td>
        <td class="priority">
            <?php if(isset($model->issuePriority)) : ?>
                    <?php echo $model->issuePriority->name; ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td class="assigned-to"><b>Assigned to:</b></td>
        <td>
            <?php if(isset($model->assignedTo)) : ?>
                <span><?php echo Bugitor::gravatar($model->assignedTo); ?></span>
                <?php echo Bugitor::link_to_user($model->assignedTo); ?>
            <?php endif; ?>
        </td>
    </tr>
</tbody></table>
</div>
<div id="splitcontentright">
<table width="100%">
    <tbody><tr>
        <td class="category"><b>Category:</b></td>
        <td>
            <?php if(isset($model->issueCategory)) : ?>
                    <?php echo $model->issueCategory->name; ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td class="fixed-version"><b>Milestone:</b></td>
        <td>
            <?php if(isset($model->milestone)) : ?>
                    <?php echo Bugitor::link_to_milestone($model->milestone); ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td class="progress"><b>% Done:</b></td>
        <td class="progress">
            <?php if(isset($model->done_ratio)) : ?>
                <?php echo Bugitor::progress_bar($model->done_ratio, array('width'=>'80px', 'legend'=>$model->done_ratio.'%'));?>
            <?php endif; ?>
        </td>
    </tr>
</tbody></table>
</div>
</div><!-- row //-->
<br style="clear:both"/>
<hr style="clear:both"/>
<b>Description:</b><br/>
<?php echo Yii::app()->textile->textilize($model->description); ?>
<hr/>
<h4>Watchers</h4>
<div id="watchers">
<?php $this->renderPartial('_watchers', array('model' => $model)); ?>
</div>
<?php if(Yii::app()->user->checkAccess('Issue.Update')) : ?>
<a href="#" onClick="$('#add_watch').toggle();">Add / Remove Watcher</a>
<div class="issues" id="add_watch" style="display: none;">
<?php $this->renderPartial('_addwatchers', array('model' => $model));?>
</div>
<br class="clearfix"/>
<br class="clearfix"/>
<?php endif; ?>
<hr/>
<h4>Attachments</h4>
<div id="attachments">
<?php $this->renderPartial('_attachments', array('attachments' => $model->getAttachments(), 'parent_id' => $model->id)); ?>
</div>
</div>
<?php if($model->changesetIssueCount>=1): ?>
<?php $this->renderPartial('_changesets',array('changeset_issues'=>$model->changesetIssues, 'changeset_issue_count' => $model->changesetIssueCount )); ?>
<?php endif; ?>
<?php if($model->commentCount>=1): ?>
<?php $this->renderPartial('_comments',array('comments'=>$model->comments,)); ?>
<?php endif; ?>
<?php else : ?>
No such issue.
<?php endif; ?>
</div>