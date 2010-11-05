<?php
$this->breadcrumbs=array(
	'Issue Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List IssueCategory', 'url'=>array('index')),
	array('label'=>'Create IssueCategory', 'url'=>array('create')),
	array('label'=>'View IssueCategory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage IssueCategory', 'url'=>array('admin')),
);
?>

<h1>Update IssueCategory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>