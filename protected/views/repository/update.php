<?php
$this->breadcrumbs=array(
	'Repositories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Repository', 'url'=>array('index')),
	array('label'=>'Create Repository', 'url'=>array('create')),
	array('label'=>'View Repository', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Repository', 'url'=>array('admin')),
);
?>

<h1>Update Repository <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>