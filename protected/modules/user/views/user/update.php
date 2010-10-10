<?php

    if(empty($tabularIdx))
    {
        $this->title=Yii::t("UserModule.user", 'Update user')." ".$model->id;

        $this->breadcrumbs = array(
            Yii::t("UserModule.user", 'Users')=>array('index'),
            $model->username=>array('view','id'=>$model->id),
            Yii::t("UserModule.user", 'Update'));

				$this->menu = array(
						YumMenuItemHelper::adminPanel(), 
						YumMenuItemHelper::manageUsers(),
            YumMenuItemHelper::listUsers(),
            YumMenuItemHelper::createUser(),
            YumMenuItemHelper::viewUser(array('id'=>$model->id)),
            YumMenuItemHelper::manageRoles(),
            YumMenuItemHelper::updateProfile(array('id'=>$model->id),'Manage this profile'),
            YumMenuItemHelper::manageFields());
    }

echo $this->renderPartial('_form', array(
			'model'=>$model,
			'profile'=>$profile,
			'tabularIdx'=>$tabularIdx)
		);
?>
