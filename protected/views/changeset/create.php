<?php
$this->breadcrumbs=array(
	'Changesets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Changeset', 'url'=>array('index')),
	array('label'=>'Manage Changeset', 'url'=>array('admin')),
);
?>

<h1>Create Changeset</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>