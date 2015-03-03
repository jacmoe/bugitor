<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "comment".
 *
 * @property integer $id
 * @property string $content
 * @property integer $issue_id
 * @property string $created
 * @property integer $create_user_id
 * @property string $modified
 * @property integer $update_user_id
 *
 * @property User $createUser
 * @property Issue $issue
 * @property User $updateUser
 * @property CommentDetail[] $commentDetails
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'issue_id'], 'required'],
            [['content'], 'string'],
            [['issue_id', 'create_user_id', 'update_user_id'], 'integer'],
            [['created', 'modified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Content'),
            'issue_id' => Yii::t('app', 'Issue ID'),
            'created' => Yii::t('app', 'Created'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'modified' => Yii::t('app', 'Modified'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'create_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(\common\models\Issue::className(), ['id' => 'issue_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'update_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentDetails()
    {
        return $this->hasMany(\common\models\CommentDetail::className(), ['comment_id' => 'id']);
    }
}
