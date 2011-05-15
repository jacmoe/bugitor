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

class InstallController extends CController {

    var $defaultAction = 'installation';

    public function beforeAction($action) {
        $config = array();
        $config = array(
            'steps' => array('Start Installation' => 'one', 'Database Connection' => 'two', 'Install Schema and Data' => 'three'),
            'events' => array(
                'onStart' => 'wizardStart',
                'onProcessStep' => 'installationWizardProcessStep',
                'onFinished' => 'wizardFinished',
                'onInvalidStep' => 'wizardInvalidStep',
            ),
            'menuLastItem' => 'Installation Finished'
        );
        if (!empty($config)) {
            $config['class'] = 'WizardBehavior';
            $this->attachBehavior('wizard', $config);
        }
        return parent::beforeAction($action);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
            else
                    $this->render('error', $error);
        }
    }
    
    public function actionInstallation($step=null) {
        $this->pageTitle = 'Installation Wizard';
        $this->process($step);
    }

    // Wizard Behavior Event Handlers
    /**
     * Raised when the wizard starts; before any steps are processed.
     * MUST set $event->handled=true for the wizard to continue.
     * Leaving $event->handled===false causes the onFinished event to be raised.
     * @param WizardEvent The event
     */
    public function wizardStart($event) {
        $event->handled = true;
    }

    /**
     * Raised when the wizard detects an invalid step
     * @param WizardEvent The event
     */
    public function wizardInvalidStep($event) {
        Yii::app()->getUser()->setFlash('notice', $event->step . ' is not a vaild step in this wizard');
    }

    /**
     * The wizard has finished; use $event->step to find out why.
     * Normally on successful completion ($event->step===true) data would be saved
     * to permanent storage; the demo just displays it
     * @param WizardEvent The event
     */
    public function wizardFinished($event) {
        if ($event->step === true) {
            $this->render('completed', compact('event'));
        } else {
            $this->render('finished', compact('event'));
            //touch(dirname(__FILE__).'/../../lock');
        }
        $event->sender->reset();
        Yii::app()->end();
    }

    /**
     * Process wizard steps.
     * The event handler must set $event->handled=true for the wizard to continue
     * @param WizardEvent The event
     */
    public function installationWizardProcessStep($event) {
        $name = 'process' . ucfirst($event->step);
        if (method_exists($this, $name)) {
            $event->handled = call_user_func(array($this, $name), $event);
        } else {
            throw new CException(Yii::t('yii', '{class} does not have a method named "{name}".', array('{class}' => get_class($this), '{name}' => $name)));
        }
    }

    // Check that systems are online and prepare for install
    // Also check that installation is not locked
    public function processOne($event) {
        $modelName = ucfirst($event->step);
        $model = new $modelName();
        $model->attributes = $event->data;
        $form = $model->getForm();
        
        $status = $this->checkPermissions();
        $message = $status['message'];
        $message .= '<br/>';
        $failed = false;
        if($status['error'] > 0) {
            $failed = true;
            $message .= "There were {$status['error']} errors.<br/>";
            $message .= 'Please adjust permissions so that the above entries are writable by the Apache process.<br/>';
        } else {
            $message .= 'Everything seems to be <b><font color="green">OK.</font></b><br/><br/>';
            $message .= 'The installation can proceed.<br/><br/>';
        }
        if ($form->submitted() && $form->validate()) {
            $event->sender->save($model->attributes);
            return true;
        } else {
            $this->render('systemcheck', compact('message', 'event', 'failed', 'form'));
        }
    }

    // Get database connection string and credentials from user
    // Should test connection as part of validation?
    public function processTwo($event) {
        $modelName = ucfirst($event->step);
        $model = new $modelName();
        $model->attributes = $event->data;
        $form = $model->getForm();
        $message = 'Enter your database details';
        if ($form->submitted() && $form->validate()) {
            $event->sender->save($model->attributes);
            $model->save();
            return true;
        } else {
            $this->render('form', compact('message', 'event', 'form'));
        }
    }

    // Test that the database connection is valid (should be moved to previous step?)
    // Apply database migrations
    public function processThree($event) {
        $modelName = ucfirst($event->step);
        $model = new $modelName();
        $model->attributes = $event->data;
        $form = $model->getForm();
        $message = 'Testing connection...<br/><br/>';

        if (file_exists(dirname(__FILE__) . '/../../../protected/config/db.php')) {
            $config = require(dirname(__FILE__) . '/../../../protected/config/db.php');
            $connection = new CDbConnection($config['connectionString'], $config['username'], $config['password']);
            try {
                $connection->active = true;
            } catch (Exception $e) {
                $message .= '<font color="red">Error:</font> Could not connect to database. Please go back to the previous step and check your database settings.';
            }
            if ($connection->active) {
                $failed = false;
                $message .= '<font color="green">Success:</font> Connection was succesful!<br/>';
                $cmd = dirname(__FILE__) . "/../../../protected/yiic migrate --interactive=0";
                // Run the command twice - second time around it should
                // output that our system is up-to-date.
                $output = stream_get_contents(popen($cmd, 'r'));
                $output = stream_get_contents(popen($cmd, 'r'));
                if (preg_match("/Your system is up-to-date/i", $output)) {
                    $message .= '<font color="green">Success:</font> Migration successfully applied.';
                } else {
                    // TODO: handle this.
                    $message .= '<font color="red">Error:</font>An error occurred - here be dragons..';
                }
            } else {
                $failed = true;
            }
        } else {
            $failed = true;
        }
        if ($form->submitted() && $form->validate()) {
            $event->sender->save($model->attributes);
            return true;
        } else {
            $this->render('dbcheckinstall', compact('message', 'event', 'failed', 'form'));
        }
    }

    protected function checkPermissions()
    {
        $out = array();
        $message = '';
        $error = 0;
        if(is_writable(dirname(__FILE__).'/../../../assets')) {
            $message .= 'assets directory is <b><font color="green">writable</font></b><br/>';
        } else
        {
            $message .= 'assets directory is not writable - <b><font color="red">error!</font></b><br/>';
            $error += 1;
        }
        if(is_writable(dirname(__FILE__).'/../../../uploads')) {
            $message .= 'uploads directory is <b><font color="green">writable</font></b><br/>';
        } else
        {
            $message .= 'uploads directory is not writable - <b><font color="red">error!</font></b><br/>';
            $error += 1;
        }
        if(is_writable(dirname(__FILE__).'/../../../repositories')) {
            $message .= 'repositories directory is <b><font color="green">writable</font></b><br/>';
        } else
        {
            $message .= 'repositories directory is not writable - <b><font color="red">error!</font></b><br/>';
            $error += 1;
        }
        if(is_writable(dirname(__FILE__).'/../../../protected/runtime')) {
            $message .= 'protected/runtime directory is <b><font color="green">writable</font></b><br/>';
        } else
        {
            $message .= 'protected/runtime directory is not writable - <b><font color="red">error!</font></b><br/>';
            $error += 1;
        }
        if(is_writable(dirname(__FILE__).'/../../../protected/config')) {
            $message .= 'protected/config directory is <b><font color="green">writable</font></b><br/>';
        } else
        {
            $message .= 'protected/config directory is not writable - <b><font color="red">error!</font></b><br/>';
            $error += 1;
        }
        $out['message'] = $message;
        $out['error'] = $error;
        return $out;
    }
}