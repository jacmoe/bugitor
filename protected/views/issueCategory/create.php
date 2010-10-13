<?php
$this->breadcrumbs=array(
	'Issue Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List IssueCategory', 'url'=>array('index')),
	array('label'=>'Manage IssueCategory', 'url'=>array('admin')),
);
?>

<h1>Create IssueCategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>