<?php
$this->breadcrumbs=array(
	'Relation Types',
);

$this->menu=array(
	array('label'=>'Create RelationType', 'url'=>array('create')),
	array('label'=>'Manage RelationType', 'url'=>array('admin')),
);
?>

<h1>Relation Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
