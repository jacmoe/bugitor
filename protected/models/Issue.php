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
 * Copyright (C) 2009 - 2010 Bugitor Team
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
 * This is the model class for table "{{issue}}".
 *
 * The followings are the available columns in table '{{issue}}':
 * @property integer $id
 * @property integer $tracker_id
 * @property integer $project_id
 * @property string $subject
 * @property string $description
 * @property integer $issue_category_id
 * @property integer $user_id
 * @property integer $issue_priority_id
 * @property integer $version_id
 * @property integer $assigned_to
 * @property integer $updated_by
 * @property string $created
 * @property string $modified
 * @property integer $done_ratio
 * @property integer $pre_done_ratio
 * @property string $status
 * @property integer $closed
 *
 * The followings are the available model relations:
 * @property User $assignedTo
 * @property User $updatedBy
 * @property IssueCategory $issueCategory
 * @property Project $project
 * @property IssuePriority $issuePriority
 * @property Tracker $tracker
 * @property User $user
 * @property Version $version
 * @property RelatedIssue[] $relatedIssues
 * @property Users[] $bugUsers
 */
class Issue extends CActiveRecord {

    private $_oldattributes = array();

    public function afterFind()
    {
        // Save old values
        $this->setOldAttributes($this->Owner->getAttributes());
        return parent::afterFind();
    }

    public function getOldAttributes()
    {
        return $this->_oldattributes;
    }

