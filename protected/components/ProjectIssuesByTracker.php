<?php

/**
 * ProjectIssuesByTracker is a Yii widget used to display a list of issues
  associated with a project
 */
class ProjectIssuesByTracker extends CWidget {

    public $project = null;

    public function getProject() {
        return $this->project;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('projectIssuesByTracker');
    }

}