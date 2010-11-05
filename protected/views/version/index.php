<?php
$this->breadcrumbs=array(
	'Versions',
);

$this->menu=array(
	array('label'=>'Create Version', 'url'=>array('create')),
	array('label'=>'Manage Version', 'url'=>array('admin')),
);
?>

<h1>Versions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
