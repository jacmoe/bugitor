<?php
$this->breadcrumbs=array(
	'Versions'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Version', 'url'=>array('index')),
	array('label'=>'Create Version', 'url'=>array('create')),
	array('label'=>'Update Version', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Version', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Version', 'url'=>array('admin')),
);
?>

<h1>View Version #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'project_id',
		'name',
		'description',
		'effective_date',
		'created_on',
		'updated_on',
	),
)); ?>
