<?php

class ProjectController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';
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
        $Criteria->select = "name, identifier";
        $results = Project::model()->findAll($Criteria);
        $project_list = array();
        foreach ($results as $result) {
            $project_list[$result->identifier] = $result->name;
        }
        return $project_list;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($identifier) {
        $project = Project::model()->find('identifier=?', array($_GET['identifier']));
        $_GET['projectname'] = $project->name;
        Yii::app()->clientScript->registerLinkTag(
            'alternate',
            'application/rss+xml',
            $this->createUrl('comment/feed',array('pid'=>$project->id)));

        $this->render('view', array(
            'model' => $project,
            'members' => $project->getMembers(),
        ));
    }

    public function actionActivity($identifier) {
        $project = Project::model()->find('identifier=?', array($_GET['identifier']));
        $_GET['projectname'] = $project->name;
        $this->render('activity', array(
            'model' => $project,
        ));
    }

    public function actionRoadmap($identifier) {
        $project = Project::model()->find('identifier=?', array($_GET['identifier']));
        $_GET['projectname'] = $project->name;
        $this->render('roadmap', array(
            'model' => $project,
        ));
    }

//	public function actionIssues($identifier)
//	{
//            $project=Project::model()->find('identifier=?',array($_GET['identifier']));
//            $this->render('issues',array(
//			'model'=>$project,
//		));
//	}
    public function actionNewIssue($identifier) {
        $project = Project::model()->find('identifier=?', array($_GET['identifier']));
        $_GET['projectname'] = $project->name;
        $this->render('newissue', array(
            'model' => $project,
        ));
    }

    public function actionCode($identifier) {
        $project = Project::model()->find('identifier=?', array($_GET['identifier']));
        $_GET['projectname'] = $project->name;
        $this->render('code', array(
            'model' => $project,
        ));
    }

    public function actionSettings($identifier) {
        $information = Project::model()->find('identifier=?', array($_GET['identifier']));
        $_GET['projectname'] = $information->name;
        $members = $information->getMembers();
        $versions = $information->getVersions();
        $categories = $information->getCategories();
        $repositories = $information->getRepositories();

        $tabs = array(
                array('name' => 'information', 'partial' => 'update', 'label' =>  'Information'),
                array('name' =>  'members', 'partial' =>  'settings/members', 'label' =>  'Members'),
                array('name' =>  'versions', 'partial' =>  'settings/versions', 'label' => 'Versions'),
                array('name' =>  'categories', 'partial' =>  'settings/issue_categories', 'label' =>  'Issue categories'),
                array('name' =>  'repositories', 'partial' =>  'settings/repository', 'label' =>  'Repositories'),
        );
        $selected_tab = $tabs[0]['name'];
        if (isset($_GET['tab'])) {
            $selected_tab = $_GET['tab'];
        }

        $this->render('settings', compact('information', 'tabs', 'selected_tab',
                'members', 'versions', 'categories', 'repositories'));
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
            $sPattern = '/\s*/m';
            $sReplace = '';
            $model->identifier = preg_replace( $sPattern, $sReplace, strtolower($model->name));
            if ($model->save())
                $this->redirect(array('view', 'identifier' => $model->identifier));
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
            if ($model->save()) {
                Yii::app()->user->setFlash('success',"Project was succesfully updated");
                $this->redirect(array('settings', 'identifier' => $model->identifier, 'tab' => 'info'));
            }
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
        Yii::app()->clientScript->registerLinkTag('alternate',
        'application/rss+xml',
        $this->createUrl('comment/feed'));

        //Project::testTime();

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
