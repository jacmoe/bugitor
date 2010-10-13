<?php
$this->breadcrumbs=array(
	'Trackers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tracker', 'url'=>array('index')),
	array('label'=>'Create Tracker', 'url'=>array('create')),
	array('label'=>'View Tracker', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tracker', 'url'=>array('admin')),
);
?>

<h1>Update Tracker <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>