<?php
$this->breadcrumbs=array(
	'Action Logs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ActionLog', 'url'=>array('index')),
	array('label'=>'Create ActionLog', 'url'=>array('create')),
	array('label'=>'Update ActionLog', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ActionLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ActionLog', 'url'=>array('admin')),
);
?>

<h1>View ActionLog #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'description',
		'action',
		'model',
		'idModel',
		'field',
		'creationdate',
		'userid',
	),
)); ?>
