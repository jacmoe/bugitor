<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<title><?php  echo CHtml::encode($this -> pageTitle);?></title>
		<meta name="description" content="">
		<meta name="author" content="">
		<link type="text/plain" rel="author" href="<?php  echo Yii::app() -> baseUrl;?>/humans.txt" />
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
        <script src="<?php  echo Yii::app() -> theme -> baseUrl;?>/js/bootstrap-dropdown.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<link rel="stylesheet" type="text/css" href="<?php  echo Yii::app() -> theme -> baseUrl;?>/css/screen.css" />
		<!-- Favicons
		================================================== -->
		<link rel="shortcut icon" href="<?php  echo Yii::app() -> theme -> baseUrl;?>/images/favicon.ico">
		<link rel="apple-touch-icon" href="<?php  echo Yii::app() -> theme -> baseUrl;?>/images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php  echo Yii::app() -> theme -> baseUrl;?>/images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php  echo Yii::app() -> theme -> baseUrl;?>/images/apple-touch-icon-114x114.png">
		<script type="text/javascript" src="<?php  echo Yii::app() -> theme -> baseUrl;?>/js/branch_renderer.js"></script>
	</head>
	<body>
		<?php
		/*
		 * This file is part of
		 *     ____              _ __
		 *    / __ )__  ______ _(_) /_____  _____
		 *   / __  / / / / __ `/ / __/ __ \/ ___/
		 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
		 * /_____/\__,_/\__, /_/\__/\____/_/
		 *             /____/
		 * A Yii powered issue tracker
		 * http://bitbucket.org/jacmoe/bugitor/
		 *
		 * Copyright (C) 2009 - 2011 Bugitor Team
		 *
		 * Permission is hereby granted, free of charge, to any person
		 * obtaining a copy of this software and associated documentation files
		 * (the "Software"), to deal in the Software without restriction,
		 * including without limitation the rights to use, copy, modify, merge,
		 * publish, distribute, sublicense, and/or sell copies of the Software,
		 * and to permit persons to whom the Software is furnished to do so,
		 * subject to the following conditions:
		 * The above copyright notice and this permission notice shall be included
		 * in all copies or substantial portions of the Software.
		 *
		 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
		 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
		 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
		 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
		 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
		 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
		 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
		 */
		$items = array();
		?>
		<div id="page">
            <div id="system-menu"><?php //$this->widget('LinkMenu');?></div>
			<header role="banner">
				<nav id="topmenu">
    <div class="topbar" >
      <div class="topbar-inner">
        <div class="menu-container">
					<span id="logo">
							<?php
								if (
								((Yii::app()->controller->id === 'project')
								|| (Yii::app()->controller->id === 'issue')
								|| (Yii::app()->controller->id === 'member')
								|| (Yii::app()->controller->id === 'changeset')
								|| (Yii::app()->controller->id === 'milestone')
								|| (Yii::app()->controller->id === 'projectLink')
								|| (Yii::app()->controller->id === 'issueCategory')
								|| (Yii::app()->controller->id === 'repository')
								) && (isset($_GET['projectname']))) :
							?>
							<span class="alt"><?php  echo '<a class="brand">'.CHtml::encode($_GET['projectname']).'</a>';?></span>
							<?php  else :?>
							<span class="alt"><?php  echo '<a class="brand">'.CHtml::encode(Yii::app() -> name).'</a>';?></span>
							<?php  endif;?>
					</span>
					<span id="mainmenu">
						<?php
							if (((Yii::app()->controller->id === 'project')
							|| (Yii::app()->controller->id === 'changeset')
							|| (Yii::app()->controller->id === 'milestone')
							|| (Yii::app()->controller->id === 'member')
							|| (Yii::app()->controller->id === 'projectLink')
							|| (Yii::app()->controller->id === 'repository')
							|| (Yii::app()->controller->id === 'issueCategory')
							|| (Yii::app()->controller->id === 'issue')) && (isset($_GET['identifier']))) :
						?>
						<?php
						$items = array( array('label' => 'Overview', 'url' => array('/projects/' . $_GET['identifier']), 'id' => 'project/view'), array('label' => 'Activity', 'url' => array('/projects/' . $_GET['identifier'] . '/activity'), 'id' => 'project/activity'), array('label' => 'Roadmap', 'url' => array('/projects/' . $_GET['identifier'] . '/roadmap'), 'id' => 'project/roadmap'), array('label' => 'Issues', 'url' => array('/projects/' . $_GET['identifier'] . '/issues'), 'id' => 'issue/index'), array('label' => 'New Issue', 'url' => array('/projects/' . $_GET['identifier'] . '/issue/create'), 'visible' => !Yii::app() -> user -> isGuest, 'id' => 'issue/create'), array('label' => 'Code', 'url' => array('/projects/' . $_GET['identifier'] . '/code'), 'id' => 'project/code'), array('label' => 'Settings', 'url' => array('/projects/' . $_GET['identifier'] . '/settings'), 'visible' => Yii::app() -> user -> checkAccess('Project.Settings') === true, 'id' => 'project/settings'), );
						?>
						<?php  else :?>
						<?php
						$items = array(
						  array('label' => 'Projects', 'url' => array('/projects/'), 'id' => 'project/index'), );
						?>
						<?php  endif;?>
					</span>
					<span id="user-menu">
						<?php $this->widget('BootTopbar', array(
						'items' => $items,
						'items2' => array(
                            array(  'url' => '#',
                                    'label' => 'test',
                                    'visible' => true,
                                    'id' => 'none',
                                'items' => array(
                                                array(  'url' => Yii::app() -> getModule('user') -> loginUrl,
                                                        'label' => Yii::app() -> getModule('user') -> t("Login"),
                                                        'visible' => Yii::app() -> user -> isGuest,
                                                        'id' => 'user/login/login'),
                                                array(  'url' => Yii::app() -> getModule('user') -> registrationUrl,
                                                        'label' => Yii::app() -> getModule('user') -> t("Register"),
                                                        'visible' => Yii::app() -> user -> isGuest,
                                                        'id' => 'user/registration/registration'),
                                                array(  'url' => Yii::app() -> getModule('user') -> profileUrl,
                                                        'label' => Yii::app() -> getModule('user') -> t("Profile"),
                                                        'visible' => !Yii::app() -> user -> isGuest,
                                                        'id' => 'user/profile/profile'),
                                                array(  'url' => Yii::app() -> getModule('user') -> logoutUrl,
                                                        'label' => Yii::app() -> getModule('user') -> t("Logout") . ' (' . Yii::app() -> user -> name . ')',
                                                        'visible' => !Yii::app() -> user -> isGuest,
                                                        'id' => 'user/logout/logout'
                                                    ),
                                            ),
                                ),
                        ),
								)
							);
						?>
					</span>
                    <span id="user-gravatar">
                        <?php if (!Yii::app()->user->isGuest): ?>
                        <?php
                            $this->widget('application.extensions.VGGravatarWidget', array('size' => '24px','email' => Yii::app() -> getModule('user') -> user() -> email));
                        ?>
                        <?php  endif;?>
                    </span>
        </div>
      </div>
    </div>
				<!-- #topmenu //-->
				</nav>
			</header>
			<div role="main">
				<?php  $this -> widget('ext.ELocalTimeago.ELocalTimeago', array('localtimeago' => 'MMM dd, yyyy HH:mm zzz'));?>
				<?php  $this -> widget('ext.EHighlight.EHighlight');?>
				<?php
				Yii::app() -> clientScript -> registerScript('myHideEffect', '$(".info").animate({opacity: 1.0}, 4000).fadeOut("slow");', CClientScript::POS_READY);
				?>
				<?php
					$user = Yii::app()->getUser();
					foreach ($user->getFlashKeys() as $key):
					if ($user->hasFlash($key)):
				?>
				<br/>
				<div class="info flash-<?php  echo $key;?>">
					<?php  echo $user -> getFlash($key);?>
				</div>
				<?php
				endif;
				endforeach;
				?>
				<div id="content">
					<?php echo $content;?>
				</div>
			</div>
			<!-- #page //-->
			<footer id="footer">
				<div align="center" class="quiet">
					<hr/>
					Powered by <a class="noicon" title="Bugitor - The Yii-powered issue tracker" href="http://bitbucket.org/jacmoe/bugitor">Bugitor</a> &copy; 2010 - 2011 by Bugitor Team.
					<br/>
					<a class="noicon" href="http://www.yiiframework.com/" rel="external"><img src="<?php echo Yii::app()->theme->baseUrl ?>/css/images/yii_power_lightblue_white.gif" alt="Made with Yii Framework" title="Made with Yii Framework"/></a>
					<hr/>
				</div>
			</footer>
		</div>
		<!-- container //-->
	</body>
</html>