<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;

	        $this->performAjaxValidation($model);

			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
					$this->lastViset();

                    if(is_null(Yii::app()->getUser()->returnUrl)) {
                        $this->redirect(Yii::app()->request->getUrlReferrer());
                    } else {
                        $this->redirect(Yii::app()->getUser()->returnUrl);
                    }
				}
			}
			// display the login form
			$this->render('/user/login',array('model'=>$model,));
		} else {
            if(is_null(Yii::app()->getUser()->returnUrl)) {
                $this->redirect(Yii::app()->request->getUrlReferrer());
            } else {
                $this->redirect(Yii::app()->getUser()->returnUrl);
            }
        }
	}

	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}