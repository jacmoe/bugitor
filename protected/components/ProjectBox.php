<?php

/**
 * ProjectBox is a Yii widget used to display information about a project
 */
class ProjectBox extends CWidget {

    public $project = null;

    public function getProject() {
        return $this->project;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('projectBox');
    }

}