<?php
$this->breadcrumbs=array(
	'Projects'=>array('index'),
	$model->name=>array('view','name'=>$model->name),
	'Update',
);

$this->menu=array(
	array('label'=>'List Project', 'url'=>array('index')),
	array('label'=>'Create Project', 'url'=>array('create'), 'visible' => Yii::app()->user->checkAccess('Project.Create')),
	array('label'=>'View Project', 'url'=>array('view', 'name'=>$model->name)),
	array('label'=>'Manage Project', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('Project.Admin')),
);
?>

<h1>Update Project <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>