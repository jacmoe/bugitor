<?php
$this->title = Yii::t('UserModule.user', 'Users');
$this->breadcrumbs=array(Yii::t("UserModule.user", "Users"));
$this->menu = array(
  YumMenuItemHelper::adminPanel(), 
	YumMenuItemHelper::createUser(),
	YumMenuItemHelper::manageUsers(),
	YumMenuItemHelper::manageFields(),
	YumMenuItemHelper::manageRoles());
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider'=>$dataProvider,
			'columns'=>array(
		array(
			'name' => 'username',
			'type'=>'raw',
			'value' => 'CHtml::link(CHtml::encode($data->username),
				array(YumHelper::route("{user}/user/profile"),"id"=>$data->id))',
			),
		array(
			'name' => 'createtime',
			'value' => 'date(UserModule::$dateFormat,$data->createtime)',
		),
		array(
			'name' => 'lastvisit',
			'value' => 'date(UserModule::$dateFormat,$data->lastvisit)',
		),
	),
)); ?>


