<?php
$this->breadcrumbs=array(
	'Relation Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RelationType', 'url'=>array('index')),
	array('label'=>'Manage RelationType', 'url'=>array('admin')),
);
?>

<h1>Create RelationType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>