<?php
$this->breadcrumbs=array(
	'Project Links'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ProjectLink', 'url'=>array('index')),
	array('label'=>'Manage ProjectLink', 'url'=>array('admin')),
);
?>

<h1>Create ProjectLink</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>