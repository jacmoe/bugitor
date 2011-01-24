<?php
$this->breadcrumbs=array(
	'Changesets',
);

$this->menu=array(
	array('label'=>'Create Changeset', 'url'=>array('create')),
	array('label'=>'Manage Changeset', 'url'=>array('admin')),
);
?>

<h1>Changesets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
