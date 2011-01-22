<?php
$this->breadcrumbs=array(
	'Project Links',
);

$this->menu=array(
	array('label'=>'Create ProjectLink', 'url'=>array('create')),
	array('label'=>'Manage ProjectLink', 'url'=>array('admin')),
);
?>

<h1>Project Links</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
