<?php

class IssueController extends RightsBaseController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions() {
        return 'index, view';
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $issue = $this->loadModel($id, true);
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
        if (isset($_GET['name']))
            $project_name = $_GET['name'] . ' - ';
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Issue'])) {
            $model->attributes = $_POST['Issue'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id, 'name' => $model->project->name));
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
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Issue'])) {
            $model->attributes = $_POST['Issue'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id, 'name' => $model->project->name));
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
        $Criteria->select = "name";
        $results = Project::model()->findAll($Criteria);
        $project_list = array();
        foreach ($results as $result) {
            $project_list[$result->name] = $result->name;
        }
        return $project_list;
    }

    /**
     * Lists all models.
     */
    public function actionIndex($name = '') {
        if ($name !== '') {
            $criteria = new CDbCriteria;
            $criteria->compare('project_id', $this->getProject($name), true);
            $dataProvider = new CActiveDataProvider('Issue', array('criteria' => $criteria));
            $this->render('index', array(
                'dataProvider' => $dataProvider,
                'project_name' => $name . ' - ',
            ));
        } else {
            $dataProvider = new CActiveDataProvider('Issue');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
                'project_name' => '',
            ));
        }
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

    private function getProject($name) {
        $project = Project::model()->findByAttributes(array('name' => $name));
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
