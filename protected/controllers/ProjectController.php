<?php

class ProjectController extends RightsBaseController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $defaultAction = 'index';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions() {
        return 'index, view, activity, roadmap, issues, code';
    }

    public function getProjects() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name";
        $results = Project::model()->findAll($Criteria);
        $project_list = array();
        foreach ($results as $result) {
            $project_list[$result->name] = $result->name;
        }
        return $project_list;
    }

    /**
     * Returns an array of available roles in which a user can be
      placed when being added to a project
     */
    public static function getUserRoleOptions() {
        return CHtml::listData(Rights::module()->getAuthorizer()->getRoles(),
                'name', 'name');
    }

    /**
     * Makes an association between a user and a the project
     */
    public function associateUserToProject($user) {
        $sql = "INSERT INTO bug_project_user_assignment (project_id,
user_id) VALUES (:projectId, :userId)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
        $command->bindValue(":userId", $user->id, PDO::PARAM_INT);
        return $command->execute();
    }

    /*
     * Determines whether or not a user is already part of a project
     */
    public function isUserInProject($user) {
        $sql = "SELECT user_id FROM tbl_project_user_assignment WHERE
project_id=:projectId AND user_id=:userId";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
        $command->bindValue(":userId", $user->id, PDO::PARAM_INT);
        return $command->execute() == 1 ? true : false;
    }

    public function actionAdduser() {
        $form = new ProjectUserForm;
        $project = $this->loadModel();
        // collect user input data
        if (isset($_POST['ProjectUserForm'])) {
            $form->attributes = $_POST['ProjectUserForm'];
            $form->project = $project;
            // validate user input and set a sucessfull flassh message if valid
            if ($form->validate()) {
                Yii::app()->user->setFlash('success', $form->username .
                        " has been added to the project.");
                $form = new ProjectUserForm;
            }
        }
        // display the add user form
        $users = User::model()->findAll();
        $usernames = array();
        foreach ($users as $user) {
            $usernames[] = $user->username;
        }
        $form->project = $project;
        $this->render('adduser', array('model' => $form,
            'usernames' => $usernames));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($name) {
        $project = Project::model()->find('name=?', array($_GET['name']));
        $this->render('view', array(
            'model' => $project,
        ));
    }

    public function actionActivity($name) {
        $project = Project::model()->find('name=?', array($_GET['name']));
        $this->render('activity', array(
            'model' => $project,
        ));
    }

    public function actionRoadmap($name) {
        $project = Project::model()->find('name=?', array($_GET['name']));
        $this->render('roadmap', array(
            'model' => $project,
        ));
    }

//	public function actionIssues($name)
//	{
//            $project=Project::model()->find('name=?',array($_GET['name']));
//            $this->render('issues',array(
//			'model'=>$project,
//		));
//	}
    public function actionNewIssue($name) {
        $project = Project::model()->find('name=?', array($_GET['name']));
        $this->render('newissue', array(
            'model' => $project,
        ));
    }

    public function actionCode($name) {
        $project = Project::model()->find('name=?', array($_GET['name']));
        $this->render('code', array(
            'model' => $project,
        ));
    }

    public function actionSettings($name) {
        $project = Project::model()->find('name=?', array($_GET['name']));
        $this->render('settings', array(
            'model' => $project,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Project;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Project'])) {
            $model->attributes = $_POST['Project'];
            if ($model->save())
                $this->redirect(array('view', 'name' => $model->name));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Project'])) {
            $model->attributes = $_POST['Project'];
            if ($model->save())
                $this->redirect(array('view', 'name' => $model->name));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Project');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Project('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Project']))
            $model->attributes = $_GET['Project'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Project::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'project-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
