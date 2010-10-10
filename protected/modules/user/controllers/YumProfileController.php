<?php

class YumProfileController extends YumController
{
	const PAGE_SIZE=10;
	private $_model;

	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('index', 'create', 'update', 'view', 'admin','delete'),
				'users'=>array(Yii::app()->user->name),
				'expression' => 'Yii::app()->user->isAdmin()'
				),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionView()
	{
		$this->layout = YumWebModule::yum()->adminLayout;
		$this->render('view',array( 'model'=>$this->loadModel()));
	}

	public function actionCreate()
	{
		$this->layout = YumWebModule::yum()->adminLayout;
		$model = new YumProfile;

		if(isset($_POST['YumProfile']))
		{
			$model->attributes=$_POST['YumProfile'];

			if($model->validate()) 
			{
				$model->save();
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array( 'model'=>$model ));
	}

	public function actionUpdate()
	{
		$this->layout = YumWebModule::yum()->adminLayout;
		$model=$this->loadModel();
		if(isset($_POST['YumProfile']))
		{
			$model->attributes=$_POST['YumProfile'];
			
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array( 'model'=>$model ));
	}

	public function actionDelete()
	{
		$this->layout = YumWebModule::yum()->adminLayout;

		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = $this->loadModel();
			$model->delete();

			if(!isset($_POST['ajax']))
				$this->redirect(array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionIndex()
	{
		$this->actionAdmin();
	}

	public function actionAdmin()
	{
		$this->layout = YumWebModule::yum()->adminLayout;

		$dataProvider=new CActiveDataProvider('YumProfile', array(
			'pagination'=>array(
				'pageSize'=>self::PAGE_SIZE,
			),
			'sort'=>array(
				'defaultOrder'=>'profile_id',
			),
		));

		$this->render('admin',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * @return YumProfileField
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=YumProfile::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
