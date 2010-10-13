<?php
$this->breadcrumbs=array(
	'Issue Statuses',
);

$this->menu=array(
	array('label'=>'Create IssueStatus', 'url'=>array('create')),
	array('label'=>'Manage IssueStatus', 'url'=>array('admin')),
);
?>

<h1>Issue Statuses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
