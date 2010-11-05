<?php
$this->breadcrumbs=array(
	'Projects'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Project', 'url'=>array('index')),
	array('label'=>'Manage Project', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('Project.Admin')),
);
?>

<h1>Create Project</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>