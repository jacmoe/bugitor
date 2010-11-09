<?php
$this->pageTitle = $project_name . 'Issues - ' . Yii::app()->name ;
$this->breadcrumbs=array(
	'Issues',
);

$this->menu=array(
	array('label'=>'Create Issue', 'url'=>array('create'), 'visible' => Yii::app()->user->checkAccess('Issue.Create')),
	array('label'=>'Manage Issue', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('Issue.Admin')),
);
?>

<h1><?php echo $project_name ?>Issues</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'pager' => array('class' => 'CustomLinkPager'),
)); ?>
