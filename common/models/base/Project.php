<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "project".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $homepage
 * @property integer $public
 * @property string $created
 * @property string $modified
 * @property string $identifier
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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['public'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['name', 'homepage', 'identifier'], 'string', 'max' => 255],
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
        return $this->hasMany(\common\models\ActionLog::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(\common\models\Issue::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueCategories()
    {
        return $this->hasMany(\common\models\IssueCategory::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(\common\models\Member::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectLinks()
    {
        return $this->hasMany(\common\models\ProjectLink::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTrackers()
    {
        return $this->hasMany(\common\models\ProjectTracker::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrackers()
    {
        return $this->hasMany(\common\models\Tracker::className(), ['id' => 'tracker_id'])->viaTable('project_tracker', ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepositories()
    {
        return $this->hasMany(\common\models\Repository::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersions()
    {
        return $this->hasMany(\common\models\Version::className(), ['project_id' => 'id']);
    }
}
