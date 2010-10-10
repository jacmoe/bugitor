<?php
$this->title = Yii::t("UserModule.user", "Update role");

$this->breadcrumbs=array(
	Yii::t("UserModule.user", 'Roles')=>array('index'),
	Yii::t("UserModule.user", 'Update'));

$this->menu = array(
		YumMenuItemHelper::adminPanel(), 
		YumMenuItemHelper::manageFields(),
		YumMenuItemHelper::manageRoles());
?>

<?php 
if(Yii::app()->user->isAdmin()) {
	$attributes = array(
			'id',
			YumMenuItemHelper::manageRoles(),
			YumMenuItemHelper::manageUsers()
			);
}
	?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
