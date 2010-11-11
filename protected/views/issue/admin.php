<?php
$this->breadcrumbs=array(
	'Issues'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Issue', 'url'=>array('index')),
	array('label'=>'Create Issue', 'url'=>array('create'), 'visible' => Yii::app()->user->checkAccess('Issue.Create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('issue-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Issues</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'issue-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
                array(
                    'name' => 'id',
                    'header' => 'Id',
                    'type' => 'raw',
                    'value' => 'CHtml::link(CHtml::encode($data->id),array("view","id"=>$data->id, "name"=>$data->project->name))',
                    'htmlOptions'=>array('width'=>'5'),
                    ),
                array(
                    'name' => 'tracker_id',
                    'header' => 'Type',
                    'value' => '$data->tracker->name',
                    'filter' => $this->getTrackers(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
//                array(
//                    'name' => 'project_id',
//                    'header' => 'Project',
//                    'value' => '$data->project->name',
//                    'filter' => $this->getProjects(),
//                ),
                array(
                    'name' => 'status',
                    'header' => 'Status',
                    'value' => '$data->swGetStatus()->getLabel()',
                    'filter' => SWHelper::allStatuslistData($model),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'issue_priority_id',
                    'header' => 'Priority',
                    'value' => '$data->issuePriority->name',
                    'filter' => $this->getPriorities(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'subject',
                    'htmlOptions'=>array('width'=>'40%'),
                ),
		/*
		'issue_category_id',*/
                array(
                    'name' => 'user_id',
                    'header' => 'Author',
                    'value' => '$data->user->username',
                    'filter' => $this->getUsers(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'assigned_to',
                    'header' => 'Owner',
                    'value' => '(($data->assignedTo)?$data->assignedTo->username:"")',
                    'filter' => $this->getUsers(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'version_id',
                    'header' => 'Version',
                    'value' => '(($data->version)?$data->version->name:"")',
                    'filter' => $this->getVersions(),
                    'htmlOptions'=>array('width'=>'10'),
                ),
                array(
                    'name' => 'done_ratio',
                    'header' => '% Done',
                    'type' => 'raw',
                    'value' => '(($data->done_ratio)?$data->done_ratio:"")',
                    'htmlOptions'=>array('width'=>'10'),
                ),
		/*'version_id',
		'assigned_to',
		'created',
		'modified',
		'start_date',
		'done_ratio',
		'status',
		'closed',
		*/
		array(
			'class'=>'IssueButtonColumn',
		),
	),
)); ?>
