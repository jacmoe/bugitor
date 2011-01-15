<?php
/**
* Rights installation controller class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.8
*/
class InstallController extends RController
{
	/**
	* @property RAuthorizer
	*/
	private $_authorizer;
	/**
	* @property RInstaller
	*/
	private $_installer;

	/**
	* Initializes the controller.
	*/
	public function init()
	{
		if( $this->module->install!==true )
			$this->redirect(Yii::app()->homeUrl);

		$this->_authorizer = $this->module->getAuthorizer();
		$this->_installer = $this->module->getInstaller();
		$this->layout = $this->module->layout;
		$this->defaultAction = 'run';

		// Register the scripts.
		$this->module->registerScripts();
	}

	/**
	* @return array action filters
	*/
	public function filters()
	{
		// Use access control when installed.
		return $this->_installer->installed===true ? array('accessControl') : array();
	}

	/**
	* Specifies the access control rules.
	* This method is used by the 'accessControl' filter.
	* @return array access control rules
	*/
	public function accessRules()
	{
		return array(
			array('allow', // Allow superusers to access Rights
				'actions'=>array(
					'confirm',
					'run',
					'ready',
				),
				'users'=>$this->_authorizer->getSuperusers(),
			),
			array('deny', // Deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	* Dislays the confirm overwrite page.
	*/
	public function actionConfirm()
	{
		$this->render('confirm');
	}

	/**
	* Installs the module.
	* @throws CHttpException if the user is not logged in.
	*/
	public function actionRun()
	{
		// Make sure the user is not a guest.
		if( Yii::app()->user->isGuest===false )
		{
			// Get the application web user.
			$user = Yii::app()->getUser();

			// Make sure that the module is not already installed.
			if( isset($_GET['confirm'])===true || $this->_installer->installed===false )
			{
				// Run the installer and check for an error.
				if( $this->_installer->run(true)===true )
				{
					// Mark the user to have superuser priviledges.
					$user->isSuperuser = true;
					$this->redirect(array('install/ready'));
				}

				// Set an error message.
				$user->setFlash($this->module->flashErrorKey,
					Rights::t('install', 'Installation failed.')
				);

				$this->redirect(Yii::app()->homeUrl);
			}
			// Module is already installed.
			else
			{
				$this->redirect(array('install/confirm'));
			}
		}
		// User is guest, deny access.
		else
		{
			$this->accessDenied(Rights::t('install', 'You must be logged in to install Rights.'));
		}
	}

	/**
	* Displays the install ready page.
	*/
	public function actionReady()
	{
		$this->render('ready');
	}
}
