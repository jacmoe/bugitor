<?php
	Yii::app()->clientscript
		->registerCssFile( Yii::app()->theme->baseUrl . '/css/bootstrap.css' )
		->registerCssFile( Yii::app()->theme->baseUrl . '/css/bootstrap-responsive.css' )
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $this->pageTitle; ?></title>
<meta name="description" content="">
<meta name="author" content="">

<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Le styles -->
<style>
	body {
		padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
	}

	@media (max-width: 980px) {
		body{
			padding-top: 0px;
		}
	}
</style>

<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico">
<!--Uncomment when required-->
<!--<link rel="apple-touch-icon" href="images/apple-touch-icon.png">-->
<!--<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">-->
<!--<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">-->
</head>

<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="#"><?php echo Yii::app()->name ?></a>
				<div class="nav-collapse">
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
                            array(  'url' => Yii::app() -> getModule('user')->profileUrl,
                                    'label' => Yii::app()->user->name,
                                    'visible' => !Yii::app()->user->isGuest,
                                    'id' => 'none',
                                'items' => array(
                                                array(  'url' => Yii::app() -> getModule('user') -> profileUrl,
                                                        'label' => Yii::app() -> getModule('user') -> t("Profile"),
                                                        'visible' => !Yii::app() -> user -> isGuest,
                                                        'id' => 'user/profile/profile'),
                                                array(  'url' => Yii::app() -> getModule('user') -> logoutUrl,
                                                        'label' => Yii::app() -> getModule('user') -> t("Logout") . ' (' . Yii::app() -> user -> name . ')',
                                                        'visible' => !Yii::app() -> user -> isGuest,
                                                        'id' => 'user/logout/logout'
                                                    ),
                                                array(  'label' => 'Administration',
                                                        'url' => array('/admin/default/index'),
                                                        'id' => 'admin/default/index',
                                                        'visible' => Yii::app()->user->checkAccess(Rights::module()->superuserName)),
                                            ),
                                ),
                            array(  'url' => '#',
                                    'label' => 'Login/Register',
                                    'visible' => Yii::app()->user->isGuest,
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
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>

	<div class="container">
		<?php echo $content ?>
	</div> <!-- /container -->
</body>
</html>
