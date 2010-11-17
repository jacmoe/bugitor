<?php

class IssueController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions() {
        return 'index, view, reqTest03';
    }

    public function actionTestAjax() {
        if(Yii::app()->request->isAjaxRequest){
            $response = array(
                'test1' => 'Username',
                'test2' => 'Password'
            );
            echo json_encode($response);
        }
    }

    public function actionReqTest03() {
        if(Yii::app()->request->isAjaxRequest){
            if(isset($_POST['ids'])) {
                foreach($_POST['ids'] as $val) {
                    switch($_POST['type']) {
                        case 'priority':
                            $issue = $this->loadModel($val, true);
                            $issue->issue_priority_id = $_POST['val'];
                            $issue->save();
                            break;
                        case 'version':
                            $issue = $this->loadModel($val, true);
                            $issue->version_id = $_POST['val'];
                            $issue->save();
                            break;
                        case 'category':
                            $issue = $this->loadModel($val, true);
                            $issue->issue_category_id = $_POST['val'];
                            $issue->save();
                            break;
                        default:
                            break;
                    }
                    echo $val . '<br/>';
                }
            }
            if(isset($_POST['val'])) {
                echo $_POST['val'] . '<br/>';
            }
            if(isset($_POST['type'])) {
                echo $_POST['type'] . '<br/>';
            }
        }
    }


    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        if (isset($_GET['identifier'])){
            $_GET['projectname'] = Project::getProjectNameFromIdentifier($_GET['identifier']);
        }
        $this->layout = '//layouts/column1';
        $issue = Issue::model()->with(array('tracker','user', 'issueCategory', 'issuePriority', 'version', 'assignedTo', 'project'))->findByPk((int) $id);//$this->loadModel($id, true);
        $comment = $this->createComment($issue);
        $this->render('view', array(
            'model' => $issue,
            'comment' => $comment,
        ));
    }

    protected function createComment($issue) {
        $comment = new Comment;
        if (isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];
            if ($issue->addComment($comment)) {
                Yii::app()->user->setFlash('commentSubmitted', "Your comment has been added.");
                $this->refresh();
            }
        }
        return $comment;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Issue;

        $project_name = '';
        if (isset($_GET['identifier'])){
            $project_name = $_GET['projectname'] = Project::getProjectNameFromIdentifier($_GET['identifier']);
            $project_name .= ' - ';
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Issue'])) {
            $model->attributes = $_POST['Issue'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success',"Issue was succesfully created");
                $this->redirect(array('view', 'id' => $model->id, 'identifier' => $model->project->identifier));
            } else {
                Yii::app()->user->setFlash('error',"There was an error creating the issue.");
            }
        }

        $this->render('create', array(
            'model' => $model,
            'project_name' => $project_name,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->layout = '//layouts/column1';
        
        $model = Issue::model()->with('project')->findByPk((int) $id);//$this->loadModel($id);

        $_GET['projectname'] = $model->project->name;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Issue'])) {
            $model->attributes = $_POST['Issue'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success',"Issue was succesfully updated");
                $this->redirect(array('view', 'id' => $model->id, 'identifier' => $model->project->identifier));
            } else {
                Yii::app()->user->setFlash('error',"There was an error updating the issue");
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

    public function getTrackerFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name";
        $results = Tracker::model()->findAll($Criteria);
        $tracker_list = array();
        foreach ($results as $result) {
            $tracker_list[$result->name] = $result->name;
        }
        return $tracker_list;
    }

    public function getPriorityFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name";
        $results = IssuePriority::model()->findAll($Criteria);
        $priority_list = array();
        foreach ($results as $result) {
            $priority_list[$result->name] = $result->name;
        }
        return $priority_list;
    }

    public function getUserFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "username";
        $results = User::model()->findAll($Criteria);
        $user_list = array();
        foreach ($results as $result) {
            $user_list[$result->username] = $result->username;
        }
        return $user_list;
    }

    public function getVersionFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name";
        if (isset($_GET['identifier'])) {
            $Criteria->compare('project_id', $this->getProject($_GET['identifier']), true);
        }
        $results = Version::model()->findAll($Criteria);
        $version_list = array();
        foreach ($results as $result) {
            $version_list[$result->name] = $result->name;
        }
        return $version_list;
    }

    public function getCategoryFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name";
        if (isset($_GET['identifier'])) {
            $Criteria->compare('project_id', $this->getProject($_GET['identifier']), true);
        }
        $results = IssueCategory::model()->findAll($Criteria);
        $category_list = array();
        foreach ($results as $result) {
            $category_list[$result->name] = $result->name;
        }
        return $category_list;
    }

    public function getTrackerSelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, id";
        $results = Tracker::model()->findAll($Criteria);
        $tracker_list = array();
        foreach ($results as $result) {
            $tracker_list[$result->id] = $result->name;
        }
        return $tracker_list;
    }

    public function getPrioritySelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, id";
        $results = IssuePriority::model()->findAll($Criteria);
        $priority_list = array();
        foreach ($results as $result) {
            $priority_list[$result->id] = $result->name;
        }
        return $priority_list;
    }

    public function getUserSelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "username, id";
        $results = User::model()->findAll($Criteria);
        $user_list = array();
        foreach ($results as $result) {
            $user_list[$result->id] = $result->username;
        }
        return $user_list;
    }

    public function getVersionSelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, id";
        if (isset($_GET['identifier'])) {
            $Criteria->compare('project_id', $this->getProject($_GET['identifier']), true);
        }
        $results = Version::model()->findAll($Criteria);
        $version_list = array();
        foreach ($results as $result) {
            $version_list[$result->id] = $result->name;
        }
        return $version_list;
    }

    public function getCategorySelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, id";
        if (isset($_GET['identifier'])) {
            $Criteria->compare('project_id', $this->getProject($_GET['identifier']), true);
        }
        $results = IssueCategory::model()->findAll($Criteria);
        $category_list = array();
        foreach ($results as $result) {
            $category_list[$result->id] = $result->name;
        }
        return $category_list;
    }

    /**
     * Lists all models.
     */
    public function actionIndex($identifier = '') {
        if($identifier !== '') $_GET['projectname'] = Project::getProjectNameFromIdentifier($identifier);

        $issueFilter = array('1' => 'Open Issues', '2' => 'Closed Issues');
        //$_GET['issueFilter'] = '1';

        $model = new Issue('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Issue']))
            $model->attributes = $_GET['Issue'];

        // page size drop down changed
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);  // would interfere with pager and repetitive page size change
        }
        $this->render('index', array(
            'model' => $model,
            'issueFilter' => $issueFilter,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Issue('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Issue']))
            $model->attributes = $_GET['Issue'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $withComments=false) {

        if($withComments) {
            $model = Issue::model()->with(
                    array('comments' => array('with' => 'author'))
                    )->findbyPk((int)$id);
        } else {
            $model = Issue::model()->findByPk((int) $id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    private function getProject($identifier) {
        $project = Project::model()->findByAttributes(array('identifier' => $identifier));
        return $project->id;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'issue-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
