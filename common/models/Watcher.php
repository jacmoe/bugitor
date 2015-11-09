<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%watcher}}".
 *
 * @property integer $issue_id
 * @property integer $user_id
 *
 * @property Issue $issue
 * @property User $user
 */
class Watcher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%watcher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_id', 'user_id'], 'required'],
            [['issue_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'issue_id' => Yii::t('app', 'Issue ID'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::className(), ['id' => 'issue_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return WatcherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WatcherQuery(get_called_class());
    }
}
