<?php

class UserModule extends CWebModule
{
	/**
	 * @var int
	 * @desc items on page
	 */
	public $user_page_size = 10;
	
	/**
	 * @var int
	 * @desc items on page
	 */
	public $fields_page_size = 10;
	
	/**
	 * @var string
	 * @desc hash method (md5,sha1 or algo hash function http://www.php.net/manual/en/function.hash.php)
	 */
	public $hash='md5';
	
	/**
	* @property string the path to the layout file to use for displaying Rights.
	*/
	public $layout = 'user.views.layouts.main';
	/**
	* @property string the path to the application layout file.
	*/
	public $appLayout = 'application.modules.admin.views.layouts.main';

        /**
	 * @var boolean
	 * @desc use email for activation user account
	 */
	public $sendActivationMail=true;
	
	/**
	 * @var boolean
	 * @desc allow auth for is not active user
	 */
	public $loginNotActiv=false;
	
	/**
	 * @var boolean
	 * @desc activate user on registration (only $sendActivationMail = false)
	 */
	public $activeAfterRegister=false;
	
	/**
	 * @var boolean
	 * @desc login after registration (need loginNotActiv or activeAfterRegister = true)
	 */
	public $autoLogin=true;
	
	public $registrationUrl = array("/user/registration");
	public $recoveryUrl = array("/user/recovery/recovery");
	public $loginUrl = array("/user/login");
	public $logoutUrl = array("/user/logout");
	public $profileUrl = array("/user/profile");
	public $returnUrl = array("/site/index");
	public $returnLogoutUrl = array("/project/index");
	
	/**
	 * @var array
	 * @desc User model relation from other models
	 * @see http://www.yiiframework.com/doc/guide/database.arr
	 */
	public $relations = array();
	
	/**
	 * @var array
	 * @desc Profile model relation from other models
	 */
	public $profileRelations = array();
	
	/**
	 * @var boolean
	 */
	public $captcha = array('registration'=>true);
	
	/**
	 * @var boolean
	 */
	//public $cacheEnable = false;
	
	public $tableUsers = '{{users}}';
	public $tableProfiles = '{{profiles}}';
	public $tableProfileFields = '{{profiles_fields}}';
	
	private $_user;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'user.models.*',
			'user.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	/**
	 * @param $str
	 * @param $params
	 * @param $dic
	 * @return string
	 */
	public static function t($str='',$params=array(),$dic='user') {
		return Yii::t("UserModule.".$dic, $str, $params);
	}
	
	/**
	 * @return hash string.
	 */
	public static function encrypting($string="") {
		$hash = Yii::app()->controller->module->hash;
		if ($hash=="md5")
			return md5($string);
		if ($hash=="sha1")
			return sha1($string);
		else
			return hash($hash,$string);
	}
	
	/**
	 * @param $place
	 * @return boolean 
	 */
	public static function doCaptcha($place = '') {
		if(!extension_loaded('gd'))
			return false;
		if (in_array($place, Yii::app()->controller->module->captcha))
			return Yii::app()->controller->module->captcha[$place];
		return false;
	}
	
	/**
	 * Return admin status.
	 * @return boolean
	 */
	public static function isAdmin() {
		if(Yii::app()->user->isGuest)
			return false;
		else {
			if(User::model()->active()->superuser()->findbyPk(Yii::app()->user->id))
				return true;
			else
				return false;
		}
	}

	/**
	 * Return admins.
	 * @return array syperusers names
	 */	
	public static function getAdmins() {
		$admins = User::model()->active()->superuser()->findAll();
		$return_name = array();
		foreach ($admins as $admin)
			array_push($return_name,$admin->username);
		return $return_name;
	}
	
	/**
	 * Send mail method
	 */
	public static function sendMail($email,$subject,$message) {
            $adminEmail = Yii::app()->params['adminEmail'];
            $adminEmailText = Yii::app()->params['adminEmailText'];
	    $yii_mail = new YiiMailMessage;
            $yii_mail->setSubject($subject);
            $yii_mail->setSender(array($adminEmail => $adminEmailText));
            $yii_mail->setFrom(array($adminEmail => $adminEmailText));
            $yii_mail->setText($message);
            $yii_mail->addTo($email);
            Yii::app()->mail->send($yii_mail);
	}
	
	/**
	 * Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public static function user($id=0) {
		if ($id) 
			return User::model()->active()->findbyPk($id);
		else {
			if(Yii::app()->user->isGuest)
				return false;
			else 
				return User::model()->active()->findbyPk(Yii::app()->user->id);
		}
	}
	
	/**
	 * Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public function users() {
		return User::model();
	}
}
