<?php
$this->pageTitle = $model->project->name . ' - ' . $model->tracker->name . ' #' . $model->id . ': ' . $model->subject . ' - ' . Yii::app()->name ;
?>
<div class="contextual" id="contextual">
<?php if(Yii::app()->user->checkAccess('Issue.Update')) echo CHtml::link('Update', array('update', 'id' => $model->id, 'identifier' => $model->project->identifier), array('class' => 'icon icon-edit')) ?>
&nbsp;&nbsp;
<?php if(Yii::app()->user->checkAccess('Issue.Update'))
    echo CHtml::ajaxLink(($model->watchedBy(Yii::app()->user->id)) ? "Unwatch" : "Watch",
        $this->createUrl('testAjax'),
        array('update' => '#watchers',
            'type' => "post",
            'data' => array('id' => $model->id),
            'complete' => 'function(data,status){
                if(status == "success") {
                var titleTxt = $("#watchButton").attr("text");
                if(titleTxt == "Unwatch") {
                    $("#watchButton").text("Watch");
                    $("#watchButton").removeClass("icon icon-fav");
                    $("#watchButton").addClass("icon icon-fav-off");
                } else {
                    $("#watchButton").text("Unwatch");
                    $("#watchButton").removeClass("icon icon-fav-off");
                    $("#watchButton").addClass("icon icon-fav");
                }}
            }'), array('class' => ($model->watchedBy(Yii::app()->user->id)) ? 'icon icon-fav' : 'icon icon-fav-off', 'id' => 'watchButton')); ?>
&nbsp;&nbsp;
<?php if(Yii::app()->user->checkAccess('Issue.Move')) echo '  ' . CHtml::link('Move', '#',array('submit' => array('move', 'id' => $model->id, 'identifier' => $model->project->identifier), 'class' => 'icon icon-move')) ?>
<?php if(Yii::app()->user->checkAccess('Issue.Delete')) echo '  ' . CHtml::link('Delete', '#', array('submit' => array('delete','id' => $model->id, 'identifier' => $model->project->identifier), 'confirm' => 'Are you sure you want to delete this issue?', 'class' => 'icon icon-del')); ?>
</div>
<h2><?php echo Bugitor::namedImage($model->tracker->name) . ' ' . $model->tracker->name . ' #' . $model->id; ?></h2>
<div class="issue">
<?php echo Bugitor::gravatar($model->user->email); ?>
<h3><?php echo $model->subject; ?></h3>
Added by <?php echo Bugitor::link_to_user($model->user->username, $model->user->id); ?> <?php echo Time::timeAgoInWords($model->created); ?>.
<hr/>
<table width="95%">
    <tbody><tr>
        <td style="width: 15%;" class="status"><b>Status:</b></td>
        <td style="width: 35%;" class="status">
            <?php if(isset($model->status)) : ?>
                <?php echo $model->swGetStatus()->getLabel(); ?>
            <?php endif; ?>
        </td>
        <td class="category"><b>Category:</b></td>
        <td>
            <?php if(isset($model->issueCategory)) : ?>
                    <?php echo $model->issueCategory->name; ?>
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
        <td class="fixed-version"><b>Version:</b></td>
        <td>
            <?php if(isset($model->version)) : ?>
                    <?php echo $model->version->name; ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td class="assigned-to"><b>Assigned to:</b></td>
        <td>
            <?php if(isset($model->assignedTo)) : ?>
                <span><?php echo Bugitor::gravatar($model->assignedTo->email); ?></span>
                <?php echo Bugitor::link_to_user($model->assignedTo->username, $model->assignedTo->id); ?>
            <?php endif; ?>
        </td>
        <td class="progress"><b>% Done:</b></td>
        <td class="progress">
            <?php if(isset($model->done_ratio)) : ?>
                <?php echo Bugitor::progress_bar($model->done_ratio, array('width'=>'80px', 'legend'=>$model->done_ratio.'%'));?>
            <?php endif; ?>
        </td>
    </tr>
</tbody></table>
<hr/>
<p><b>Description:</b></p>
<?php echo $model->getDescription(); ?>
<hr/>
<h4>Watchers</h4>
<div id="watchers">
<?php $this->renderPartial('_watchers', array('watchers' => $model->getWatchers())); ?>
</div>
</div>
<?php /* ?>
<div class="span-16" id="comments">
<?php if($model->commentCount>=1): ?>
<h3>
<?php echo $model->commentCount>1 ? $model->commentCount . ' comments' : 'One comment'; ?>
</h3>
<?php $this->renderPartial('_comments',array('comments'=>$model->comments,)); ?>
<?php endif; ?>
<h3>Leave a Comment</h3>
<?php if(Yii::app()->user->hasFlash('commentSubmitted')): ?>
<div class="flash-success">
<?php echo Yii::app()->user->getFlash('commentSubmitted'); ?>
</div>
<?php endif; ?>
<?php $this->renderPartial('/comment/_form',array('model'=>$comment,)); ?>
</div>
<?php */ ?>
