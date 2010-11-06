<?php
$this->pageTitle = $model->project->name . ' - ' . $model->tracker->name . ' #' . $model->id . ': ' . $model->subject . ' - ' . Yii::app()->name ;
$this->breadcrumbs=array(
	'Issues'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Issue', 'url'=>array('index')),
	array('label'=>'Create Issue', 'url'=>array('create'), 'visible' => Yii::app()->user->checkAccess('Issue.Create')),
	array('label'=>'Update Issue', 'url'=>array('update', 'id'=>$model->id), 'visible' => Yii::app()->user->checkAccess('Issue.Update')),
	array('label'=>'Delete Issue', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible' => Yii::app()->user->checkAccess('Issue.Delete')),
	array('label'=>'Manage Issue', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('Issue.Admin')),
);
?>
<h3><?php echo $model->tracker->name . ' #' . $model->id; ?></h3>
<div class="span-24 issue">
<?php $this->widget('application.extensions.VGGravatarWidget', array('email' => $model->user()->email)); ?>
<h3><?php echo $model->subject; ?></h3>
Added by <?php echo CHtml::link(ucfirst($model->user->username),array('/user/user/view', "id" => $model->user->id)); ?> <?php echo Time::timeAgoInWords($model->created); ?>.
<hr/>
<table width="100%">
    <tbody><tr>
        <td style="width: 15%;" class="status"><b>Status:</b></td>
        <td style="width: 35%;" class="status"><?php echo $model->swGetStatus()->getLabel(); ?></td>
        <td style="width: 15%;" class="start-date"><b>Start:</b></td>
        <td style="width: 35%;">04/16/2010</td>
    </tr>
    <tr>
        <td class="priority"><b>Priority:</b></td>
        <td class="priority"><?php echo $model->issuePriority->name; ?></td>
        <td class="due-date"><b>Due Date:</b></td>
        <td class="due-date"></td>
    </tr>
    <tr>
        <td class="assigned-to"><b>Assigned to:</b></td>
        <td><?php if(isset($model->assignedTo)) : ?>
            <span><?php $this->widget('application.extensions.VGGravatarWidget', array('email' => $model->assignedTo->email)); ?></span> 
            <?php echo CHtml::link(ucfirst($model->assignedTo->username),array('/user/user/view', "id" => $model->assignedTo->id)); ?>
        <?php endif; ?></td>
        <td class="progress"><b>% Done:</b></td>
        <td class="progress"><?php echo Bugitor::progress_bar($model->done_ratio, array('width'=>'80px', 'legend'=>$model->done_ratio.'%'));?></td>
    </tr>
    <tr>
        <td class="category"><b>Category:</b></td>
        <td>-</td>
    </tr>
    <tr>
        <td class="fixed-version"><b>Version:</b></td>
        <td>0.5.0</td>
    </tr>
    <tr>
    </tr>
</tbody></table>
<hr/>
<p><b>Description:</b></p>
<?php echo $model->getDescription(); ?>
<hr/>
</div>
<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
				'label' => 'Tracker',
				'type' => 'raw',
				'value' => $model->tracker->name,
		),
		//'tracker_id',
		array(
				'label' => 'Project',
				'type' => 'raw',
				'value' => $model->project->name,
		),
		//'project_id',
		'subject',
		array(
				'label' => 'Description',
				'type' => 'raw',
				'value' => $model->getDescription(),
		),
		//'description',
		'due_date',
		'issue_category_id',
		array(
				'label' => 'User',
				'type' => 'raw',
				'value' => ucfirst($model->user->username),
		),
		//'user_id',
		array(
				'label' => 'Priority',
				'type' => 'raw',
				'value' => $model->issuePriority->name,
		),
		//'issue_priority_id',
//		array(
//				'label' => 'Version',
//				'type' => 'raw',
//				'value' => $model->version->name,
//		),
		'version_id',
		array(
				'label' => 'Assigned To',
				'type' => 'raw',
				'value' => ucfirst($model->user->username),
		),
		//'assigned_to',
		array('name' => 'created',
                    'value' => Time::timeAgoInWords($model->created),
                ),
		array('name' => 'modified',
                    'value' => Time::timeAgoInWords($model->modified),
                ),
		'start_date',
		'done_ratio',
		array(
                    'label' => 'Status',
                    'type' => 'raw',
                    'value' => $model->swGetStatus()->getLabel()
                ),
		array(
                    'label' => 'Closed',
                    'type' => 'boolean',
                    'value' => $model->closed,
                ),
	),
));*/ ?>
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
