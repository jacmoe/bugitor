<?php

/**
 * WatchedIssues is a Yii widget used to display a list of assigned
  issues for current user
 */
class AssignedIssues extends CWidget {

    private $_assigned_to;

    public function init() {
        if(isset(Yii::app()->user->id)) {
            $this->_assigned_to = Issue::assigned(Yii::app()->user->id);
        } else {
            $this->_assigned_to = array();
        }
    }

    public function getAssignedIssues() {
        return $this->_assigned_to;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('assignedIssues');
    }

}