<?php
$this->breadcrumbs=array(
	'Changesets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Changeset', 'url'=>array('index')),
	array('label'=>'Create Changeset', 'url'=>array('create')),
	array('label'=>'View Changeset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Changeset', 'url'=>array('admin')),
);
?>

<h1>Update Changeset <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>