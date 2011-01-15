<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2010 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php

Yii::import('application.vendors.*');
require_once('Zend/Feed.php');
require_once('Zend/Feed/Rss.php');

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
        return 'index, view, activity, roadmap, issues, code, waitforclone, feed';
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

   function is_process_running($PID)
   {
       exec("ps -p $PID", $ProcessState);
       return(count($ProcessState) > 1);
   }

    public function actionWaitforclone() {

        while($this->is_process_running((int)Yii::app()->user->getState('pid')))
        {
            ob_flush();
            flush();
            sleep(1);
        }
        Yii::app()->user->setState('pid', 'none');
        $out = 1;
        echo json_encode($out);
        $this->redirect($this->route);
    }

    public function actionFeed($identifier) {
        $project = Project::model()->find('identifier=?', array($_GET['identifier']));
        $activities = ActionLog::model()->findRecentEntries(20, $project->id);
        //convert from an array of comment AR class instances to a name=>value array for Zend
        $entries = array();
        foreach ($activities as $activity) {
            $entries[] = array(
                'title' => $activity->subject,
                'link' => CHtml::encode(Yii::app()->getRequest()->getHostInfo('').$activity->url),
                'guid' => CHtml::encode(Yii::app()->getRequest()->getHostInfo('').$activity->url),
                'description' => CHtml::encode($activity->description),
                'lastUpdate' => strtotime($activity->when),
                'dc:creator' => $activity->author->username,
            );
        }
        //now use the Zend Feed class to generate the Feed
        // generate and render RSS feed
        $feed = Zend_Feed::importArray(array(
                    'title' => ucfirst($identifier) . ' Project Activities Feed',
                    'link' => $this->createAbsoluteUrl(''),
                    'atom:link' => $this->createAbsoluteUrl('', array('rel' => 'self')),
                    'charset' => 'UTF-8',
                    'entries' => $entries,
                        ), 'rss');
        $feed->send();
    }
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($identifier) {
        $project = Project::model()->with(array('issueOpenBugCount', 'issueBugCount', 'issueOpenFeatureCount', 'issueFeatureCount'))->find('identifier=?', array($_GET['identifier']));
        $_GET['projectname'] = $project->name;

        $this->render('view', array(
            'model' => $project,
            'members' => $project->getMembers(),
        ));
    }

    public function actionActivity($identifier) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'identifier = :identifier';
        $criteria->params = array('identifier' => $_GET['identifier']);
        $project = Project::model()->with('activities')->find($criteria);
        $_GET['projectname'] = $project->name;
        
        Yii::app()->clientScript->registerLinkTag(
            'alternate',
            'application/rss+xml',
            $this->createUrl('project/feed',array('identifier' => $project->identifier)));

        $this->render('activity', array(
            'model' => $project,
        ));
    }

    public function actionRoadmap($identifier) {

        //$this->layout = '//layouts/column2';

        $criteria = new CDbCriteria();
        $criteria->condition = 'identifier = :identifier';
        $criteria->params = array('identifier' => $_GET['identifier']);
        $criteria->group = 'versions.id, issues.closed, issues.id';
        $criteria->order = 'versions.id, issues.closed, issues.id DESC';

        $project = Project::model()->with(
                array('versions' => array('with' => array('issues')))
                )->find($criteria);

        $_GET['projectname'] = $project->name;

        $this->render('roadmap', array(
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
        if(!Yii::app()->user->getState('pid')=== 'none') {
            if(!$this->is_process_running((int)Yii::app()->user->getState('pid'))) {
                Yii::app()->user->setState('pid', 'none');
            }
        }

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
            $model->identifier = preg_replace( '/\s*/m', '', strtolower($model->name));
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
                $this->redirect(array('settings', 'identifier' => $model->identifier, 'tab' => 'information'));
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
        $dataProvider = new CActiveDataProvider('Project', array(
            'sort'=>array('defaultOrder'=>'t.name ASC'),
        ));
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
