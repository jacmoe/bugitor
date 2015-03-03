<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%tracker}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_in_chlog
 * @property integer $is_in_roadmap
 * @property integer $position
 *
 * @property Issue[] $issues
 * @property ProjectTracker[] $projectTrackers
 * @property Project[] $projects
 */
class Tracker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tracker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_in_chlog', 'is_in_roadmap', 'position'], 'integer'],
            [['name'], 'string', 'max' => 255]
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
            'is_in_chlog' => Yii::t('app', 'Is In Chlog'),
            'is_in_roadmap' => Yii::t('app', 'Is In Roadmap'),
            'position' => Yii::t('app', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['tracker_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectTrackers()
    {
        return $this->hasMany(ProjectTracker::className(), ['tracker_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id' => 'project_id'])->viaTable('{{%project_tracker}}', ['tracker_id' => 'id']);
    }
}
