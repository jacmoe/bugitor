<?php
namespace app\models;
/*
* This file is part of
*  _                 _ _
* | |__  _   _  __ _(_) |_ ___  _ __
* | '_ \| | | |/ _` | | __/ _ \| '__|
* | |_) | |_| | (_| | | || (_) | |
* |_.__/ \__,_|\__, |_|\__\___/|_|
*              |___/
*                 issue tracker
*
*	Copyright (c) 2010 - 2016 Jacob Moen
*	Licensed under the MIT license
*/

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $homepage
 * @property integer $public
 * @property string $created
 * @property string $modified
 * @property string $identifier
 * @property string $image
 * @property string $logo
 * @property string $logoname
 * @property integer $owner_id
 * @property integer $updater_id
 *
 * @property ActionLog[] $actionLogs
 * @property Issue[] $issues
 * @property IssueCategory[] $issueCategories
 * @property Member[] $members
 * @property ProjectLink[] $projectLinks
 * @property ProjectTracker[] $projectTrackers
 * @property Tracker[] $trackers
 * @property Repository[] $repositories
 * @property Version[] $versions
 */
class Project extends \yii\db\ActiveRecord
{
    public $image; // stores the project logo image when uploaded

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'modified',
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'owner_id',
                'updatedByAttribute' => 'updater_id',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['public', 'owner_id', 'updater_id'], 'integer'],
            [['created', 'modified', 'image'], 'safe'],
            [['image'], 'file', 'extensions'=>'jpg, gif, png'],
            [['name', 'homepage', 'identifier', 'logo', 'logoname'], 'string', 'max' => 255],
            [['identifier'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'homepage' => Yii::t('app', 'Homepage'),
            'public' => Yii::t('app', 'Public'),
            'owner_id' => Yii::t('app', 'Owner'),
            'updater_id' => Yii::t('app', 'Updater'),
            'created' => Yii::t('app', 'Created'),
            'modified' => Yii::t('app', 'Modified'),
            'identifier' => Yii::t('app', 'Identifier'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionLogs()
    {
        return $this->hasMany(ActionLog::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueCategories()
    {
        return $this->hasMany(IssueCategory::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectLinks()
    {
        return $this->hasMany(ProjectLink::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTrackers()
    {
        return $this->hasMany(ProjectTracker::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrackers()
    {
        return $this->hasMany(Tracker::className(), ['id' => 'tracker_id'])->viaTable('{{%project_tracker}}', ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepositories()
    {
        return $this->hasMany(Repository::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersions()
    {
        return $this->hasMany(Version::className(), ['project_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ProjectQuery(get_called_class());
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if($insert) {
                $this->setAttribute('identifier', preg_replace( '/\s*/m', '', strtolower($this->getAttribute('name'))));
            }
            return true;

        } else {
            return false;
        }
    }
}
