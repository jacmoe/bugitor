<?php
$this->breadcrumbs=array(
	'Issue Priorities'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List IssuePriority', 'url'=>array('index')),
	array('label'=>'Create IssuePriority', 'url'=>array('create')),
	array('label'=>'Update IssuePriority', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IssuePriority', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IssuePriority', 'url'=>array('admin')),
);
?>

<h1>View IssuePriority #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
