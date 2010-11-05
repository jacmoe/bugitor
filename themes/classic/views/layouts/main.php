<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/plugins/fancy-type/screen.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body>
        <div class=container id="page">
            <div id="mainmenu" class="span-16">
                <?php
                $this->widget('BugitorMenu', array(
                    'items' => array(
                        array('label' => 'Home', 'url' => array('/site/index'), 'id' => 'site/index'),
                        array('label' => 'Projects', 'url' => array('/projects/'), 'id' => 'project/index'),
                    ),
                )); ?>
            </div>
            <div id="mainmenu" class="span-8 last">
                <span class="right">
                    <?php
                    $this->widget('BugitorMenu', array(
                        'items' => array(
                            array('url' => Yii::app()->getModule('user')->loginUrl, 'label' => Yii::app()->getModule('user')->t("Login"), 'visible' => Yii::app()->user->isGuest, 'id' => 'user/login/login'),
                            array('url' => Yii::app()->getModule('user')->registrationUrl, 'label' => Yii::app()->getModule('user')->t("Register"), 'visible' => Yii::app()->user->isGuest, 'id' => 'user/registration/registration'),
                            array('url' => Yii::app()->getModule('user')->profileUrl, 'label' => Yii::app()->getModule('user')->t("Profile"), 'visible' => !Yii::app()->user->isGuest, 'id' => 'user/profile/profile'),
                            array('url' => Yii::app()->getModule('user')->logoutUrl, 'label' => Yii::app()->getModule('user')->t("Logout") . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest, 'id' => 'user/logout/logout'),
                        ),
                    )); ?>
                </span>
            </div>
            <hr/>
            <div id="header" class="span-24">
                <div id="logo" class="span-2">
                    <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/images/bugitor_64.png') ?>
                </div>
                <div id="header" class="span-14 alt">
                    <div>
                        <h1 class="alt"><?php echo CHtml::encode(Yii::app()->name); ?></h1>
                    </div>
                </div>
                <div id="gravatar" class="span-8 last">
                    <div class="right">
                        <?php if (!Yii::app()->user->isGuest): ?>
                        <?php
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
                        </div>
                    </div>
                </div>
                <div id="mainmenu" class="span-24">
                <?php if (((Yii::app()->controller->id === 'project')||(Yii::app()->controller->id === 'issue')) && (isset($_GET['name']))) : ?>
                <?php
                                $this->widget('BugitorMenu', array(
                                    'items' => array(
                                        array('label' => 'Overview', 'url' => array('/projects/' . $_GET['name']), 'id' => 'project/view'),
                                        array('label' => 'Activity', 'url' => array('/projects/' . $_GET['name'] . '/activity'), 'id' => 'project/activity'),
                                        array('label' => 'Roadmap', 'url' => array('/projects/' . $_GET['name'] . '/roadmap'), 'id' => 'project/roadmap'),
                                        array('label' => 'Issues', 'url' => array('/projects/' . $_GET['name'] . '/issues'), 'id' => 'issue/index'),
                                        array('label' => 'New Issue', 'url' => array('/projects/' . $_GET['name'] . '/issue/create'), 'visible' => !Yii::app()->user->isGuest, 'id' => 'issue/create'),
                                        array('label' => 'Code', 'url' => array('/projects/' . $_GET['name'] . '/code'), 'id' => 'project/code'),
                                        array('label' => 'Settings', 'url' => array('/projects/' . $_GET['name'] . '/settings'), 'visible' => !Yii::app()->user->isGuest, 'id' => 'project/settings'),
                                    ),
                                )); ?>
                <?php else : ?>
                <?php
                                    $this->widget('BugitorMenu', array(
                                        'items' => array(
                                            array('label' => 'Welcome', 'url' => array('/site/index'), 'id' => 'site/index'),
                                            array('label' => 'Projects', 'url' => array('/projects/'), 'id' => 'project/index'),
                                        ),
                                    )); ?>
                <?php endif; ?>
                                </div>
                                <hr/>
            <?php echo $content; ?>
                                    <hr/>
                                    <div class="span-24 alt"><div align="center" class="quiet">
                                            Copyright &copy; 2010 by Ogitor Team.<br/>
                    <a href="http://www.yiiframework.com/" rel="external"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/yii_power_lightgrey_white.gif" alt="Powered by Yii Framework" title="Powered by Yii Framework"/></a><hr/></div>
            </div>
        </div>
    </body>
</html>