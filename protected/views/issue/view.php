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

<h3><?php echo $model->project->name . ' - ' . $model->tracker->name . ' #' . $model->id . ': ' . $model->subject; ?></h3>

<?php $this->widget('zii.widgets.CDetailView', array(
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
				'value' => $model->user->username,
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
				'value' => $model->user->username,
		),
		//'assigned_to',
		'created',
		'modified',
		'start_date',
		'done_ratio',
		array(
                    'label' => 'Status',
                    'type' => 'raw',
                    'value' => $model->swGetStatus()->getLabel()
                ),
		'closed',
	),
)); ?>
<div id="comments">
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