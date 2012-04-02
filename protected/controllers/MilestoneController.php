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

class MilestoneController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights',
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
        if (isset($_GET['identifier'])) {
            $_GET['projectname'] = Project::getProjectNameFromIdentifier($_GET['identifier']);
        }
        $criteria = new CDbCriteria();
        $criteria->condition = 't.id = :id';
        $criteria->params = array('id' => $id);
        $criteria->group = 'issues.closed, issues.id';
        $criteria->order = 'issues.closed, issues.id DESC';
        $model = Milestone::model()->with(array('project', 'issues'))->find($criteria);
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($identifier) {
        $_GET['projectname'] = Project::getProjectNameFromIdentifier($identifier);

        $model = new Milestone;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Milestone'])) {
            $model->attributes = $_POST['Milestone'];
            if ($model->save())
                $this->redirect(array('project/settings', 'identifier' => $identifier, 'tab' => 'milestones'));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    private function getProject($identifier) {
        $project = Project::model()->findByAttributes(array('identifier' => $identifier));
        return $project->id;
    }
    
    private function dateadd($day, $toadd, $interval)
    {
        $tmp = explode("-", $day);
        switch ($interval) {
            case 'years':
                $tmp[0] = $tmp[0] + $toadd;
                break;
            case 'months':
                $tmp[1] = $tmp[1] + $toadd;
                break;
            case 'days':
                $tmp[2] = $tmp[2] + $toadd;
                break;
            default:
                $tmp[2] = $tmp[2] + $toadd;
                break;
        }
        $dadate = mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]);
        return date('Y-m-d', $dadate);
    }
    
    public function actionPostpone($identifier) {
        $_GET['projectname'] = Project::getProjectNameFromIdentifier($identifier);
        
        $model = new MilestonePostponeForm;
        if (isset($_POST['MilestonePostponeForm'])) {
            $model->attributes = $_POST['MilestonePostponeForm'];
            
            $Criteria = new CDbCriteria();
            $Criteria->select = "name, title, id, effective_date, project_id";
            $Criteria->order = 'effective_date';
            $Criteria->compare('project_id', $this->getProject($identifier));
            $results = Milestone::model()->findAll($Criteria);
            foreach ($results as $result) {
                if(strtotime($result->effective_date) >= strtotime(date("Y-m-d"))) {
                    $milestone = $this->loadModel($result->id);
                    $milestone->effective_date = $this->dateadd($milestone->effective_date, $model->postpone, $model->interval);
                    $milestone->save();
                }
            }
            if (Yii::app()->request->isAjaxRequest)
            {
                echo CJSON::encode(array(
                    'status'=>'success', 
                    'div'=>"Milestones successfully postponed"
                    ));
                exit;               
            }
            else {
                $this->redirect(array('project/settings', 'identifier' => $identifier, 'tab' => 'milestones'));
            }
        }

 
        if (Yii::app()->request->isAjaxRequest)
        {
            echo CJSON::encode(array(
                'status'=>'failure', 
                'div'=>$this->renderPartial('postpone', array('model'=>$model), true)));
            exit;               
        }
        else {
            $this->render('postpone', array(
                'model' => $model,
            ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id, $identifier) {
        $_GET['projectname'] = Project::getProjectNameFromIdentifier($identifier);

        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Milestone'])) {
            $model->attributes = $_POST['Milestone'];
            if ($model->save())
                $this->redirect(array('project/settings', 'identifier' => $identifier, 'tab' => 'milestones'));
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
    public function actionDelete($id, $identifier) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            //TODO: check if the milestone is in use!!
            $issue = Issue::model()->findByAttributes(array('milestone_id' => $id));
            if (!$issue) {
                $this->loadModel($id)->delete();
            } else {
                Yii::app()->user->setFlash('info', "Milestone is in use");
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('project/settings', 'identifier' => $identifier, 'tab' => 'milestones'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Milestone');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->block_robots = true;
        $model = new Milestone('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Milestone']))
            $model->attributes = $_GET['Milestone'];

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
        $model = Milestone::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested milestone does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'milestone-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
