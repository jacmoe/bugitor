<?php
$this->title = Yii::t('UserModule.user','View role {role}',array('{role}',$model->title));

$this->breadcrumbs=array(
	Yii::t("UserModule.user", 'Roles')=>array('index'),
	Yii::t("UserModule.user", 'View'));

$this->menu = array(
		YumMenuItemHelper::adminPanel(), 
		YumMenuItemHelper::manageRoles(),
		YumMenuItemHelper::manageUsers(),
		YumMenuItemHelper::createRole(),
		YumMenuItemHelper::updateRole(array('id'=>$model->id)));
?>

<?php echo $model->description; ?>
<hr />

<p> 
<?php 
echo Yii::t('UserModule.user',
		'This users have been assigned to this role'); ?> 
</p>

	<?php 
if($model->users) 
{
	foreach($model->users as $user) {
		printf("<li>%s</li>", CHtml::link($user->username, array(YumHelper::route('user/view'), 'id' => $user->id)));

	}
}
else 
{
	echo '<p> None </p>';
}

?>
