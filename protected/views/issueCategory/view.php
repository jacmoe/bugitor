<?php
$this->breadcrumbs=array(
	'Issue Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List IssueCategory', 'url'=>array('index')),
	array('label'=>'Create IssueCategory', 'url'=>array('create')),
	array('label'=>'Update IssueCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IssueCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IssueCategory', 'url'=>array('admin')),
);
?>

<h1>View IssueCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
	),
)); ?>
