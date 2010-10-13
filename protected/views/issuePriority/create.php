<?php
$this->breadcrumbs=array(
	'Issue Priorities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IssuePriority', 'url'=>array('index')),
	array('label'=>'Manage IssuePriority', 'url'=>array('admin')),
);
?>

<h1>Create IssuePriority</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>