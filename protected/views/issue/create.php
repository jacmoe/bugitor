<?php
$this->pageTitle = $project_name . 'New Issue - ' . Yii::app()->name ;
$this->breadcrumbs=array(
	'Issues'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Issue', 'url'=>array('index')),
	array('label'=>'Manage Issue', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('Issue.Admin')),
);
?>

<h1>Create Issue</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>