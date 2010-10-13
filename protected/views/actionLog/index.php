<?php
$this->breadcrumbs=array(
	'Action Logs',
);

$this->menu=array(
	array('label'=>'Create ActionLog', 'url'=>array('create')),
	array('label'=>'Manage ActionLog', 'url'=>array('admin')),
);
?>

<h1>Action Logs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
