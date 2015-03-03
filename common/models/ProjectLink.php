<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%project_link}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property integer $position
 * @property integer $project_id
 *
 * @property Project $project
 */
class ProjectLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'title', 'description', 'position', 'project_id'], 'required'],
            [['position', 'project_id'], 'integer'],
            [['url', 'title', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'position' => Yii::t('app', 'Position'),
            'project_id' => Yii::t('app', 'Project ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
}
