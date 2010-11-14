<?php
$this->breadcrumbs=array(
	'Members'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Member', 'url'=>array('index')),
	array('label'=>'Create Member', 'url'=>array('create')),
	array('label'=>'View Member', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Member', 'url'=>array('admin')),
);
?>

<h1>Update Member <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>