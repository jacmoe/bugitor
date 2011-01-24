<?php
$this->breadcrumbs=array(
	'Changesets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Changeset', 'url'=>array('index')),
	array('label'=>'Create Changeset', 'url'=>array('create')),
	array('label'=>'Update Changeset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Changeset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Changeset', 'url'=>array('admin')),
);
?>

<h1>View Changeset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'revision',
		'user_id',
		'scm_id',
		'commit_date',
		'message',
		'short_rev',
		'parent',
		'branch',
		'tags',
		'add',
		'edit',
		'del',
	),
)); ?>
