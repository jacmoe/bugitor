<?php
$this->pageTitle = $model->name . ' - Overview - ' . Yii::app()->name;

$this->breadcrumbs=array(
	'Projects'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Projects', 'url'=>array('index')),
	array('label'=>'Create Project', 'url'=>array('create'), 'visible' => Yii::app()->user->checkAccess('Project.Create')),
	array('label'=>'Update Project', 'url'=>array('update', 'id'=>$model->id), 'visible' => Yii::app()->user->checkAccess('Project.Update')),
	array('label'=>'Delete Project', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible' => Yii::app()->user->checkAccess('Project.Delete')),
	array('label'=>'Manage Projects', 'url'=>array('admin'), 'visible' => Yii::app()->user->checkAccess('Project.Admin')),
	array('label'=>'Add Users', 'url'=>array('adduser', 'identifier' => $_GET['identifier']), 'visible' => Yii::app()->user->checkAccess('Project.Adduser')),
);
?>

<h1>View Project <?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		array('name' => 'homepage',
                    'type' => 'url',
                ),
		array('name' => 'public',
                    'type' => 'boolean',
                ),
		array('name' => 'created',
                    'type' => 'html',
                    'value' => Time::timeAgoInWords($model->created),
                ),
		array('name' => 'modified',
                    'type' => 'html',
                    'value' => Time::timeAgoInWords($model->modified),
                ),
		'identifier',
	),
)); ?>
<?php
foreach($members as $member) {
    echo '<b>' . $member->username . '</b> | ';
    $assignments = Rights::getAssignedRoles($member->id);
    foreach($assignments as $assignment) {
        echo $assignment->name . ' . ';
        }
        echo '<br/>';

}
?>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
'title'=>'Recent Project Comments',
));
$this->widget('RecentComments', array('projectId'=>$model->id));
$this->endWidget(); ?>