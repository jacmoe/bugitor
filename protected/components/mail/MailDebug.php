<?php
/**
* @author Jonah Turnquist <poppitypop@gmail.com>
* @link http://www.yiiframework.com/
*/

/**
* The MailDebug widget used for debugging email messages.  It is recommended you use this in your layout file
*/
class MailDebug extends CWidget {
	public function run() {
		if(Yii::app()->user->hasFlash('mail')) {
			//register css file
			$url = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.mail.views.debug').'.css');
			Yii::app()->getClientScript()->registerCssFile($url);
			
			//dump debug info
			echo Yii::app()->user->getFlash('mail');
		}
	}
}