<?php

class LogoutController extends Controller
{
	public $defaultAction = 'logout';

	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogout()
	{
		$redirect_url = Yii::app()->user->rightsReturnUrl;
	    if(is_null($redirect_url)) {
	        $redirect_url = Yii::app()->request->getUrlReferrer();
	    }
		Yii::app()->user->logout();
        $this->redirect($redirect_url);
		// $this->redirect(Yii::app()->controller->module->returnLogoutUrl);
	}

}