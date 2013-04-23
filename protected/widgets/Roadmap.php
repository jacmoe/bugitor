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
 * Copyright (C) 2009 - 2013 Bugitor Team
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
 * WatchedIssues is a Yii widget used to display a list of assigned
  issues for current user
 */
class Roadmap extends CWidget {

    public $milestones;
    public $identifier;
    public $detail_view = false;
    public $show_all = false;

    public function getMilestones() {
        return $this->milestones;
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function getDetailview() {
        return $this->detail_view;
    }

    public function getShowAllOverride() {
        if(($this->getOwner()->getAction()->getId() === 'view') && (Yii::app()->controller->id === 'milestone' ) ) {
            return true;
        }
        if(($this->getOwner()->getAction()->getId() === 'roadmap') && (Yii::app()->controller->id === 'project' ) ) {
            return Yii::app()->user->getState('show_all_milestons', false);
        }
        return $this->show_all;
    }

    public function run() {
        $this->render('roadmap');
    }

}