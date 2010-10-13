<?php
$this->breadcrumbs=array(
	'Issue Priorities'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List IssuePriority', 'url'=>array('index')),
	array('label'=>'Create IssuePriority', 'url'=>array('create')),
	array('label'=>'View IssuePriority', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage IssuePriority', 'url'=>array('admin')),
);
?>

<h1>Update IssuePriority <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>