<?php

/**
 * OwnedIssues is a Yii widget used to display a list of owned
  issues
 */
class OwnedIssues extends CWidget {

    private $_owned;

    public function init() {
        if(isset(Yii::app()->user->id)) {
            $this->_owned = Issue::owned(Yii::app()->user->id);
        } else {
            $this->_owned = array();
        }
    }

    public function getOwnedIssues() {
        return $this->_owned;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('ownedIssues');
    }

}