    public function setOldAttributes($value)
    {
        $this->_oldattributes=$value;
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Issue the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{issue}}';
    }

    public function sendAssignedNotice($isAssigned = true, $reopen = false) {
        if($isAssigned) {
            Yii::app()->user->setFlash('success',"Sending Assignment notice!");
        } else {
            Yii::app()->user->setFlash('notice',"Sending Unassignment notice!");
        }
        if($reopen) {
            $this->closed = 0;
            $this->done_ratio = $this->pre_done_ratio;
        }
    }
    
    public function markAsClosed($rejected = false) {
        $this->closed = 1;
        $this->pre_done_ratio = $this->done_ratio;
        if($rejected) {
            $this->done_ratio = 0;
        } else {
            $this->done_ratio = 100;
        }
    }

    public function getWatchers() {
        $criteria = new CDbCriteria();
        $criteria->compare('issue_id', $this->id, true);
        $watchers = Watcher::model()->with('user')->findAll($criteria);
        return $watchers;
    }

    public function watchedBy() {
        $criteria = new CDbCriteria();
        $criteria->select = 'user_id';
        $criteria->compare('user_id', Yii::app()->user->id, true);
        $criteria->compare('issue_id', $this->id, true);
        $watchers = Watcher::model()->findAll($criteria);
        return !empty($watchers);
    }

    public static function watching($user_id) {
        $criteria = new CDbCriteria();
        $criteria->compare('t.user_id', $user_id, true);
        $watched = Watcher::model()->with(array('user', 'issue', 'issue.project'))->findAll($criteria);
        return $watched;
    }

    public static function owned($user_id) {
        $criteria = new CDbCriteria();
        $criteria->compare('user_id', $user_id, true);
        $owned = Issue::model()->with(array('project'))->findAll($criteria);
        return $owned;
    }

    public static function assigned($user_id) {
        $criteria = new CDbCriteria();
        $criteria->compare('assigned_to', $user_id, true);
        $assigned = Issue::model()->with(array('project'))->findAll($criteria);
        return $assigned;
    }

    public static function isOwnerOf() {
        if(Yii::app()->controller->id !== 'issue') {
            return false;
        }
        if((isset(Yii::app()->user->id))&&(isset($_GET['id']))) {
            $criteria = new CDbCriteria();
            $criteria->select = 'user_id';
            $criteria->compare('user_id', Yii::app()->user->id, true);
            $criteria->compare('id', $_GET['id'], true);
            $owner = Issue::model()->findAll($criteria);
            return !empty($owner);
        } else {
            return false;
        }
        return false;
    }

    /**
     * Prepares create_time, create_user_id, update_time and update_user_id attributes before performing validation.
     */
    protected function beforeValidate() {
        if(($this->assigned_to) && ($this->status === 'swIssue/new')) {
            $this->status = 'swIssue/assigned';
            $this->sendAssignedNotice();
        }
        if(($this->assigned_to) && ($this->status === 'swIssue/unassigned')) {
            $this->status = 'swIssue/assigned';
            $this->sendAssignedNotice();
        }
        if((!$this->assigned_to) && ($this->status === 'swIssue/assigned')) {
            $this->status = 'swIssue/unassigned';
            $this->sendAssignedNotice(false);
        }
        return parent::beforeValidate();
    }

    public function behaviors() {
        return array(
            'BugitorTimestampBehavior' => array(
                'class' => 'application.behaviors.BugitorTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
            ),
//            'CSafeContentBehavior' => array(
//                'class' => 'application.behaviors.CSafeContentBehavior',
//                'attributes' => array('description', 'subject'),
//            ),
            'swBehavior' => array(
                'class' => 'application.extensions.simpleWorkflow.SWActiveRecordBehavior',
            ),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('subject, description, user_id, status, issue_priority_id, tracker_id', 'required'),
            array('tracker_id, project_id, issue_category_id, user_id, issue_priority_id, version_id, assigned_to, updated_by, done_ratio, pre_done_ratio, closed', 'numerical', 'integerOnly' => true),
            array('subject', 'length', 'max' => 255),
            array('status', 'SWValidator'),
            array('created, modified, updated_by', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, tracker_id, project_id, subject, description, issue_category_id, user_id, issue_priority_id, version_id, assigned_to, created, modified, done_ratio, pre_done_ratio, status, closed', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'assignedTo' => array(self::BELONGS_TO, 'User', 'assigned_to'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'issueCategory' => array(self::BELONGS_TO, 'IssueCategory', 'issue_category_id'),
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
            'issuePriority' => array(self::BELONGS_TO, 'IssuePriority', 'issue_priority_id'),
            'tracker' => array(self::BELONGS_TO, 'Tracker', 'tracker_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'version' => array(self::BELONGS_TO, 'Version', 'version_id'),
            'relatedIssues' => array(self::HAS_MANY, 'RelatedIssue', 'issue_to'),
            'watchers' => array(self::MANY_MANY, 'User', '{{watcher}}(issue_id, user_id)'),
            'comments' => array(self::HAS_MANY, 'Comment', 'issue_id'),
            'commentCount' => array(self::STAT, 'Comment', 'issue_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'tracker_id' => 'Tracker',
            'project_id' => 'Project',
            'subject' => 'Subject',
            'description' => 'Description',
            'issue_category_id' => 'Issue Category',
            'user_id' => 'Author',
            'issue_priority_id' => 'Issue Priority',
            'version_id' => 'Version',
            'assigned_to' => 'Owner',
            'updated_by' => 'Updated by',
            'created' => 'Created',
            'modified' => 'Modified',
            'done_ratio' => 'Done Ratio',
            'pre_done_ratio' => 'Done Ratio on close',
            'status' => 'Status',
            'closed' => 'Closed',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->with = array('user', 'project', 'tracker', 'issuePriority', 'assignedTo', 'updatedBy', 'version', 'issueCategory');
        $criteria->compare('id', $this->id);
        $criteria->compare('tracker.name', $this->tracker_id);
        $criteria->compare('user.username', $this->user_id);
        $criteria->compare('project.name', $this->project_id);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('issueCategory.name', $this->issue_category_id);
        $criteria->compare('issuePriority.name', $this->issue_priority_id);
        $criteria->compare('version.name', $this->version_id);
        $criteria->compare('assignedTo.username', $this->assigned_to);
        $criteria->compare('updatedBy.username', $this->updated_by);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('done_ratio', $this->done_ratio);
        $criteria->compare('t.status', $this->status, true);
        $criteria->compare('closed', $this->closed);

        if (isset($_GET['identifier'])) {
            $project = Project::model()->findByAttributes(array('identifier' => $_GET['identifier']));
            $criteria->compare('t.project_id', $project->id, true);
        }
        if (isset($_GET['issueFilter'])) {
            if((int)$_GET['issueFilter']===1) {
                $criteria->compare('t.closed', 0, true);
            }
            elseif((int)$_GET['issueFilter']===2) {
                $criteria->compare('t.closed', 1, true);
            }
        } else {
            $criteria->compare('t.closed', 0, true);
        }

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort'=>array('defaultOrder'=>'t.modified DESC'),
            'pagination'=>array(
                'pageSize'=> Yii::app()->user->getState('pageSize', Yii::app()->config->get('defaultPagesize')),
            ),
        ));
    }

    private function getNamefromRowValue($name, $value) {
        $returned_name = '';
        switch ($name) {
            case 'tracker_id':
                $returned_name = Tracker::model()->findByPk($value)->name;
                break;
            case 'version_id':
                $returned_name = Version::model()->findByPk($value)->name;
                break;
            case 'issue_category_id':
                $returned_name = IssueCategory::model()->findByPk($value)->name;
                break;
            case 'assigned_to':
                $returned_name = ucfirst(User::model()->findByPk($value)->username);
                break;
            case 'issue_priority_id':
                $returned_name = IssuePriority::model()->findByPk($value)->name;
                break;
            case 'status':
                list($crap, $out) = explode('/', $value);
                $returned_name = ucfirst($out);
                break;
            default:
                $returned_name = $value;
                break;
        }
        return $returned_name;
    }

    public function getStatusLabel($status) {
        switch ($status) {
            case 'swIssue/new':
                return 'New';
                break;
            case 'swIssue/assigned':
                return 'Assigned';
                break;
            case 'swIssue/rejected':
                return 'Rejected';
                break;
            case 'swIssue/resolved':
                return 'Resolved';
                break;
            case 'swIssue/reassigned':
                return 'Reassigned';
                break;
            default:
                return $status;
                break;
        }
    }

    public function addToActionLog($id, $user_id, $type, $url, $comment = null) {
        $issue = Issue::model()->findByPk((int)$id);
        switch ($type) {
            case 'new':
                $actionLog = new ActionLog;
                $actionLog->author_id = $user_id;
                $actionLog->project_id = $issue->project_id;
                $actionLog->description = $issue->description;
                $actionLog->subject = $issue->tracker->name.'#'.$issue->id.' (New): '.$issue->subject;
                $actionLog->type = 'issue-new';
                $actionLog->when = $issue->created;
                $actionLog->url = $url;
                if($actionLog->validate())
                    $actionLog->save(false);
                break;
            case 'note':
                $actionLog = new ActionLog;
                $actionLog->author_id = $user_id;
                $actionLog->project_id = $issue->project_id;
                $actionLog->description = $comment->content;
                $actionLog->subject = 'asdf';//$issue->tracker->name.'#'.$issue->id.' : '.$issue->subject;
                $actionLog->type = 'issue-note';
                $actionLog->when = $comment->created;
                $actionLog->url = 'sdf';//$url;
                if($actionLog->validate())
                    $actionLog->save(false);
                break;
            case 'change':
                $actionLog = new ActionLog;
                $actionLog->author_id = $user_id;
                $actionLog->project_id = $issue->project_id;
                $actionLog->description = $comment->content;
                $actionLog->subject = $issue->tracker->name.'#'.$issue->id.' : '.$issue->subject;
                $actionLog->type = 'issue-change';
                $actionLog->when = $comment->created;
                $actionLog->url = $url;
                if($actionLog->validate())
                    $actionLog->save(false);
                break;
            case 'resolved':
                $actionLog = new ActionLog;
                $actionLog->author_id = $user_id;
                $actionLog->project_id = $issue->project_id;
                $actionLog->description = $comment->content;
                $actionLog->subject = $issue->tracker->name.'#'.$issue->id.' (Resolved): '.$issue->subject;
                $actionLog->type = 'issue-resolved';
                $actionLog->when = $comment->created;
                $actionLog->url = $url;
                if($actionLog->validate())
                    $actionLog->save(false);
                break;
            case 'rejected':
                $actionLog = new ActionLog;
                $actionLog->author_id = $user_id;
                $actionLog->project_id = $issue->project_id;
                $actionLog->description = $comment->content;
                $actionLog->subject = $issue->tracker->name.'#'.$issue->id.' (Rejected): '.$issue->subject;
                $actionLog->type = 'issue-rejected';
                $actionLog->when = $comment->created;
                $actionLog->url = $url;
                if($actionLog->validate())
                    $actionLog->save(false);
                break;
            default:
                break;
        }
    }

    public function buildCommentDetails($comment_id) {
        $labels = $this->attributeLabels();
        $newattributes = $this->getAttributes();
        $oldattributes = $this->getOldAttributes();
        $changed = false;
        // compare old and new
        foreach ($newattributes as $name => $value) {

            if (!empty($oldattributes)) {
                $old = $oldattributes[$name];
            } else {
                $old = '';
            }
            if (($value != $old)&&($name != 'updated_by')&&($name != 'description'))
            {
                $changed = true;
                $detail = new CommentDetail();
                $detail->comment_id = $comment_id;

                if($old == '')
                {
                    $detail->change = '<b>'.$labels[$name] . '</b> set to <i>' . $this->getNamefromRowValue($name, $value).'</i>';
                    $changed = true;
                }
                else
                {
                    if($value != '')
                    {
                        $detail->change = '<b>'.$labels[$name] . '</b> changed from <i>' . $this->getNamefromRowValue($name, $old) . '</i> to <i>' . $this->getNamefromRowValue($name, $value).'</i>';
                    }
                    else
                    {
                        $detail->change = '<b>'.$labels[$name] . '</b> removed (<i><s>'.$this->getNamefromRowValue($name, $old).'</s></i>)';
                    }
                }
                if($detail->validate() && $changed)
                    $detail->save(false);
            }
        }
        return $changed;
    }

    public function wasModified() {
        $newattributes = $this->getAttributes();
        $oldattributes = $this->getOldAttributes();
        // compare old and new
        $changed = false;
        foreach ($newattributes as $name => $value) {

            if (!empty($oldattributes)) {
                $old = $oldattributes[$name];
            } else {
                $old = '';
            }
            if ($value != $old)
            {
                $changed = true;
            }
        }
        return $changed;
    }

    public function sendNotifications($id, $comment) {
        $issue = Issue::model()->findByPk((int) $id);
        $message = new YiiMailMessage;
        $message->view = 'issuechange';
        $message->setSubject(Bugitor::issue_subject($issue));
        $message->setBody(array('issue'=>$issue, 'comment' => $comment), 'text/html');
        $message->from = 'ticket@tracker.ogitor.org';
        $emails = $issue->getWatcherEmails($id);
        foreach($emails as $email)
            $message->addTo($email);
        Yii::app()->mail->send($message);
    }

    public function getWatcherEmails($id) {
        $criteria = new CDbCriteria();
        $criteria->compare('issue_id', $id, true);
        $watchers = Watcher::model()->with('user')->findAll($criteria);
        foreach($watchers as $watcher){
            $emails[] = $watcher->user->email;
        }
        return $emails;
    }

    /**
     * Adds a comment to this issue
     */
    public function addComment($comment) {
        $comment->issue_id = $this->id;
        return $comment->save();
    }

}