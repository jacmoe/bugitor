<?php
$this->breadcrumbs=array(
	'Issue Priorities',
);

$this->menu=array(
	array('label'=>'Create IssuePriority', 'url'=>array('create')),
	array('label'=>'Manage IssuePriority', 'url'=>array('admin')),
);
?>

<h1>Issue Priorities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
