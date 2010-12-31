<?php
$this->breadcrumbs=array(
	'Relation Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List RelationType', 'url'=>array('index')),
	array('label'=>'Create RelationType', 'url'=>array('create')),
	array('label'=>'Update RelationType', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete RelationType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RelationType', 'url'=>array('admin')),
);
?>

<h1>View RelationType #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
