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
 * @property ActionLog[] $activities
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
            'issueCount' => array(self::STAT, 'Issue', 'project_id'),
            'issueBugCount' => array(self::STAT, 'Issue', 'project_id', 'condition' => 'tracker_id=1'),
            'issueFeatureCount' => array(self::STAT, 'Issue', 'project_id', 'condition' => 'tracker_id=2'),
            'issueOpenBugCount' => array(self::STAT, 'Issue', 'project_id', 'condition' => 'tracker_id=1 AND closed=0'),
            'issueOpenFeatureCount' => array(self::STAT, 'Issue', 'project_id', 'condition' => 'tracker_id=2 AND closed=0'),
            'issueClosedBugCount' => array(self::STAT, 'Issue', 'project_id', 'condition' => 'tracker_id=1 AND closed=1'),
            'issueClosedFeatureCount' => array(self::STAT, 'Issue', 'project_id', 'condition' => 'tracker_id=2 AND closed=1'),
            'bugTrackers' => array(self::MANY_MANY, 'Tracker', '{{project_tracker}}(project_id, tracker_id)'),
            'members' => array(self::MANY_MANY, 'Member', 'project_id'),
            'repositories' => array(self::HAS_MANY, 'Repository', 'project_id'),
            'versions' => array(self::HAS_MANY, 'Version', 'project_id'),
            'issueCategories' => array(self::HAS_MANY, 'IssueCategory', 'project_id'),
            'activities' => array(self::HAS_MANY, 'ActionLog', 'project_id'),
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
            'BugitorTimestampBehavior' => array(
                'class' => 'application.behaviors.BugitorTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
            ),
//            'CSafeContentBehavior' => array(
//                'class' => 'application.behaviors.CSafeContentBehavior',
//                'attributes' => array('description', 'homepage', 'name'),
//            ),
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

    /*
     * Determines whether or not a user is already part of a project
     */
    public function isUserInProject($user) {
        $sql = "SELECT user_id FROM bug_member WHERE
project_id=:projectId AND user_id=:userId";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":projectId", $this->id, PDO::PARAM_INT);
        $command->bindValue(":userId", $user->id, PDO::PARAM_INT);
        return $command->execute() == 1 ? true : false;
    }

    public function getMembers() {
        $criteria = new CDbCriteria;
        $criteria->compare('project_id', $this->id, true);
        return Member::model()->with('user')->findAll($criteria);
    }

    public static function getNonMembers() {
        $members = Member::model()->findAll();
        $criteria = new CDbCriteria;
        $criteria->addNotInCondition('user_id', $members);
        $criteria1->addInCondition('project_id', Project::getProjectIdFromIdentifier($_GET['identifier']));
        return User::model()->findAll($criteria);
    }

    public static function isMemberOf() {
        if((isset(Yii::app()->user->id))&&(isset($_GET['identifier']))) {
            $criteria = new CDbCriteria();
            $criteria->select = 'user_id, project_id';
            $criteria->compare('user_id', Yii::app()->user->id);
            $criteria->compare('project_id', Project::getProjectIdFromIdentifier($_GET['identifier']));
            $member = Member::model()->findAll($criteria);
            return !empty($member);
        } else {
            return false;
        }
        return false;
    }

    public static function myProjects($user_id) {
        $criteria = new CDbCriteria();
        $criteria->distinct = true;
        $criteria->compare('user_id', $user_id, true);
        $members = Member::model()->findAll($criteria);
        $project_list = array();
        foreach ($members as $member) {
            $project_list[] = $member->project_id;
        }
        $criteria2 = new CDbCriteria;
        $criteria2->addInCondition('id', $project_list);
        $results =  Project::model()->findAll($criteria2);
        return $results;
    }

    public static function getNonMembersList() {
        $members = Member::model()->findAll();
        $criteria1 = new CDbCriteria();
        $criteria1->select = "user_id";
        $criteria1->compare('project_id', Project::getProjectIdFromIdentifier($_GET['identifier']));
        $members = Member::model()->findAll($criteria1);
        $member_list = array();
        foreach ($members as $member) {
            $member_list[] = $member->user_id;
        }
        $criteria2 = new CDbCriteria;
        $criteria2->addNotInCondition('id', $member_list);
        $results =  User::model()->findAll($criteria2);
        $user_list = array();
        foreach ($results as $result) {
            $user_list[$result->id] = $result->username;
        }
        return $user_list;
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