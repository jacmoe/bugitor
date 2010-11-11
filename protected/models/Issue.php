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
 * @property string $created
 * @property string $modified
 * @property integer $done_ratio
 * @property string $status
 * @property integer $closed
 *
 * The followings are the available model relations:
 * @property User $assignedTo
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

    // Returns list of searchable fileds for DataFilter widget
    public function getDataFilterSearchFields($filterName)
    {
        switch ($filterName) {
            case 'issueFieldsSearch': //filter name
                return array(
                    't.id'=>'Issue ID', //field name => display name
                    't.subject'=>'Subject',
                );
        }
    }

    // Applies search criteria enterd using DataFilter widget
    public function applyDataSearchCriteria(&$criteria, $filterName, $searchField, $searchValue)
    {
        if($filterName == 'issueFieldsSearch') {
            $localCriteria = new CDbCriteria;
            //$localCriteria->condition = ' '.$searchField.' LIKE "%'.$searchValue.'%" ';
            $localCriteria->condition = ' '.$searchField.' LIKE :searchValue ';
            $localCriteria->params = array(':searchValue'=>'%'.$searchValue.'%');
            $criteria->mergeWith($localCriteria);
        }
    }

    // Returns options for DataFilter widget
    public function getDataFilterOptions($filterName)
    {
        switch ($filterName) {
            case 'Priority':  //filter name
            case 'priorityFilter2':
                // data from database
                $priority = IssuePriority::model()->findAll();
                return CHtml::listData($priority, 'id', 'name');
           case 'closedDropFilter':
                // static data (not from database)
                $options = array(
                    array('id'=>'', 'name'=>'All'),
                    array('id'=>1, 'name'=>'Closed'),
                    array('id'=>0, 'name'=>'Open'),
                );
                return CHtml::listData($options, 'id', 'name');
        }
    }

    // Applies filter criteria enterd using DataFilter widget
    public function applyDataFilterCriteria(&$criteria, $filterName, $filterValue)
    {
        if($filterName == 'Priority' || $filterName == 'groupFilter2') {
            $localCriteria = new CDbCriteria;
            CDataFilter::setCondition('issue_priority_id', $filterValue, $localCriteria);
            $criteria->mergeWith($localCriteria);
        }

        if($filterName == 'closedFilter') {
            if ($filterValue !== 'closed') return;
            $localCriteria = new CDbCriteria;
            CDataFilter::setCondition('closed', 1, $localCriteria);
            $criteria->mergeWith($localCriteria);
        }

        if($filterName == 'closedDropFilter') {
            $localCriteria = new CDbCriteria;
            CDataFilter::setCondition('closed', $filterValue, $localCriteria);
            $criteria->mergeWith($localCriteria);
        }

    }

    public function sendAssignedNotice($isAssigned = true) {
        if($isAssigned) {
            Yii::app()->user->setFlash('success',"Sending Assignment notice!");
        } else {
            Yii::app()->user->setFlash('notice',"Sending Unassignment notice!");
        }
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
            'CActiveRecordLogableBehavior' =>
            array('class' => 'application.behaviors.CActiveRecordLogableBehavior'),
            'BugitorTimestampBehavior' => array(
                'class' => 'application.behaviors.BugitorTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
            ),
            'CSafeContentBehavior' => array(
                'class' => 'application.behaviors.CSafeContentBehavior',
                'attributes' => array('description', 'subject'),
            ),
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
            array('tracker_id, project_id, issue_category_id, user_id, issue_priority_id, version_id, assigned_to, done_ratio, closed', 'numerical', 'integerOnly' => true),
            array('subject', 'length', 'max' => 255),
            array('status', 'SWValidator'),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, tracker_id, project_id, subject, description, issue_category_id, user_id, issue_priority_id, version_id, assigned_to, created, modified, done_ratio, status, closed', 'safe', 'on' => 'search'),
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
            'created' => 'Created',
            'modified' => 'Modified',
            'done_ratio' => 'Done Ratio',
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

        $criteria->with = array('user', 'project', 'tracker', 'issuePriority', 'assignedTo', 'version', 'issueCategory');
        $criteria->compare('id', $this->id);
        $criteria->compare('tracker.name', $this->tracker_id);
        $criteria->compare('User.username', $this->user_id);
        $criteria->compare('project.name', $this->project_id);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('issueCategory.name', $this->issue_category_id);
        $criteria->compare('issuePriority.name', $this->issue_priority_id);
        $criteria->compare('version.name', $this->version_id);
        $criteria->compare('assignedTo.username', $this->assigned_to);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('done_ratio', $this->done_ratio);
        $criteria->compare('t.status', $this->status, true);
        $criteria->compare('closed', $this->closed);

        if (isset($_GET['name'])) {
            $project = Project::model()->findByAttributes(array('name' => $_GET['name']));
            $criteria->compare('t.project_id', $project->id, true);
        }
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort'=>array('defaultOrder'=>'t.modified DESC'),
            //'pagination' => array('pageSize' => 3),
        ));
    }

    public function getDescription() {
        $parser = new CMarkdownParser;
        return $parser->safeTransform($this->description);
    }

    /**
     * Adds a comment to this issue
     */
    public function addComment($comment) {
        $comment->issue_id = $this->id;
        return $comment->save();
    }

}