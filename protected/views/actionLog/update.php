<?php
$this->breadcrumbs=array(
	'Action Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ActionLog', 'url'=>array('index')),
	array('label'=>'Create ActionLog', 'url'=>array('create')),
	array('label'=>'View ActionLog', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ActionLog', 'url'=>array('admin')),
);
?>

<h1>Update ActionLog <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>