<?php

/**
 * This is the model class for table "{{project}}".
 *
 * The followings are the available columns in table '{{project}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $homepage
 * @property integer $public
 * @property string $created
 * @property string $modified
 * @property string $identifier
 *
 * The followings are the available model relations:
 * @property Issue[] $issues
 * @property Tracker[] $bugTrackers
 * @property Repository[] $repositories
 * @property Version[] $versions
 * @property IssueCategory[] $issueCategories
 */
class Project extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Project the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{project}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('public', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 30),
            array('homepage', 'url'),
            array('identifier', 'length', 'max' => 20),
            array('description, created, modified', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, homepage, public, created, modified, identifier', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'issues' => array(self::HAS_MANY, 'Issue', 'project_id'),
            'bugTrackers' => array(self::MANY_MANY, 'Tracker', '{{project_tracker}}(project_id, tracker_id)'),
            'members' => array(self::MANY_MANY, 'User', '{{project_user_assignment}}(project_id, user_id)'),
            'repositories' => array(self::HAS_MANY, 'Repository', 'project_id'),
            'versions' => array(self::HAS_MANY, 'Version', 'project_id'),
            'issueCategories' => array(self::HAS_MANY, 'IssueCategory', 'project_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'homepage' => 'Homepage',
            'public' => 'Public',
            'created' => 'Created',
            'modified' => 'Modified',
            'identifier' => 'Identifier',
        );
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
                'attributes' => array('description', 'homepage', 'name'),
            ),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('homepage', $this->homepage, true);
        $criteria->compare('public', $this->public);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('identifier', $this->identifier, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public static function getProjectNameFromIdentifier($identifier) {
        $cacheKey = 'ProjectNameFromIdentifier_'.$identifier;
        $name = '';
        if(false === $name = Yii::app()->cache->get($cacheKey)) {
            $project = Project::model()->find('identifier=?', array($identifier));
            $name = $project->name;
            $cacheDependency = new CDbCacheDependency("
               SELECT `modified` FROM `bug_project`
                  WHERE `id` = {$project->id} LIMIT 1
            ");
            Yii::app()->cache->set($cacheKey, $name, 0, $cacheDependency);
        }
        return $name;
    }

    public static function getProjectIdFromIdentifier($identifier) {
        $cacheKey = 'ProjectIdFromIdentifier'.$identifier;
        $id = '';
        if(false === $id = Yii::app()->cache->get($cacheKey)) {
            $project = Project::model()->find('identifier=?', array($identifier));
            $id = $project->id;
            $cacheDependency = new CDbCacheDependency("
               SELECT `modified` FROM `bug_project`
                  WHERE `id` = {$project->id} LIMIT 1
            ");
            Yii::app()->cache->set($cacheKey, $id, 0, $cacheDependency);
        }
        return $id;
    }

    /**
     * Returns an array of available roles in which a user can be
      placed when being added to a project
     */
    public static function getUserRoleOptions() {
        return CHtml::listData(Rights::module()->getAuthorizer()->getRoles(),
                'name', 'name');
    }

    /**
     * Makes an association between a user and a the project
     */
    public function associateUserToProject($user) {
        $sql = "INSERT INTO bug_project_user_assignment (project_id,
user_id) VALUES (:projectId, :userId)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
        $command->bindValue(":userId", $user->id, PDO::PARAM_INT);
        return $command->execute();
    }

    public static function testTime() {
        $model = new TimeTest();
        $model->server_time = date("Y-m-d\TH:i:s\Z", time());//
        $model->sql_time = new CDbExpression('UTC_TIMESTAMP');
        $model->save();
        echo $model->sql_time;
    }

    public function associateUserToRole($role, $userId) {
	$model = User::model()->findByPk($userId);
        $model->attachBehavior('rights', new RightsUserBehavior);
        Yii::app()->getModule('rights')->getAuthorizer()->authManager->assign($role, $model->getId());
        $item = Yii::app()->getModule('rights')->getAuthorizer()->authManager->getAuthItem($role);
        //echo $item->getNameText();
    }

    /*
     * Determines whether or not a user is already part of a project
     */

    public function isUserInProject($user) {
        $sql = "SELECT user_id FROM bug_project_user_assignment WHERE
project_id=:projectId AND user_id=:userId";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
        $command->bindValue(":userId", $user->id, PDO::PARAM_INT);
        return $command->execute() == 1 ? true : false;
    }

    public function getMembers() {
        $criteria = new CDbCriteria;
        $criteria->compare('project_id', $this->id, true);
        return User::model()->with('members')->findAll($criteria);
    }

    public function getVersions() {
        $criteria = new CDbCriteria;
        $criteria->compare('project_id', $this->id, true);
        return Version::model()->findAll($criteria);
    }

    public function getCategories() {
        $criteria = new CDbCriteria;
        $criteria->compare('project_id', $this->id, true);
        return IssueCategory::model()->findAll($criteria);
    }

    public function getRepositories() {
        $criteria = new CDbCriteria;
        $criteria->compare('project_id', $this->id, true);
        return Repository::model()->findAll($criteria);
    }

}