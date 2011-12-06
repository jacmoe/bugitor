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
 * Copyright (C) 2009 - 2011 Bugitor Team
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

class SettingsController extends Controller {

    public $block_robots = true;
    
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function actionUpdate() {
        $model = $this->loadModel();

        if(isset($_POST['ConfigForm']))
        {
            $model->attributes=$_POST['ConfigForm'];
            if($model->save())
                $this->redirect(array('settings/index'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionIndex() {
        $model = $this->loadModel();
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel() {
        $model = new ConfigForm;
        $model->pagesize = Yii::app()->config->get('defaultPagesize');
        $model->hg_executable = Yii::app()->config->get('hg_executable');
        $model->git_executable = Yii::app()->config->get('git_executable');
        $model->svn_executable = Yii::app()->config->get('svn_executable');
        $model->python_path = Yii::app()->config->get('python_path');
        $model->default_scm = Yii::app()->config->get('default_scm');
        $model->default_timezone = Yii::app()->config->get('default_timezone');
        $model->hostname = Yii::app()->config->get('hostname');
        $model->logging_enabled = Yii::app()->config->get('logging_enabled');
        return $model;
    }

}
