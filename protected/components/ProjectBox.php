<?php

/**
 * WatchedIssues is a Yii widget used to display a list of assigned
  issues
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