<?php
    if(empty($tabularIdx))
    {
    $this->title = Yii::t('UserModule.user', "Create user");
    $this->breadcrumbs = array(
        Yii::t('UserModule.user', 'Users') => array('index'),
    Yii::t('UserModule.user', 'Create'));
    $this->menu = array(
        YumMenuItemHelper::listUsers(),
        YumMenuItemHelper::manageUsers(),
        YumMenuItemHelper::manageFields());
    }

    echo $this->renderPartial('_form', array(
		'model'=>$model,
		'profile'=>$profile,
		'tabularIdx'=>$tabularIdx));
?>
