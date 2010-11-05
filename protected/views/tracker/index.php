<?php
$this->breadcrumbs=array(
	'Trackers',
);

$this->menu=array(
	array('label'=>'Create Tracker', 'url'=>array('create')),
	array('label'=>'Manage Tracker', 'url'=>array('admin')),
);
?>

<h1>Trackers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
