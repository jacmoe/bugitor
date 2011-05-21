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

/**
 * ProjectMembers is a Yii widget used to display a list of project
  members
 */
class ProjectActivity extends CWidget {

    private $_activities = null;
    public $displayLimit = 0;
    public $projectId = null;
    private $_pages = null;

    public function init() {
        $criteria2 = new CDbCriteria;
        $criteria2->select = array('id', 'type', 'author_id', 'theDate', 'url', 'project_id', 'subject', 'description', 'DATE(theDate) as theday');
        $criteria2->condition = 'project_id = :project_id';
        $criteria2->together = true;
        $criteria2->params = array('project_id' => $this->projectId);
        $criteria2->order = 'theDate DESC';
        if($this->displayLimit > 0) {
            $criteria2->limit = $this->displayLimit;
        } else {
            $this->_pages = new CPagination(ActionLog::model()->find()->count($criteria2));
            $this->_pages->pageSize = 25;
            $this->_pages->applyLimit($criteria2);
        }
        $this->_activities = ActionLog::model()->findAll($criteria2);
    }

    public function getActivities() {
        return $this->_activities;
    }

    public function getPages() {
        return $this->_pages;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('projectActivity');
    }

}