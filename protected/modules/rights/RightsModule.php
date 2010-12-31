<?php
/**
* Rights module class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @version 0.9.11
*/
class RightsModule extends CWebModule
{
	/**
	* @property string the name of the role with superuser priviledges.
	*/
	public $superuserName = 'Admin';
	/**
	* @property string the name of the guest role.
	*/
	public $authenticatedName = 'Authenticated';
	/**
	* @property string the name of the guest role.
	*/
	public $guestName = 'Guest';
	/**
	* @property array list of default roles.
	*/
	public $defaultRoles = null;
	/**
	* @property string the name of the user model class.
	*/
	public $userClass = 'User';
	/**
	* @property string the name of the id column in the user table.
	*/
	public $userIdColumn = 'id';
	/**
	* @property string the name of the username column in the user table.
	*/
	public $userNameColumn = 'username';
	/**
	* @property boolean whether to enable business rules.
	*/
	public $enableBizRule = true;
	/**
	* @property boolean whether to enable data for business rules.
	*/
	public $enableBizRuleData = false;
	/**
	* @property string the flash message key to use for success messages.
	*/
	public $flashSuccessKey = 'RightsSuccess';
	/**
	* @property string the flash message key to use for error messages.
	*/
	public $flashErrorKey = 'RightsError';
	/**
	* @property boolean whether to install rights when accessed.
	*/
	public $install = false;
	/**
	* @property string the base url to Rights. Override when module is nested.
	*/
	public $baseUrl = '/rights';
	/**
	* @property string that path to the layout file to use for displaying Rights.
	*/
	public $layout;
	/**
	* @property string the style sheet file to use for Rights.
	*/
	public $cssFile;
	/**
	* @property boolean whether ot enable debug mode.
	*/
	public $debug = false;

	private $_assetsUrl;

	/**
	* Initializes the "rights" module.
	*/
	public function init()
	{
		// Set required classes for import
		$this->setImport(array(
			'rights.models.*',
			'rights.components.*',
			'rights.components.dataproviders.*',
			'rights.controllers.*',
		));

		// Set the user identity guest name
		Yii::app()->getUser()->guestName = $this->guestName;

		// Set guest role as the default
		// if the default roles are not set
		if( $this->defaultRoles===null )
			$this->defaultRoles = array($this->guestName);

		// Set the components component
		$this->setComponents(array(
			'authorizer'=>array(
				'class'=>'RightsAuthorizer',
				'superuserName'=>$this->superuserName,
				'defaultRoles'=>$this->defaultRoles,
			),
			'generator'=>array(
				'class'=>'RightsGenerator',
			),
		));

		$this->defaultController = 'assignment';

		// Set the installer if necessary
		if( $this->install===true )
		{
			$this->setComponents(array(
				'installer'=>array(
					'class'=>'RightsInstaller',
					'superuserName'=>$this->superuserName,
					'authenticatedName'=>$this->authenticatedName,
					'guestName'=>$this->guestName,
					'defaultRoles'=>$this->defaultRoles,
				),
			));

			$this->defaultController = 'install';
		}

		// Default layout is used unless one is provided
		if( $this->layout===null )
			$this->layout = 'rights.views.layouts.rights';
	}

	/**
	* Registers the necessary scripts.
	*/
	public function registerScripts()
	{
		// Publish the asset path
		$assetsUrl = $this->getAssetsUrl();

		// Register the necessary scripts
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCoreScript('jquery.ui');
		$cs->registerScriptFile($assetsUrl.'/js/rights.js');
		$cs->registerCssFile($assetsUrl.'/css/core.css');

		// Make sure we want to register a style sheet
		if( $this->cssFile!==false )
		{
			// Default style sheet is used unless one is provided
			if( $this->cssFile===null )
				$this->cssFile = $assetsUrl.'/css/default.css';
			else
				$this->cssFile = Yii::app()->request->baseUrl.$this->cssFile;

			// Register the style sheet
			$cs->registerCssFile($this->cssFile);
		}
	}

	/**
	* Publishes the module assets path.
	* @return string the base URL that contains all published asset files of Rights.
	*/
	public function getAssetsUrl()
	{
		if( $this->_assetsUrl===null )
		{
			$assetsPath = Yii::getPathOfAlias('rights.assets');

			// We need to republish the assets if debug mode is enabled
			if( $this->debug===true )
				$this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
			else
				$this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
		}

		return $this->_assetsUrl;
	}

	/**
	* @return RightsAuthorizer the authorizer component.
	*/
	public function getAuthorizer()
	{
		return $this->getComponent('authorizer');
	}

	/**
	* @return RightsInstaller the installer component.
	*/
	public function getInstaller()
	{
		return $this->getComponent('installer');
	}

	/**
	* @return RightsGenerator the generator component.
	*/
	public function getGenerator()
	{
		return $this->getComponent('generator');
	}

	/**
	* @return the current version.
	*/
	public function getVersion()
	{
		return '0.9.11';
	}
}
