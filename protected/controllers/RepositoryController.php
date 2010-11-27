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

class RepositoryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($identifier)
	{
                $_GET['projectname'] = Project::getProjectNameFromIdentifier($identifier);

                $model=new Repository;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Repository']))
		{
			$model->attributes=$_POST['Repository'];
                        $model->identifier = preg_replace( '/\s*/m', '', strtolower($model->name));
                        $unique_id = uniqid($model->identifier.'_');
                        Yii::app()->file->createDir(0754, 'repositories/'.$unique_id);
                        $directoryy = Yii::app()->file->set('repositories/'.$unique_id, true);
			$model->local_path = $directoryy->getRealPath();
                        if($model->save()){
                            if (PHP_OS === 'WINNT') {
                                $commandString = 'start /b '.Yii::app()->config->get('hg_executable').' clone '.$model->url.' "'.$model->local_path.'"';
                                pclose(popen($commandString, 'r'));
                            } else { // we're on *nix
                                if(Yii::app()->config->get('python_path'))
                                    putenv(Yii::app()->config->get('python_path'));
                                $commandString = Yii::app()->config->get('hg_executable').' clone '.$model->url.' "'.$model->local_path.'"';
                                $PID = shell_exec("nohup $commandString 2> /dev/null & echo $!");
                                //exec($commandString . ' > /dev/null &');
                                Yii::app()->user->setState('pid', $PID);
                            }
                            //Yii::app()->scm->mtrack_run_tool('hg', 'read', array('init', 'C:/wamp/www/repositories/'.$model->name ));
                            //Yii::app()->scm->mtrack_run_tool('hg', 'read', array('clone', $model->url, 'C:/wamp/www/repositories/'.$model->name ));
                            
                            $this->redirect(array('project/settings','identifier'=>$identifier, 'tab' => 'repositories'));
                        } else {
                            if (PHP_OS === 'WINNT') {
                                $commandString = 'start /b rmdir /S /Q "'.$model->local_path.'"';
                            } else {
                                $commandString = 'rm -rf "'.$model->local_path.'"';
                            }
                            pclose(popen($commandString, 'r'));
                        }
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

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Repository']))
		{
			$model->attributes=$_POST['Repository'];
			if($model->save())
                            $this->redirect(array('project/settings','identifier'=>$identifier, 'tab' => 'repositories'));
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
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = $this->loadModel($id);

                        
                        if($model->delete())
                        {
                            if (PHP_OS === 'WINNT') {
                                $commandString = 'start /b rmdir /S /Q "'.$model->local_path.'"';
                            } else {
                                $commandString = 'rm -rf "'.$model->local_path.'"';
                            }
                            pclose(popen($commandString, 'r'));
                        }

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
                            $this->redirect(array('project/settings','identifier'=>$_GET['identifier'], 'tab' => 'repositories'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Repository::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='repository-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
