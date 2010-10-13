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
 * @property string $due_date
 * @property integer $issue_category_id
 * @property integer $issue_status_id
 * @property integer $user_id
 * @property integer $issue_priority_id
 * @property integer $version_id
 * @property integer $assigned_to
 * @property string $created_on
 * @property string $updated_on
 * @property string $start_date
 * @property integer $done_ratio
 *
 * The followings are the available model relations:
 * @property Tracker $tracker
 * @property Project $project
 * @property IssueCategory $issueCategory
 * @property IssueStatus $issueStatus
 * @property IssuePriority $issuePriority
 * @property Users $user
 * @property Version $version
 * @property Users $assignedTo0
 * @property RelatedIssue[] $relatedIssues
 * @property Users[] $bugUsers
 */
class Issue extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Issue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{issue}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject, description, user_id', 'required'),
			array('tracker_id, project_id, issue_category_id, issue_status_id, user_id, issue_priority_id, version_id, assigned_to, done_ratio', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>255),
			array('due_date, created_on, updated_on, start_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tracker_id, project_id, subject, description, due_date, issue_category_id, issue_status_id, user_id, issue_priority_id, version_id, assigned_to, created_on, updated_on, start_date, done_ratio', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tracker' => array(self::BELONGS_TO, 'Tracker', 'tracker_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'issueCategory' => array(self::BELONGS_TO, 'IssueCategory', 'issue_category_id'),
			'issueStatus' => array(self::BELONGS_TO, 'IssueStatus', 'issue_status_id'),
			'issuePriority' => array(self::BELONGS_TO, 'IssuePriority', 'issue_priority_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'version' => array(self::BELONGS_TO, 'Version', 'version_id'),
			'assignedTo0' => array(self::BELONGS_TO, 'Users', 'assigned_to'),
			'relatedIssues' => array(self::HAS_MANY, 'RelatedIssue', 'issue_to'),
			'bugUsers' => array(self::MANY_MANY, 'Users', '{{watcher}}(issue_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tracker_id' => 'Tracker',
			'project_id' => 'Project',
			'subject' => 'Subject',
			'description' => 'Description',
			'due_date' => 'Due Date',
			'issue_category_id' => 'Issue Category',
			'issue_status_id' => 'Issue Status',
			'user_id' => 'User',
			'issue_priority_id' => 'Issue Priority',
			'version_id' => 'Version',
			'assigned_to' => 'Assigned To',
			'created_on' => 'Created On',
			'updated_on' => 'Updated On',
			'start_date' => 'Start Date',
			'done_ratio' => 'Done Ratio',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('tracker_id',$this->tracker_id);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('due_date',$this->due_date,true);
		$criteria->compare('issue_category_id',$this->issue_category_id);
		$criteria->compare('issue_status_id',$this->issue_status_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('issue_priority_id',$this->issue_priority_id);
		$criteria->compare('version_id',$this->version_id);
		$criteria->compare('assigned_to',$this->assigned_to);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('updated_on',$this->updated_on,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('done_ratio',$this->done_ratio);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}