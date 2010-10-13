<?php
$this->breadcrumbs=array(
	'Repositories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Repository', 'url'=>array('index')),
	array('label'=>'Create Repository', 'url'=>array('create')),
	array('label'=>'Update Repository', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Repository', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Repository', 'url'=>array('admin')),
);
?>

<h1>View Repository #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'project_id',
		'url',
		'login',
		'password',
		'name',
	),
)); ?>
