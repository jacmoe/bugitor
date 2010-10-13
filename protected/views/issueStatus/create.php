<?php
$this->breadcrumbs=array(
	'Issue Statuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IssueStatus', 'url'=>array('index')),
	array('label'=>'Manage IssueStatus', 'url'=>array('admin')),
);
?>

<h1>Create IssueStatus</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>