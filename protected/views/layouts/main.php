<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container" id="page">

            <div id="header">
                <div id="logo"><?php echo CHtml::image(Yii::app()->theme->baseUrl . '/images/bugitor_64.png') ?><?php echo CHtml::encode(Yii::app()->name); ?><div align="right"><?php if (!Yii::app()->user->isGuest): ?><?php
            $this->widget('application.extensions.VGGravatarWidget',
                    array(
                        'email' => Yii::app()->getModule('user')->user()->email, // email to display the gravatar belonging to it
                        'hashed' => false, // if the email provided above is already md5 hashed then set this property to true, defaults to false
                        'default' => 'http://www.mysite.com/default_gravatar_image.jpg', // if an email is not associated with a gravatar this image will be displayed,
                        // by default this is omitted so the Blue Gravatar icon will be displayed you can also set this to
                        // "identicon" "monsterid" and "wavatar" which are default gravatar icons
                        'size' => 50, // the gravatar icon size in px defaults to 40
                        'rating' => 'PG', // the Gravatar ratings, Can be G, PG, R, X, Defaults to G
                        'htmlOptions' => array('alt' => 'Gravatar Icon'), // Html options that will be appended to the image tag
            ));
?>
<?php endif; ?>
                    </div></div>
            </div><!-- header -->

            <div id="mainmenu">
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        array('label' => 'Home', 'url' => array('/site/index')),
                        array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
                        array('label' => 'Contact', 'url' => array('/site/contact')),
                        array('url' => Yii::app()->getModule('user')->loginUrl, 'label' => Yii::app()->getModule('user')->t("Login"), 'visible' => Yii::app()->user->isGuest),
                        array('url' => Yii::app()->getModule('user')->registrationUrl, 'label' => Yii::app()->getModule('user')->t("Register"), 'visible' => Yii::app()->user->isGuest),
                        array('url' => Yii::app()->getModule('user')->profileUrl, 'label' => Yii::app()->getModule('user')->t("Profile"), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Rights', 'url' => array('/rights'), 'visible' => Yii::app()->user->checkAccess(Rights::module()->superuserName)),
                        array('url' => Yii::app()->getModule('user')->logoutUrl, 'label' => Yii::app()->getModule('user')->t("Logout") . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest),
                    ),
                )); ?>
                </div><!-- mainmenu -->
            <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                )); ?><!-- breadcrumbs -->
            <?php echo CHtml::image(Yii::app()->getModule('user')->user()->avatar('64')); ?>
                <?php echo $content; ?>
            <?php echo CHtml::image(Yii::app()->getModule('user')->user()->avatar()); ?>

            <div id="footer">
		Copyright &copy; 2010 by Ogitor Team.<br/>
		All Rights Reserved.<br/>
<?php echo Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->
    </body>
</html>