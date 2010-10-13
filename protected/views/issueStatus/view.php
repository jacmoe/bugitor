<?php
$this->breadcrumbs=array(
	'Issue Statuses'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List IssueStatus', 'url'=>array('index')),
	array('label'=>'Create IssueStatus', 'url'=>array('create')),
	array('label'=>'Update IssueStatus', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IssueStatus', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IssueStatus', 'url'=>array('admin')),
);
?>

<h1>View IssueStatus #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'closed',
		'default',
	),
)); ?>
