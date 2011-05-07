<?php

class DefaultController extends CController
{
	public function init()
	{
		// Register the scripts
		$this->module->registerScripts();
	}
	
        public function actionIndex()
	{
            $this->layout = 'main';
            $this->render('index');
	}
}