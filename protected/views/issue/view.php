<?php
$this->breadcrumbs=array(
	'Issues'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Issue', 'url'=>array('index')),
	array('label'=>'Create Issue', 'url'=>array('create')),
	array('label'=>'Update Issue', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Issue', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Issue', 'url'=>array('admin')),
);
?>

<h1>View Issue #<?php echo $model->id; ?></h1>

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
