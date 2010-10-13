<?php
$this->breadcrumbs=array(
	'Issue Statuses'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List IssueStatus', 'url'=>array('index')),
	array('label'=>'Create IssueStatus', 'url'=>array('create')),
	array('label'=>'View IssueStatus', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage IssueStatus', 'url'=>array('admin')),
);
?>

<h1>Update IssueStatus <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>