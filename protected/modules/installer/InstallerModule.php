<?php

class InstallerModule extends CWebModule
{
	/**
	* @property boolean whether to enable debug mode.
	*/
	public $debug = false;

	private $_assetsUrl;
        
        public function init()
	{
                $this->layout = '//installer/layouts/main';
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'installer.models.*',
			'installer.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
                        $controller->layout = 'main';
			// this method is called before any module controller action is performed
			// you may place customized code here
			return parent::beforeControllerAction($controller, $action);
	}
	/**
	* Publishes the module assets path.
	* @return string the base URL that contains all published asset files of Rights.
	*/
	public function getAssetsUrl()
	{
		if( $this->_assetsUrl===null )
		{
			$assetsPath = Yii::getPathOfAlias('installer.assets');

			// We need to republish the assets if debug mode is enabled.
			if( $this->debug===true )
				$this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
			else
				$this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
		}

		return $this->_assetsUrl;
	}
	/**
	* Registers the necessary scripts.
	*/
	public function registerScripts()
	{
		// Get the url to the module assets
		$assetsUrl = $this->getAssetsUrl();

		// Register the necessary scripts
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCoreScript('jquery.ui');
		$cs->registerCssFile($assetsUrl.'/wizard.css');
	}
}
