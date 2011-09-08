<?php
/* /////////////////////////////////////////////////////////////////////////
  // This file is part of
  //      _
  //     | | __ _  ___ _ __ ___   ___   ___  ___
  //  _  | |/ _` |/ __| '_ ` _ \ / _ \ / _ \/ __|
  // | |_| | (_| | (__| | | | | | (_) |  __/\__ \
  //  \___/ \__,_|\___|_| |_| |_|\___/ \___||___/
  //                                             personal blogging software
  // Copyright (c) 2010-2011 by Jacob 'jacmoe' Moen
  // License: GNU General Public License (GPL) - see root_dir/license.txt for details
  // Credits: see root_dir/credits.txt
  // Homepage: http://www.jacmoe.dk/page/jacmoes
  // Repository: http://bitbucket.org/jacmoe/jacmoes
  ///////////////////////////////////////////////////////////////////////// */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <!-- Framework CSS -->
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" type="text/css" media="screen, projection"/>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/plugins/fancy-type/screen.css" type="text/css" media="screen, projection"/>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" type="text/css" media="print"/>
        <!--[if lt IE 8]><link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" type="text/css" media="screen, projection"/><![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/yii_style.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/menu.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/wizard.css" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body id="toc-top">
        <div class="container">
                <div class="column span-24">
                    <div id="title">
                        <hr/>
                            <?php $grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=" .
                                md5('jacmoe@mail.dk') . "&size=" . '64';
                            echo '<img class="big_gravatar" title="'.Yii::app()->name . ' ' . Yii::app()->params['description'] . '" src="'.$grav_url.'"/>'; ?>
                            <h1><?php echo CHtml::link(CHtml::encode(Yii::app()->name), $this->createUrl('/post/index'), array('class' => 'title')) ?> <span class="alt"><?php echo Yii::app()->params['description'] ?></span></h1>
                        <hr/>
                    </div>
                </div><!-- header -->
                <hr class="space"/>
            <?php echo $content; ?>
            <div class="block">
                <div class="column span-24 last">
                    <hr class="space"/>
                    <hr/>
                    <div style="text-align:center;">
                        <a href="http://www.jacmoe.dk/page/jacmoes"><img title="Powered by jacmoes" alt="Powered by jacmoes" src="<?php echo Yii::app()->request->baseUrl ?>/images/jacmoes.png"/></a><br/>
                        <div style="font-size: 75%;font-style: italic;">Copyright &copy; 2010-2011 by Jacob 'jacmoe' Moen<br/>All Rights Reserved.<br/></div>
                        <a href="http://www.yiiframework.com"><img title="Powered by Yii Framework" alt="Powered by Yii Framework" src="<?php echo Yii::app()->request->baseUrl ?>/images/yii-powered.png"/></a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
