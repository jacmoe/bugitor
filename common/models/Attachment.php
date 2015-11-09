<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%attachment}}".
 *
 * @property integer $id
 * @property integer $issue_id
 * @property integer $user_id
 * @property string $name
 * @property integer $size
 * @property string $created
 *
 * @property Issue $issue
 * @property User $user
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attachment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_id', 'user_id', 'name', 'size'], 'required'],
            [['issue_id', 'user_id', 'size'], 'integer'],
            [['created'], 'safe'],
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
            'issue_id' => Yii::t('app', 'Issue ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'created' => Yii::t('app', 'Created'),
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
     * @return AttachmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AttachmentQuery(get_called_class());
    }
}
