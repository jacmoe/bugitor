<?php

/**
 * ProjectMembers is a Yii widget used to display a list of project
  members
 */
class ProjectMembers extends CWidget {

    public $project = null;

    public function getMembers() {
        return (isset($this->project) ? $this->project->getMembers() : array());
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('projectMembers');
    }

}