<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%project_tracker}}".
 *
 * @property integer $project_id
 * @property integer $tracker_id
 *
 * @property Project $project
 * @property Tracker $tracker
 */
class ProjectTracker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_tracker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'tracker_id'], 'required'],
            [['project_id', 'tracker_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => Yii::t('app', 'Project ID'),
            'tracker_id' => Yii::t('app', 'Tracker ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTracker()
    {
        return $this->hasOne(Tracker::className(), ['id' => 'tracker_id']);
    }
}
