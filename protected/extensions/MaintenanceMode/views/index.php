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
        <div class="container" id="page">
            <div id="topmenu" class="span-24">
                <div id="topmenu" class="span-15">
                    <h3 style="color:white;">Maintenance Mode</h3>
                </div>
                <div id="topmenu" class="span-8 last">
                    <span class="right">
                        <?php
                        $this->widget('BugitorMenu', array(
                            'items' => array(
                                array('url' => Yii::app()->getModule('user')->loginUrl, 'label' => Yii::app()->getModule('user')->t("Login"), 'visible' => Yii::app()->user->isGuest, 'id' => 'user/login/login'),
                                array('url' => Yii::app()->getModule('user')->logoutUrl, 'label' => Yii::app()->getModule('user')->t("Logout") . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest, 'id' => 'user/logout/logout'),
                            ),
                        )); ?>
                    </span>
                </div>
            </div>
            <div id="mainmenu" class="span-24">
                <div id="header" class="span-24">
                    <div id="logo" class="span-2">
                        <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/images/bugitor_64.png','Powered by Bugitor', array('title' => 'Powered by Bugitor')) ?>
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
                                        array('email' => Yii::app()->getModule('user')->user()->email));
                            ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div id="mainmenu" class="span-20 last">
                                    </div>
                                </div>
                                <div class="clear">
                                    <br/>
                                </div>
                                    <br/>
                                <div class="span-24"><div align="center"><div id="message">
                                    <h1>Bugitor is off-line</h1>
                                    <b><?php echo Yii::app()->maintenanceMode->message; ?></b>
                                </div></div></div>
                                <div class="clear">
                                    <br/>
                                    <br/>
                                    <br/>
                                        <div class="span-24 alt"><div align="center" class="quiet">
                                        <hr/>
                                                Powered by <a href="http://bitbucket.org/jacmoe/bugitor">Bugitor</a> &copy; 2010 by Bugitor Team.<br/>
                                                <a href="http://www.yiiframework.com/" rel="external"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/yii_power_lightblue_white.gif" alt="Made with Yii Framework" title="Made with Yii Framework"/></a>
                                                <hr/></div>
            </div>
        </div>
    </body>
</html>