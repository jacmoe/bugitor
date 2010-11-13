<?php
$this->breadcrumbs=array(
	'Configs'=>array('index'),
	$model->key=>array('view','id'=>$model->key),
	'Update',
);

$this->menu=array(
	array('label'=>'List Config', 'url'=>array('index')),
	array('label'=>'Create Config', 'url'=>array('create')),
	array('label'=>'View Config', 'url'=>array('view', 'id'=>$model->key)),
	array('label'=>'Manage Config', 'url'=>array('admin')),
);
?>

<h1>Update Config <?php echo $model->key; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>