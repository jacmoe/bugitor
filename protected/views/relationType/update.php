<?php
$this->breadcrumbs=array(
	'Relation Types'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List RelationType', 'url'=>array('index')),
	array('label'=>'Create RelationType', 'url'=>array('create')),
	array('label'=>'View RelationType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage RelationType', 'url'=>array('admin')),
);
?>

<h1>Update RelationType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>