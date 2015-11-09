<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%issue_priority}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Issue[] $issues
 */
class IssuePriority extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%issue_priority}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issue::className(), ['issue_priority_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return IssuePriorityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new IssuePriorityQuery(get_called_class());
    }
}
