<?php
$this->breadcrumbs=array(
	'Trackers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tracker', 'url'=>array('index')),
	array('label'=>'Manage Tracker', 'url'=>array('admin')),
);
?>

<h1>Create Tracker</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>