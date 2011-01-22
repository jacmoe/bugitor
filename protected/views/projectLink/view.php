<?php
$this->breadcrumbs=array(
	'Project Links'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List ProjectLink', 'url'=>array('index')),
	array('label'=>'Create ProjectLink', 'url'=>array('create')),
	array('label'=>'Update ProjectLink', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ProjectLink', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ProjectLink', 'url'=>array('admin')),
);
?>

<h1>View ProjectLink #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'url',
		'title',
		'description',
		'position',
	),
)); ?>
