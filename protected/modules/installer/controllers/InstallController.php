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

    var $layout = 'main';
    
    var $defaultAction = 'installation';

    /**
     * Initializes the controller.
     */
    public function init() {
        // Register the scripts
        $this->module->registerScripts();
    }

    public function beforeAction($action) {
        $config = array();
        $config = array(
            'steps' => array('install', 'database', 'stuff'),
            'events' => array(
                'onStart' => 'wizardStart',
                'onProcessStep' => 'installationWizardProcessStep',
                'onFinished' => 'wizardFinished',
                'onInvalidStep' => 'wizardInvalidStep',
            ),
            'menuLastItem' => 'Installation Finished'
        );
        if (!empty($config)) {
            $config['class'] = 'installer.components.WizardBehavior';
            $this->attachBehavior('wizard', $config);
        }
        return parent::beforeAction($action);
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
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
//		if ($event->step===true)
//			$this->render('completed', compact('event'));
//		else
        $this->render('finished', compact('event'));

        $event->sender->reset();
        Yii::app()->end();
    }

    /**
     * Process wizard steps.
     * The event handler must set $event->handled=true for the wizard to continue
     * @param WizardEvent The event
     */
    public function installationWizardProcessStep($event) {
        $modelName = ucfirst($event->step);
        $model = new $modelName();
        $model->attributes = $event->data;
        $form = $model->getForm();

        if ($form->submitted() && $form->validate()) {
            $event->sender->save($model->attributes);
            $event->handled = true;
        } else {
            $this->render('form', compact('event', 'form'));
        }
    }

}