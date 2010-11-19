<?php

/**
 * MyProjects is a Yii widget used to display a list of projects
  associated with current user
 */
class MyProjects extends CWidget {

    private $_my_projects;

    public function init() {
        if(isset(Yii::app()->user->id)) {
            $this->_my_projects = Project::myProjects(Yii::app()->user->id);
        } else {
            $this->_my_projects = array();
        }
    }

    public function getMyProjects() {
        return $this->_my_projects;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('myProjects');
    }

}