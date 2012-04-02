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
 * Copyright (C) 2009 - 2012 Bugitor Team
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

    private function runStuff() {
        $commandPath = Yii::app()->getBasePath().DIRECTORY_SEPARATOR.'commands';
        $runner=new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $args = array('handlerepositories', '2', 'nolock');
        $command = $runner->createCommand($args[0]);
        array_shift($args);
        $command->run($args);
    }
    
/*    public function beforeAction($action) {
        $time = 600;
        $fake_cron_last_exec_time = Yii::app()->config->get('fakecron_last_exec_time');
        if(isset($fake_cron_last_exec_time)) {
            if((time() - $fake_cron_last_exec_time) >= $time) {
                $this->runStuff();
                Yii::app()->config->set('fakecron_last_exec_time', time());
            } else {
                echo 'Time until next run: '. ($time - (time() - $fake_cron_last_exec_time)) . '<br/>';
            }
        } else {
            Yii::app()->config->set('fakecron_last_exec_time', time());
        }
        return parent::beforeAction($action);
    }*/
    
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

    public function is_clone_running(){
        $directory = Yii::app()->file->set('repositories', true);
        $lock_file = $directory->getRealPath() . '/lock';
        //$lock_file = '/opt/lampp/htdocs/repositories/lock';
        return file_exists($lock_file);
    }

    public function actionWaitforclone() {

        $directory = Yii::app()->file->set('repositories', true);
        $lock_file = $directory->getRealPath() . '/lock';
        //$lock_file = '/opt/lampp/htdocs/repositories/lock';
        while(file_exists($lock_file))
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
        if(null === $project){
            throw new CHttpException(404,'The requested project does not exist.');
        }
        $activities = ActionLog::model()->findRecentEntries(20, $project->id);
        //convert from an array of comment AR class instances to a name=>value array for Zend
        $entries = array();
        foreach ($activities as $activity) {
            $entries[] = array(
                'title' => CHtml::encode($activity->subject),
                'link' => CHtml::encode(Yii::app()->getRequest()->getHostInfo('').$activity->url),
                'guid' => CHtml::encode(Yii::app()->getRequest()->getHostInfo('').$activity->url),
                'description' => CHtml::encode(ucfirst($activity->author->username)) . ' : ' . CHtml::encode($activity->description),
                'lastUpdate' => strtotime($activity->theDate),
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
        if(null === $project){
            throw new CHttpException(404,'The requested project does not exist.');
        }
        $_GET['projectname'] = $project->name;

        Yii::app()->clientScript->registerLinkTag(
            'alternate',
            'application/rss+xml',
            $this->createUrl('project/feed',array('identifier' => $project->identifier)));

        $this->render('view', array(
            'model' => $project,
            'members' => $project->getMembers(),
        ));
    }

    public function actionActivity($identifier) {
        
        //$this->layout = '//layouts/activity2';
        
        $criteria = new CDbCriteria;
        $criteria->condition = 'identifier = :identifier';
        $criteria->params = array('identifier' => $_GET['identifier']);
        
        // Sourceforge does not like 'with activities' ...
        //$project = Project::model()->with('activities')->find($criteria);
        $project = Project::model()->find($criteria);
        if(null === $project){
            throw new CHttpException(404,'The requested project does not exist.');
        }
        
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

        $this->layout = '//layouts/roadmap2';

        $criteria = new CDbCriteria();
        $criteria->condition = 'identifier = :identifier';
        $criteria->params = array('identifier' => $_GET['identifier']);
        $criteria->order = 'milestones.effective_date';

        // Sourceforge dies on milestones with issues..
        //$project = Project::model()->with(
        //        array('milestones' => array('with' => array('issues')))
        //        )->find($criteria);
        $project = Project::model()->with(
                array('milestones')
                )->find($criteria);

        if(null === $project){
            throw new CHttpException(404,'The requested project does not exist.');
        }
        
        $_GET['projectname'] = $project->name;

        $this->render('roadmap', array(
            'model' => $project,
        ));
    }

    public function actionCode($identifier) {
        $criteria = new CDbCriteria();
        $criteria->compare('t.identifier', array($_GET['identifier']));
        $criteria->limit = 25;
        $criteria->order = 'changesets.commit_date DESC';

        $project = Project::model()->with(
                array('repositories' =>
                    array('with' => 'changesets')
                    )
                )->together()->find($criteria);

        if(null === $project){
            throw new CHttpException(404,'The requested project does not exist.');
        }
        

        $_GET['projectname'] = $project->name;
        $this->render('code', array(
            'model' => $project,
        ));
    }

    public function actionSettings($identifier) {
        $this->block_robots = true;
        if(!Yii::app()->user->getState('pid')=== 'none') {
            if(!$this->is_clone_running()) {
                Yii::app()->user->setState('pid', 'none');
            }
        }

        $information = Project::model()->find('identifier=?', array($_GET['identifier']));
        $_GET['projectname'] = $information->name;
        $members = $information->getMembers();
        $milestones = $information->getMilestones();
        $categories = $information->getCategories();
        $repositories = $information->getRepositories();
        $links = $information->getLinks();

        $tabs = array(
                array('name' => 'information', 'partial' => 'update', 'label' =>  'Information'),
                array('name' => 'links', 'partial' => 'settings/links', 'label' =>  'Links'),
                array('name' =>  'members', 'partial' =>  'settings/members', 'label' =>  'Members'),
                array('name' =>  'milestones', 'partial' =>  'settings/milestones', 'label' => 'Milestones'),
                array('name' =>  'categories', 'partial' =>  'settings/issue_categories', 'label' =>  'Issue categories'),
                array('name' =>  'repositories', 'partial' =>  'settings/repository', 'label' =>  'Repositories'),
        );
        $selected_tab = $tabs[0]['name'];
        if (isset($_GET['tab'])) {
            $selected_tab = $_GET['tab'];
        }

        $this->render('settings', compact('information', 'tabs', 'selected_tab',
                'members', 'milestones', 'categories', 'repositories', 'links'));
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
                $this->redirect(array('view', 'id' => $model->id, 'identifier' => $model->identifier));
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
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('project/index'));
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

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->block_robots = true;
        $this->layout = 'admin.views.layouts.main';
        
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
