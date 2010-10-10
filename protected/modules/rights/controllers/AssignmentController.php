<?php
/**
* Rights assignment controller class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.1
*/
class AssignmentController extends Controller
{
	/**
	* @property RightsAuthorizer
	*/
	private $_authorizer;

	/**
	* Initializes the controller.
	*/
	public function init()
	{
		$this->_authorizer = $this->module->getAuthorizer();
		$this->layout = $this->module->layout;
		$this->defaultAction = 'view';

		// Register the scripts
		$this->module->registerScripts();
	}

	/**
	* @return array action filters
	*/
	public function filters()
	{
		return array('accessControl');
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
					'view',
					'user',
					'revoke',
				),
				'users'=>$this->_authorizer->getSuperusers(),
			),
			array('allow',  // Allow to view and revoke assignments if the user can manage them
				'actions'=>array(
					'view',
					'user',
					'revoke',
				),
				'roles'=>array('RightsAssignments'),
			),
			array('deny', // Deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	* Displays an overview of the users and their assignments.
	*/
	public function actionView()
	{
		// Get the user model class
		$userClass = $this->module->userClass;

		// Create a data provider for listing the users
		$dataProvider = new RightsActiveDataProvider($userClass, array(
			'pagination'=>array(
				'pageSize'=>20,
			),
			'behaviors'=>array(
				'rights'=>'RightsUserBehavior',
			),
		));

		// Render the view
		$this->render('view', array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	* Displays the authorization assignments for an user.
	*/
	public function actionUser()
	{
		// Create the user model and attach the required behavior
		$userClass = $this->module->userClass;
		$model = CActiveRecord::model($userClass)->findByPk($_GET['id']);
		$model->attachBehavior('rights', new RightsUserBehavior);

		$assignedItems = $this->_authorizer->getAuthItems(null, $model->getId(), null, true);
		$assignments = array_keys($assignedItems);

		// Make sure we have items to be selected
		$selectOptions = $this->_authorizer->getAuthItemSelectOptions(null, null, null, true, $assignments);
		if( $selectOptions!==array() )
		{
			// Create a from to add a child for the authorization item
		    $form = new CForm(array(
				'elements'=>array(
					'itemname'=>array(
						'label'=>false,
					    'type'=>'dropdownlist',
					    'items'=>$selectOptions,
					),
				),
				'buttons'=>array(
					'submit'=>array(
					    'type'=>'submit',
					    'label'=>Rights::t('core', 'Assign'),
					),
				),
			), new AssignmentForm);

		    // Form is submitted and data is valid, redirect the user
		    if( $form->submitted()===true && $form->validate()===true )
			{
				// Update and redirect
				$this->_authorizer->authManager->assign($form->model->itemname, $model->getId());
				$item = $this->_authorizer->authManager->getAuthItem($form->model->itemname);

				Yii::app()->user->setFlash($this->module->flashSuccessKey,
					Rights::t('core', ':name assigned.', array(':name'=>$item->getNameText()))
				);

				$this->redirect(array('assignment/user', 'id'=>$model->getId()));
			}
		}
		// No items available
		else
		{
		 	$form = null;
		}

		// Create a data provider for listing the assignments
		$dataProvider = new AuthItemDataProvider('assignments', array(
			'userId'=>$model->getId(),
		));

		// Render the view
		$this->render('user', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
			'form'=>$form,
		));
	}

	/**
	* Revokes an assignment from an user.
	*/
	public function actionRevoke()
	{
		// We only allow deletion via POST request
		if( Yii::app()->request->isPostRequest===true )
		{
			// Revoke the item from the user and load it
			$this->_authorizer->authManager->revoke($_GET['name'], $_GET['id']);
			$item = $this->_authorizer->authManager->getAuthItem($_GET['name']);

			// Set flash message for revoking the item
			Yii::app()->user->setFlash($this->module->flashSuccessKey,
				Rights::t('core', ':name revoked.', array(':name'=>$item->getNameText()))
			);

			// if AJAX request, we should not redirect the browser
			if( isset($_POST['ajax'])===false )
				$this->redirect(array('assignment/user', 'id'=>$_GET['id']));
		}
		else
		{
			throw new CHttpException(400, Rights::t('core', 'Invalid request. Please do not repeat this request again.'));
		}
	}
}
