<?php
$this->breadcrumbs=array(
	'Action Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ActionLog', 'url'=>array('index')),
	array('label'=>'Manage ActionLog', 'url'=>array('admin')),
);
?>

<h1>Create ActionLog</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>