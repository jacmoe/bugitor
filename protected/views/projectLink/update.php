<?php
$this->breadcrumbs=array(
	'Project Links'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ProjectLink', 'url'=>array('index')),
	array('label'=>'Create ProjectLink', 'url'=>array('create')),
	array('label'=>'View ProjectLink', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ProjectLink', 'url'=>array('admin')),
);
?>

<h1>Update ProjectLink <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>