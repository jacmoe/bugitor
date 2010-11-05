<?php
$this->pageTitle=Yii::app()->name . ' - Projects';

$this->breadcrumbs=array(
	'Projects',
);

$this->menu=array(
	array('label'=>'Create Project', 'url'=>array('create'), 'visible' => Yii::app()->user->checkAccess('Project.Create')),
	array('label'=>'Manage Project', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('Project.Admin')),
);
?>
        <?php echo Time::serverOffset(); ?>

<h1>Projects</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<?php $this->beginWidget('zii.widgets.CPortlet', array(
'title'=>'Recent Comments',
));
$this->widget('RecentComments');
$this->endWidget(); ?>