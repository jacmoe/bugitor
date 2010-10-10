<?php
$this->title = Yii::t("UserModule.user", "Create role");

$this->breadcrumbs=array(
	Yii::t("UserModule.user", 'Roles')=>array('index'),
	Yii::t("UserModule.user", 'Create'));

$this->menu = array(
	YumMenuItemHelper::adminPanel(),
	YumMenuItemHelper::manageRoles(),
	YumMenuItemHelper::manageUsers());
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
