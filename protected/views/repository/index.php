<?php
$this->breadcrumbs=array(
	'Repositories',
);

$this->menu=array(
	array('label'=>'Create Repository', 'url'=>array('create')),
	array('label'=>'Manage Repository', 'url'=>array('admin')),
);
?>

<h1>Repositories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
