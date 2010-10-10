<?php $this->widget('zii.widgets.CMenu', array(
	'htmlOptions'=>array('class'=>'actions'),
	'items'=>array(
		array(
			'label'=>Rights::t('core', 'Assignments'),
			'url'=>array('assignment/view'),
			'itemOptions'=>array('class'=>'first assignments'),
			'visible'=>Yii::app()->user->checkAccess('RightsAssignments')===true,
		),
		array(
			'label'=>Rights::t('core', 'Permissions'),
			'url'=>array('authItem/permissions'),
			'itemOptions'=>array('class'=>'permissions'),
			'visible'=>Yii::app()->user->checkAccess('RightsPermissions')===true,
		),
		array(
			'label'=>Rights::t('core', 'Operations'),
			'url'=>array('authItem/operations'),
			'itemOptions'=>array('class'=>'operations'),
			'visible'=>Yii::app()->user->checkAccess('RightsAdministration')===true,
		),
		array(
			'label'=>Rights::t('core', 'Tasks'),
			'url'=>array('authItem/tasks'),
			'itemOptions'=>array('class'=>'tasks'),
			'visible'=>Yii::app()->user->checkAccess('RightsAdministration')===true,
		),
		array(
			'label'=>Rights::t('core', 'Roles'),
			'url'=>array('authItem/roles'),
			'itemOptions'=>array('class'=>'roles'),
			'visible'=>Yii::app()->user->checkAccess('RightsAdministration')===true,
		),
		array(
			'label'=>Rights::t('core', 'Generator'),
			'url'=>array('authItem/generate'),
			'itemOptions'=>array('class'=>'last generator'),
			'visible'=>Yii::app()->user->checkAccess('RightsAdministration')===true,
		),
	)
));	?>
