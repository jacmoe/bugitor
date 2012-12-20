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

class MemberController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights',
        );
    }

    public function allowedActions()
    {
        return 'index, view';
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
                if (isset($_GET['identifier'])){
                    $_GET['projectname'] = Project::getProjectNameFromIdentifier($_GET['identifier']);
                }

                $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($identifier)
    {
                $_GET['projectname'] = Project::getProjectNameFromIdentifier($identifier);

                $model=new Member;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Member']))
        {
            $model->attributes=$_POST['Member'];
                        Rights::assign($model->role, $model->user_id);

            if($model->save())
                            $this->redirect(array('project/settings','identifier'=>$identifier, 'tab' => 'members'));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id, $identifier)
    {
                $_GET['projectname'] = Project::getProjectNameFromIdentifier($identifier);

        $model = Member::model()->with('user')->findByPk((int)$id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Member']))
        {
            $model->attributes=$_POST['Member'];

                        if($model->validate())
                        {
                            $roles = Rights::getAssignedRoles($model->user_id);
                            //TODO: handle different roles in different projects!
                            foreach($roles as $role) {
                                if($role->name === 'Developer')
                                    Rights::revoke($role->name, $model->user_id);
                                if($role->name === 'Project Admin')
                                    Rights::revoke($role->name, $model->user_id);
                                if($role->name === 'Project Lead')
                                    Rights::revoke($role->name, $model->user_id);
                            }
                            Rights::assign($model->role, $model->user_id);
                            if($model->save())
                                $this->redirect(array('project/settings','identifier'=>$identifier, 'tab' => 'members'));
                        }

        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id, $identifier)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
                        if($this->loadModel($id)->delete())
                        {
                            Yii::app()->user->setFlash('succes',"Member was deleted.");
                        }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(array('project/settings','identifier'=>$identifier, 'tab' => 'members'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('Member');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
                $this->block_robots = true;
        $model=new Member('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Member']))
            $model->attributes=$_GET['Member'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=Member::model()->findByPk((int)$id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='member-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
