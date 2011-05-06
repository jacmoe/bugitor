<?php

class InstallerModule extends CWebModule
{
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
}
