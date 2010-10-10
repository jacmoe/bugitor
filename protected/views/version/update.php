<?php
$this->breadcrumbs=array(
	'Versions'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Version', 'url'=>array('index')),
	array('label'=>'Create Version', 'url'=>array('create')),
	array('label'=>'View Version', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Version', 'url'=>array('admin')),
);
?>

<h1>Update Version <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>