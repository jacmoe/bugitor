<?php

/**
 * WatchedIssues is a Yii widget used to display a list of watched
  issues
 */
class WatchedIssues extends CWidget {

    private $_watching;

    public function init() {
        if(isset(Yii::app()->user->id)) {
            $this->_watching = Issue::watching(Yii::app()->user->id);
        } else {
            $this->_watching = array();
        }
    }

    public function getWatchedIssues() {
        return $this->_watching;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('watchedIssues');
    }

}