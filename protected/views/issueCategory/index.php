<?php
$this->breadcrumbs=array(
	'Issue Categories',
);

$this->menu=array(
	array('label'=>'Create IssueCategory', 'url'=>array('create')),
	array('label'=>'Manage IssueCategory', 'url'=>array('admin')),
);
?>

<h1>Issue Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
