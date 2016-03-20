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
*	Copyright (c) 2009 - 2016 Jacob Moen
*	Licensed under the MIT license
*/

use Yii;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property string $content
 * @property integer $issue_id
 * @property integer $created
 * @property integer $create_user_id
 * @property integer $modified
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
            [['content', 'issue_id', 'created', 'modified'], 'required'],
            [['content'], 'string'],
            [['issue_id', 'created', 'create_user_id', 'modified', 'update_user_id'], 'integer'],
            [['create_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['create_user_id' => 'id']],
            [['issue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Issue::className(), 'targetAttribute' => ['issue_id' => 'id']],
            [['update_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['update_user_id' => 'id']],
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
        return $this->hasOne(User::className(), ['id' => 'create_user_id']);
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
    public function getUpdateUser()
    {
        return $this->hasOne(User::className(), ['id' => 'update_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentDetails()
    {
        return $this->hasMany(CommentDetail::className(), ['comment_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\CommentQuery(get_called_class());
    }
}
